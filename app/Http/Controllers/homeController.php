<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Room;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;



class homeController extends Controller
{
    /**
     * Fungsi untuk menampilkan data dari database
     */
    public function index(Request $request)
    {
        // Ambil semua ruangan untuk dropdown (tanpa pagination)
        $roomsAll = Room::orderBy('name')->get();

        // Ambil ruangan dengan pagination (untuk daftar ruangan)
        $roomsPaginated = Room::paginate(6);

        // Ambil data peminjaman beserta nama ruangan
        $events = DB::table('loans')
            ->join('rooms', 'loans.room_id', '=', 'rooms.id') // Join dengan tabel rooms
            ->Where('loans.status', 'approved')
            ->select(
                'loans.id',
                'loans.acara',
                'loans.nama_organisasi',
                'loans.tanggal_pinjam as start',
                'loans.tanggal_kembali as end',
                'rooms.name as room_name' // Ambil nama ruangan dari tabel rooms
            )
            ->get()
            // Hanya ambil data penting saja untuk keperluan data kalender
            ->map(fn($loan) => [
                'id' => $loan->id,
                'title' => $loan->acara . ' - ' . $loan->nama_organisasi . ' ( Ruang  ' . $loan->room_name . ')', // Tambahkan nama ruangan di title
                'start' => $loan->start,
                'end' => $loan->end,
            ]);

        return view('home', compact('roomsPaginated', 'roomsAll', 'events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // Validasi Input dari form dengan validasi laravel
        $validator = Validator::make($request->all(), [
            'nama_organisasi' => ['required', 'string', 'max:255'],
            'nama_peminjam'   => ['required', 'string', 'max:255'],
            'no_hp'           => ['required', 'digits_between:10,15'], // No HP harus angka dan 10-15 digit
            'acara'           => ['required', 'string', 'max:255'],
            'tanggal_pinjam'  => ['required', 'date', 'after_or_equal:today'],
            'tanggal_kembali' => ['required', 'date', 'after:tanggal_pinjam'],
            'tipe_peralatan'  => ['required', 'string', 'max:255'],
            'nama_ruangan'    => ['required', 'exists:rooms,id'],
            'surat_pinjam'    => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);
    
        // Error handling jika terjadi data yang tidak di inginkan lalu di tampilkan dalam HTML dan proses penyimpanan di hentikan
        if ($validator->fails()) {
            if ($validator->errors()->has('tanggal_pinjam')) {
                Alert::error('Gagal!', 'Tanggal peminjaman harus hari ini atau setelahnya.');
            } elseif ($validator->errors()->has('tanggal_kembali')) {
                Alert::error('Gagal!', 'Tanggal pengembalian harus setelah tanggal peminjaman.');
            } elseif ($validator->errors()->has('surat_pinjam')) {
                Alert::error('Gagal!', 'Format surat peminjaman harus PDF, JPG, JPEG, atau PNG dengan ukuran maksimal 2MB.');
            } else {
                Alert::error('Gagal!', 'Harap isi semua data dengan benar.');
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Ambil data peminjaman yang sudah "accepted" untuk ruangan yang dipilih
        $loans = DB::table('loans')
            ->join('rooms', 'loans.room_id', '=', 'rooms.id')
            ->where('loans.room_id', $request->nama_ruangan)
            ->where('loans.status', 'approved')
            ->select('loans.tanggal_pinjam', 'loans.tanggal_kembali', 'loans.nama_organisasi', 'rooms.name as room_name')
            ->get();

        // Validasi bentrokan jadwal dengan loop jika ada bentrokan maka akan otomatis tidak tersimpan dalam database
        foreach ($loans as $loan) {
            $loanStart = strtotime($loan->tanggal_pinjam);
            $loanEnd   = strtotime($loan->tanggal_kembali);
            $requestStart = strtotime($request->tanggal_pinjam);
            $requestEnd   = strtotime($request->tanggal_kembali);
    
            if (
                ($requestStart >= $loanStart && $requestStart <= $loanEnd) ||  // Bentrok di tengah periode pinjaman
                ($requestEnd >= $loanStart && $requestEnd <= $loanEnd) ||      // Bentrok di akhir periode pinjaman
                ($requestStart <= $loanStart && $requestEnd >= $loanEnd)       // Menutupi seluruh periode pinjaman
            ) {
                // Format tanggal agar lebih mudah dibaca
                $tanggalPinjam = \Carbon\Carbon::parse($loan->tanggal_pinjam)->translatedFormat('d F Y, H:i');
                $tanggalKembali = \Carbon\Carbon::parse($loan->tanggal_kembali)->translatedFormat('d F Y, H:i');
    
                // Tampilkan error jika jadwal bentrok
                Alert::error('Gagal!', "Ruangan '{$loan->room_name}' sudah dipesan oleh '{$loan->nama_organisasi}' dari {$tanggalPinjam} hingga {$tanggalKembali}.")->autoclose(0);
    
                return redirect()->back()->withInput();
            }
        }
    
        // Simpan data jika semua validasi berhasil dengan try catch
        try {
            $validatedData = $validator->validated();
    
            // Simpan file dengan nama unik
            $suratPath = $request->file('surat_pinjam')->store('uploads/surat_pinjam', 'public');
    
            Loan::create([
                'room_id'         => $validatedData['nama_ruangan'],
                'nama_organisasi' => $validatedData['nama_organisasi'],
                'nama_peminjam'   => $validatedData['nama_peminjam'],
                'no_hp'           => $validatedData['no_hp'],
                'acara'           => $validatedData['acara'],
                'tanggal_pinjam'  => $validatedData['tanggal_pinjam'],
                'tanggal_kembali' => $validatedData['tanggal_kembali'],
                'surat_pinjam'    => $suratPath,
                'status'          => 'pending', // Status awal peminjaman
            ]);
    
            // Notifikasi sukses
            Alert::success('Pemesanan Berhasil!', 'Permintaan peminjaman Anda telah dikirim. Silakan tunggu persetujuan dari admin.');
    
            return redirect()->back()->with('success', 'Reservasi berhasil dikirim!');
        } catch (\Exception $e) {
            Alert::error('Gagal!', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }
    
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
