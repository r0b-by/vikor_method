@extends('dashboard.layouts.dashboardmain')

@section('content')
<div class="container py-5">
    <div class="mb-5 text-center" data-aos="fade-down" data-aos-duration="100">
        <h1 class="display-5 fw-bold text-gradient text-primary mb-3">Dashboard Siswa</h1>
        <p class="lead text-white-50">Pantau hasil seleksi VIKOR dan profil Anda secara real-time</p>
    </div>

    <div class="card shadow-lg border-0 bg-dark text-white rounded-4 overflow-hidden mb-5" data-aos="fade-up" data-aos-delay="200" data-aos-duration="100">
        <div class="card-header bg-gradient-info py-4 px-4">
            <h5 class="mb-0 fw-semibold fs-5 text-white">
                <i class="fas fa-user-circle me-2"></i> Informasi Profil Pengguna
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-unstyled mb-3">
                        <li class="mb-2" data-aos="fade-right" data-aos-delay="300"><strong><i class="fas fa-id-badge me-2 text-primary"></i> ID:</strong> {{ Auth::user()->id }}</li>
                        <li class="mb-2" data-aos="fade-right" data-aos-delay="350"><strong><i class="fas fa-user me-2 text-primary"></i> Nama Lengkap:</strong> {{ Auth::user()->name }}</li>
                        <li class="mb-2" data-aos="fade-right" data-aos-delay="400"><strong><i class="fas fa-envelope me-2 text-primary"></i> Email:</strong> {{ Auth::user()->email }}</li>
                        <li class="mb-2" data-aos="fade-right" data-aos-delay="450"><strong><i class="fas fa-id-card-alt me-2 text-primary"></i> NIS:</strong> {{ Auth::user()->nis ?? '-' }}</li>
                        <li class="mb-2" data-aos="fade-right" data-aos-delay="500"><strong><i class="fas fa-chalkboard-teacher me-2 text-primary"></i> Kelas:</strong> {{ Auth::user()->kelas ?? '-' }}</li>
                        <li class="mb-2" data-aos="fade-right" data-aos-delay="550"><strong><i class="fas fa-graduation-cap me-2 text-primary"></i> Jurusan:</strong> {{ Auth::user()->jurusan ?? '-' }}</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled mb-3">
                        <li class="mb-2" data-aos="fade-left" data-aos-delay="300"><strong><i class="fas fa-map-marker-alt me-2 text-primary"></i> Alamat:</strong> {{ Auth::user()->alamat ?? '-' }}</li>
                        <li class="mb-2" data-aos="fade-left" data-aos-delay="350"><strong><i class="fas fa-check-circle me-2 text-primary"></i> Email Diverifikasi:</strong> {{ Auth::user()->email_verified_at ? Auth::user()->email_verified_at->format('Y-m-d H:i') : 'Belum Diverifikasi' }}</li>
                        <li class="mb-2" data-aos="fade-left" data-aos-delay="400"><strong><i class="fas fa-calendar-plus me-2 text-primary"></i> Dibuat Pada:</strong> {{ Auth::user()->created_at->format('Y-m-d H:i') }}</li>
                        <li class="mb-2" data-aos="fade-left" data-aos-delay="450"><strong><i class="fas fa-calendar-alt me-2 text-primary"></i> Diperbarui Pada:</strong> {{ Auth::user()->updated_at->format('Y-m-d H:i') }}</li>
                        <li class="mb-2" data-aos="fade-left" data-aos-delay="500"><strong><i class="fas fa-info-circle me-2 text-primary"></i> Status Akun:</strong> <span class="badge {{ Auth::user()->status == 'active' ? 'bg-success' : (Auth::user()->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst(Auth::user()->status) }}</span></li>
                        <li class="mb-2" data-aos="fade-left" data-aos-delay="550"><strong><i class="fas fa-user-check me-2 text-primary"></i> Disetujui Oleh:</strong> {{ Auth::user()->approvedBy->name ?? '-' }}</li>
                        <li class="mb-2" data-aos="fade-left" data-aos-delay="600"><strong><i class="fas fa-calendar-check me-2 text-primary"></i> Disetujui Pada:</strong> {{ Auth::user()->approved_at ? Auth::user()->approved_at->format('Y-m-d H:i') : '-' }}</li>
                    </ul>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-3">
               <a href="{{ route('siswa.profile.edit', auth()->id()) }}" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Edit Profil
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-lg border-0 bg-gradient-dark text-white rounded-4 overflow-hidden" data-aos="fade-up" data-aos-delay="700" data-aos-duration="600">
        <div class="card-header bg-gradient-primary py-4 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold fs-5 text-white">
                    <i class="fas fa-chart-line me-2"></i> Hasil Perhitungan VIKOR
                </h5>
                <span class="badge bg-white text-primary fs-6 fw-bold" data-aos="zoom-in" data-aos-delay="800">
                    ID Alternatif: {{ $alternatif->alternatif_code ?? 'N/A' }}
                </span>
            </div>
        </div>

        <div class="card-body p-4">
            @if(Auth::user()->status === 'pending')
                <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert" data-aos="fade-in" data-aos-delay="500">
                    <i class="fas fa-clock me-3 fs-4"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Peninjauan Berlangsung</h5>
                        Pendaftaran Anda sedang diverifikasi. Silakan tunggu konfirmasi admin.
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif(!$alternatif)
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert" data-aos="fade-in" data-aos-delay="600">
                    <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Data Alternatif Tidak Ditemukan</h5>
                        Profil alternatif Anda belum terdaftar. Hubungi administrator.
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif(!$hasilVikor)
                <div class="alert alert-info alert-dismissible fade show d-flex align-items-center" role="alert" data-aos="fade-in" data-aos-delay="700">
                    <i class="fas fa-info-circle me-3 fs-4"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Proses Seleksi</h5>
                        Hasil VIKOR belum tersedia. Proses seleksi sedang berlangsung.
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @else
                <div class="table-responsive rounded-3 overflow-hidden mb-4" data-aos="fade-in" data-aos-delay="100">
                    <table class="table table-dark table-hover table-bordered align-middle mb-0">
                        <thead class="bg-gradient-secondary">
                            <tr>
                                <th class="text-center py-3" data-aos="fade-down" data-aos-delay="120">Kode</th>
                                <th class="text-center py-3" data-aos="fade-down" data-aos-delay="150">Nama</th>
                                <th class="text-center py-3" data-aos="fade-down" data-aos-delay="200">Utility (S)</th>
                                <th class="text-center py-3" data-aos="fade-down" data-aos-delay="250">Regret (R)</th>
                                <th class="text-center py-3" data-aos="fade-down" data-aos-delay="300">Indeks (Q)</th>
                                <th class="text-center py-3" data-aos="fade-down" data-aos-delay="350">Peringkat</th>
                                <th class="text-center py-3" data-aos="fade-down" data-aos-delay="400">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-aos="fade-up" data-aos-delay="1500">
                                <td class="fw-bold text-primary">{{ $hasilVikor->alternatif->alternatif_code ?? 'N/A' }}</td>
                                <td>{{ $hasilVikor->alternatif->alternatif_name ?? 'N/A' }}</td>
                                <td>{{ number_format($hasilVikor->nilai_s, 4) }}</td>
                                <td>{{ number_format($hasilVikor->nilai_r, 4) }}</td>
                                <td class="fw-bold">{{ number_format($hasilVikor->nilai_q, 4) }}</td>
                                <td>
                                    <span class="badge bg-primary rounded-pill fs-5 px-3 py-1">
                                        #{{ $hasilVikor->ranking }}
                                    </span>
                                </td>
                                <td>
                                    @if ($hasilVikor->status == 'Lulus')
                                        <span class="badge bg-success rounded-pill py-2 px-3 fw-bold" data-aos="zoom-in" data-aos-delay="200">
                                            <i class="fas fa-check-circle me-1"></i> Lulus
                                        </span>
                                    @else
                                        <span class="badge bg-danger rounded-pill py-2 px-3 fw-bold" data-aos="zoom-in" data-aos-delay="200">
                                            <i class="fas fa-times-circle me-1"></i> Tidak Lulus
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4" data-aos="fade-right" data-aos-delay="300">
                        <div class="card bg-dark border-0 h-100">
                            <div class="card-body text-center p-4">
                                <div class="icon-shape icon-lg bg-primary bg-gradient rounded-3 mb-3">
                                    <i class="fas fa-chart-bar text-white"></i>
                                </div>
                                <h5 class="mb-2">Nilai Utility (S)</h5>
                                <h2 class="fw-bold text-primary">{{ number_format($hasilVikor->nilai_s, 4) }}</h2>
                                <p class="small text-white-50 mb-0">Ukuran manfaat keseluruhan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                        <div class="card bg-dark border-0 h-100">
                            <div class="card-body text-center p-4">
                                <div class="icon-shape icon-lg bg-warning bg-gradient rounded-3 mb-3">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                                <h5 class="mb-2">Nilai Regret (R)</h5>
                                <h2 class="fw-bold text-warning">{{ number_format($hasilVikor->nilai_r, 4) }}</h2>
                                <p class="small text-white-50 mb-0">Tingkat penyesalan maksimum</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" data-aos="fade-left" data-aos-delay="500">
                        <div class="card bg-dark border-0 h-100">
                            <div class="card-body text-center p-4">
                                <div class="icon-shape icon-lg bg-info bg-gradient rounded-3 mb-3">
                                    <i class="fas fa-star text-white"></i>
                                </div>
                                <h5 class="mb-2">Indeks VIKOR (Q)</h5>
                                <h2 class="fw-bold text-info">{{ number_format($hasilVikor->nilai_q, 4) }}</h2>
                                <p class="small text-white-50 mb-0">Nilai kompromi akhir</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3 mt-4" data-aos="fade-up" data-aos-delay="400">
                    <a href="{{ route('siswa.cetak-hasil') }}" class="btn btn-primary btn-lg px-4 py-3">
                        <i class="fas fa-file-pdf me-2"></i> Cetak Hasil Saya
                    </a>
                    <button class="btn btn-outline-light btn-lg px-4 py-3" data-bs-toggle="modal" data-bs-target="#infoModal">
                        <i class="fas fa-info-circle me-2"></i> Panduan
                    </button>
                </div>

                <div class="bg-dark bg-opacity-50 p-4 rounded-3 mt-5 border border-secondary" data-aos="fade-in" data-aos-delay="500">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-lightbulb text-warning me-3 mt-1 fs-4"></i>
                        <div>
                            <h5 class="text-warning mb-2">Informasi Seleksi</h5>
                            <p class="mb-1">Peserta dengan peringkat 1-10 akan dinyatakan <span class="badge bg-success">Lulus</span> seleksi.</p>
                            <p class="mb-0">Hasil ini bersifat final dan tidak dapat diganggu gugat.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-primary">
                        <i class="fas fa-info-circle me-2"></i> Panduan Hasil VIKOR
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <h6 class="text-primary">Nilai S (Utility Measure)</h6>
                            <p class="small text-white-50">Menunjukkan jumlah manfaat keseluruhan dari alternatif, dihitung sebagai jumlah tertimbang dari normalisasi matriks keputusan.</p>
                        </li>
                        <li class="mb-3">
                            <h6 class="text-warning">Nilai R (Regret Measure)</h6>
                            <p class="small text-white-50">Mewakili penyesalan maksimum yang mungkin dialami jika memilih alternatif tertentu dibandingkan yang terbaik.</p>
                        </li>
                        <li>
                            <h6 class="text-info">Nilai Q (VIKOR Index)</h6>
                            <p class="small text-white-50">Indeks kompromi yang menentukan peringkat akhir, menggabungkan nilai S dan R dengan parameter v (biasanya 0.5).</p>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection