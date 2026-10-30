<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Reservasi Online</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Library Calendar --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        /* Base Color */
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
            --text-color: #5a5c69;
            --light-gray: #f8f9fc;
            --dark-gray: #5a5c69;
        }

        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: var(--text-color);
        }

        /* Navbar Styling */
        .navbar {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        /* Hero Section Styling */
        .hero-section {
            /* background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); */
            padding: 100px 0;
        }

        .hero-heading {
            font-weight: 700;
            color: #2e384d;
        }

        .hero-subheading {
            color: #4a5568;
            margin-bottom: 2rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        /* Section Styling */
        section {
            padding: 80px 0;
        }

        .section-title {
            font-weight: 700;
            margin-bottom: 2rem;
            color: #2e384d;
            text-align: center;
        }

        /* Calendar Styling */
        .calendar {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            overflow: hidden;
        }

        .calendar-header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: 700;
        }

        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            padding: 10px;
            font-weight: 600;
            border-bottom: 1px solid #e3e6f0;
        }

        .calendar-dates {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            grid-gap: 5px;
            padding: 15px;
        }

        .date {
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .date:hover {
            background-color: var(--light-gray);
        }

        .date.active {
            background-color: var(--primary-color);
            color: white;
        }

        .date.available {
            border: 2px solid var(--primary-color);
        }

        .date.unavailable {
            color: #d1d3e2;
            cursor: not-allowed;
        }

        /* Form Styling */
        .form-section {
            background-color: var(--secondary-color);
        }

        .form-container {
            background-color: rgb(235, 235, 235);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            /* Lebih natural */
            padding: 40px;
            border: 1px solid #e3e6f0;
            /* Tambahan border halus */
        }


        .form-label {
            font-weight: 600;
            color: #4a5568;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }

        /* Footer Styling */
        footer {
            background-color: #2e384d;
            color: white;
            padding: 50px 0 20px;
        }

        .footer-heading {
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-link:hover {
            color: white;
            text-decoration: underline;
        }

        .social-icon {
            font-size: 1.5rem;
            margin-right: 15px;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            color: white;
        }

        .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            margin-top: 30px;
        }

        /* Desain Card Ruangan */
        .room-card {
            height: 100%;
            /* Memastikan tinggi card tetap */
            display: flex;
            flex-direction: column;
        }

        .room-image {
            height: 200px;
            /* Tentukan tinggi gambar agar konsisten */
            object-fit: cover;
            /* Pastikan gambar tetap proporsional tanpa distorsi */
        }

        .card-body {
            flex-grow: 1;
            /* Membuat isi card tetap rapi */
            display: flex;
            flex-direction: column;
        }

        .card-title {
            font-size: 1.2rem;
        }

        .card-text {
            font-size: 1rem;
        }

        .pagination {
            justify-content: center;
        }

        .page-item.active .page-link {
            background-color: #007bff;
            /* Warna biru Bootstrap */
            border-color: #007bff;
            color: white;
        }

        .page-link {
            color: #007bff;
        }

        .page-link:hover {
            background-color: #e9ecef;
        }
    </style>
</head>

<body>
    @include('sweetalert::alert')
    <!-- 1. NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py- fixed-top">
        <div class="container">
            <!-- Logo/Brand Name -->
            <a class="navbar-brand text-primary" href="#">
                <img src="{{ asset('storage/img/logo_dinkes.png') }}" alt="Logo" width="50" height="50">
                ReservasiDinkes
            </a>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-4">
                    <li class="nav-item">
                        <a class="nav-link text-dark" style="font-weight: 500" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" style="font-weight: 500" href="#cara-reservasi">Cara Reservasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" style="font-weight: 500" href="#form-reservasi">Formulir
                            Peminjaman</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" style="font-weight: 500" href="#kontak">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- 2. HERO SECTION -->
    <section class="hero-section" style="margin-top: 36px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h1 class="hero-heading display-4 mb-3">Sistem Reservasi Online</h1>
                    <p class="hero-subheading lead">
                        Platform terpadu untuk peminjaman ruangan dengan mudah dan cepat.
                        Atur jadwal, pilih fasilitas, dan dapatkan konfirmasi.
                    </p>
                    <a href="#form-reservasi" class="btn btn-primary btn-lg px-5 py-3 rounded-pill">
                        Reservasi Sekarang
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('storage/img/ilustrasi.jpg') }}" alt="Sistem Reservasi" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- 3. CARA RESERVASI SECTION -->
    <section id="cara-reservasi">
        <div class="container">
            <h2 class="section-title mb-5">Cara Melakukan Reservasi</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-ui-checks fs-1"></i>
                            </div>
                            <h4 class="card-title mb-3">1. Pilih Opsi</h4>
                            <p class="card-text">Pilih ruangan yang ingin di reservsai</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-pencil-square fs-1"></i>
                            </div>
                            <h4 class="card-title mb-3">2. Isi Form</h4>
                            <p class="card-text">Lengkapi form reservasi dengan data yang dibutuhkan dan pastikan
                                semuanya
                                benar.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-calendar-check fs-1"></i>
                            </div>
                            <h4 class="card-title mb-3">3. Pilih Tanggal</h4>
                            <p class="card-text">Tentukan tanggal dan waktu yang tersedia sesuai kebutuhan reservasi
                                Anda.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-envelope-check fs-1"></i>
                            </div>
                            <h4 class="card-title mb-3">4. Tunggu Konfirmasi</h4>
                            <p class="card-text">Reservasi Anda sedang diproses. Konfirmasi akan dikirimkan melalui
                                nomor
                                whatsapp.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

    </section>

    <!-- 4. DAFTAR RUANGAN -->
    <section class="container mt-5">
        <h2 class="section-title mb-5" id="daftar-ruangan">Daftar Ruangan yang Bisa Dipinjam</h2>
        <div class="row" id="room-list">
            @foreach ($roomsPaginated as $room)
                <div class="col-md-4 mt-4">
                    <div class="card shadow-sm mb-4 room-card">
                        <img src="{{ asset('storage/' . $room->image) }}" class="card-img-top room-image"
                            alt="{{ $room->name }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary">{{ $room->name }}</h5>
                            <p class="card-text">
                                <strong>Kapasitas:</strong> {{ $room->capacity }} orang
                            </p>
                            <div class="mt-auto">
                                <a href="#form-reservasi" class="btn btn-outline-primary w-100">Reservasi</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Tampilkan Pagination -->
        <div class="mt-4">
            {{ $roomsPaginated->links() }}
        </div>
    </section>

    <!-- 5. CALENDAR SECTION -->
    <section id="kalender" class="bg-light">
        <div class="container">
            <h2 class="section-title">Kalender Ketersediaan</h2>
            <p class="text-center mb-5">
                Lihat tanggal yang tersedia dan yang sudah dipesan untuk memastikan jadwal reservasi Anda.
                Pilih tanggal yang masih kosong agar proses peminjaman berjalan lancar tanpa bentrokan jadwal.
            </p>

            {{-- Calendar --}}
            <div class="container">
                <div id="calendar"></div>
            </div>

            {{-- Modal Info Per/Hari --}}
            <div id="modal-action" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalActionTitle">Detail Event</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. FORM RESERVASI SECTION -->
    <section id="form-reservasi" class="form-section">
        <div class="container">
            <h2 class="section-title">Form Reservasi</h2>
            <p class="text-center mb-5">Lengkapi form berikut untuk melakukan reservasi</p>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="form-container">

                        <form class="needs-validation" action="{{ route('reservasi.store') }}" method="POST"
                            enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="row g-3">

                                <!-- Nama Organisasi -->
                                <div class="col-md-6 mb-3">
                                    <label for="namaOrganisasi" class="form-label">Nama Organisasi</label>
                                    <input type="text" class="form-control" name="nama_organisasi"
                                        id="namaOrganisasi" value="{{ old('nama_organisasi') }}"
                                        placeholder="Masukkan nama organisasi" required>
                                    <div class="invalid-feedback">Nama organisasi wajib diisi.</div>
                                </div>

                                <!-- Nama Peminjam -->
                                <div class="col-md-6 mb-3">
                                    <label for="namaPeminjam" class="form-label">Nama Peminjam</label>
                                    <input type="text" class="form-control" name="nama_peminjam"
                                        id="namaPeminjam" value="{{ old('nama_peminjam') }}"
                                        placeholder="Masukkan nama peminjam" required>
                                    <div class="invalid-feedback">Nama peminjam wajib diisi.</div>
                                </div>

                                <!-- No. HP -->
                                <div class="col-md-6 mb-3">
                                    <label for="noHP" class="form-label">No. HP</label>
                                    <input type="tel" class="form-control" name="no_hp" id="noHP"
                                        value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789"
                                        pattern="[0-9]{10,13}" required>
                                    <div class="invalid-feedback">Masukkan nomor HP yang valid (10-13 digit).</div>
                                </div>

                                <!-- Acara -->
                                <div class="col-md-6 mb-3">
                                    <label for="acara" class="form-label">Acara</label>
                                    <input type="text" class="form-control" name="acara" id="acara"
                                        value="{{ old('acara') }}" placeholder="Masukkan nama acara" required>
                                    <div class="invalid-feedback">Nama acara wajib diisi.</div>
                                </div>

                                <!-- Tanggal & Jam Pinjam -->
                                <div class="col-md-6 mb-3">
                                    <label for="tanggalPinjam" class="form-label">Tanggal & Jam Pinjam</label>
                                    <input type="datetime-local" class="form-control" name="tanggal_pinjam"
                                        id="tanggalPinjam" value="{{ old('tanggal_pinjam') }}" required>
                                    <div class="invalid-feedback">Tanggal dan jam pinjam wajib diisi.</div>
                                </div>

                                <!-- Tanggal & Jam Kembali -->
                                <div class="col-md-6 mb-3">
                                    <label for="tanggalKembali" class="form-label">Tanggal & Jam Kembali</label>
                                    <input type="datetime-local" class="form-control" name="tanggal_kembali"
                                        id="tanggalKembali" value="{{ old('tanggal_kembali') }}" required>
                                    <div class="invalid-feedback">Tanggal dan jam kembali wajib diisi.</div>
                                </div>

                                <!-- Tipe Peralatan -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Tipe</label>
                                    <div class="d-flex flex-wrap gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipe_peralatan"
                                                id="tipeRuangan" value="ruangan"
                                                {{ old('tipe_peralatan') == 'ruangan' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="tipeRuangan">Ruangan</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">Pilih salah satu tipe peralatan.</div>
                                </div>

                                <!-- Nama Ruangan -->
                                <div class="col-md-6 mb-3">
                                    <label for="namaRuangan" class="form-label">Nama Ruangan</label>
                                    <select class="form-select" name="nama_ruangan" id="namaRuangan" required>
                                        <option value="" disabled
                                            {{ old('nama_ruangan') == '' ? 'selected' : '' }}>Pilih ruangan</option>
                                        @foreach ($roomsAll as $room)
                                            <option value="{{ $room->id }}"
                                                {{ old('nama_ruangan') == $room->id ? 'selected' : '' }}>
                                                {{ $room->name . " (Kapasitas : " . $room->capacity . ")"}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Pilih ruangan yang akan dipinjam.</div>
                                </div>

                                <!-- Upload Surat Pinjam -->
                                <div class="col-md-6 mb-3">
                                    <label for="suratPinjam" class="form-label">Upload Surat Pinjam</label>
                                    <input type="file" class="form-control" name="surat_pinjam" id="suratPinjam"
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                    <div class="form-text">Format file: jpg, jpeg, png, PDF, DOC, atau DOCX. Maksimal
                                        2MB.</div>
                                    <div class="invalid-feedback">Upload surat pinjam wajib dilampirkan.</div>
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="col-12 mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="terms"
                                            id="termsConditions" required>
                                        <label class="form-check-label" for="termsConditions">
                                            Saya menyetujui <a href="#" class="text-decoration-none">Syarat dan
                                                Ketentuan</a> yang berlaku
                                        </label>
                                        <div class="invalid-feedback">Anda harus menyetujui syarat dan ketentuan.</div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-5 py-3 rounded-pill">
                                        Kirim Reservasi <i class="bi bi-send ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. CONTACT SECTION -->
    <section id="kontak">
        <div class="container">
            <h2 class="section-title">Hubungi Kami</h2>
            <p class="text-center mb-5">Jika Anda memiliki pertanyaan atau kendala, silakan hubungi kami</p>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-geo-alt fs-1"></i>
                            </div>
                            <h4 class="card-title mb-3">Alamat</h4>
                            <p class="card-text">Jl. Pemuda Nomor No.313, Mlinjon, Tonggalan, Kec. Klaten Tengah, Kabupaten Klaten, Jawa Tengah 57412</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-telephone fs-1"></i>
                            </div>
                            <h4 class="card-title mb-3">Telepon</h4>
                            <p class="card-text">+62 8222 0333 828</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-envelope fs-1"></i>
                            </div>
                            <h4 class="card-title mb-3">Email</h4>
                            <p class="card-text">dinas.kesehatan@klaten.go.id</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. FOOTER -->
    <footer>
        <div class="container">
            <div class="row g-4">
                <!-- Company Info -->
                <div class="col-lg-4">
                    <h4 class="footer-heading">ReservasiKu</h4>
                    <p class="mb-4">
                        Platform untuk peminjaman ruangan dengan mudah dan cepat.
                        Atur jadwal, pilih fasilitas, dan dapatkan konfirmasi instan.
                    </p>

                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-4">
                    <h5 class="footer-heading">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="footer-link">Home</a></li>
                        <li class="mb-2"><a href="#cara-reservasi" class="footer-link">Cara Reservasi</a></li>
                        <li class="mb-2"><a href="#kalender" class="footer-link">Kalender</a></li>
                        <li class="mb-2"><a href="#form-reservasi" class="footer-link">Form Reservasi</a></li>
                        <li class="mb-2"><a href="#kontak" class="footer-link">Kontak</a></li>
                    </ul>
                </div>

                <!-- Resources -->
                <div class="col-lg-2 col-md-4">
                    <h5 class="footer-heading">Resources</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="footer-link">FAQ</a></li>
                        <li class="mb-2"><a href="#" class="footer-link">Syarat & Ketentuan</a></li>
                        <li class="mb-2"><a href="#" class="footer-link">Kebijakan Privasi</a></li>
                        <li class="mb-2"><a href="#" class="footer-link">Bantuan</a></li>
                        <li class="mb-2"><a href="#" class="footer-link">Blog</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-4 col-md-4">
                    <h5 class="footer-heading">Kontak Kami</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="bi bi-geo-alt me-2"></i> Jl. Pemuda Nomor No.313, Mlinjon, Tonggalan, Kec. Klaten Tengah, Kabupaten Klaten, Jawa Tengah 57412
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-telephone me-2"></i> +62 8222 0333 828
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-envelope me-2"></i> dinas.kesehatan@klaten.go.id
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-clock me-2"></i> Senin - Jumat: 08.00 - 15.00 WIB
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="copyright text-center">
                <p>&copy; 2025 ReservasiKu. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Jquery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    </script>

    {{-- Java Script --}}
    <script>
        // Validasi Form berdasarkan validasi HTML
        (function() {
            'use strict'

            // Ambil DOM Setiap Form Dalam HTML
            var forms = document.querySelectorAll('.needs-validation')

            // Looping validasi sesuai validasi dalam HTML
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()

        // Mengambil data untuk keperluan tampilan calender
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'id',
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                events: {!! json_encode($events) !!}, // Kirim data event ke FullCalendar
                eventDidMount: function(info) {
                    info.el.style.cursor = 'pointer';
                },

                // Ketika Event di click maka akan muncul event detail
                eventClick: function(info) {
                    var loanId = info.event.id; // Ambil ID loan yang diklik

                    // AJAX request untuk mengambil data dari backend query dalam route
                    $.ajax({
                        url: '/loan/' + loanId, // Endpoint backend
                        type: 'GET',
                        dataType: 'json',
                        // Jika Sukses maka data akan di format dan di taruh dalam bentuk html modal
                        success: function(data) {
                            console.log("Data Loan:", data);

                            // Masukkan data ke modal html
                            $('#modalActionTitle').text(data.acara);
                            $('.modal-body').html(`
                                    <p><strong>Organisasi:</strong> ${data.nama_organisasi}</p>
                                    <p><strong>Ruangan:</strong> ${data.ruangan}</p>

                                    <p><strong>Tanggal Pinjam:</strong> ${new Date(data.tanggal_pinjam).toLocaleDateString('id-ID', {
                                        weekday: 'long', day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false
                                    })}</p>

                                    <p><strong>Tanggal Kembali:</strong> ${new Date(data.tanggal_kembali).toLocaleDateString('id-ID', {
                                        weekday: 'long', day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false
                                    })}</p>

                                    <p><strong>Keterangan:</strong> Acara ${data.keterangan}</p>
                                    ${data.gambar ? `<img src="${data.gambar}" class="img-fluid mt-3" alt="Gambar">` : ''}
                                `);

                            // Tampilkan modal
                            $('#modal-action').modal('show');
                        },
                        // Kalau Error
                        error: function(xhr, status, error) {
                            console.error("Terjadi kesalahan:", error);
                            alert("Data tidak ditemukan atau terjadi kesalahan.");
                        }
                    });
                }
            });
            // render data ke kalender
            calendar.render();
        });

        // JS Pemanis
        document.addEventListener("DOMContentLoaded", function() {
            if (window.location.search.includes("page=")) {
                document.getElementById("daftar-ruangan").scrollIntoView({
                    behavior: "smooth"
                });
            }
        });
    </script>
</body>

</html>
