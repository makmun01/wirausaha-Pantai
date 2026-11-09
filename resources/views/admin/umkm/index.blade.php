@extends('layouts.admin')

@section('title', 'Manajemen & Verifikasi Mitra UMKM')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold brand-font mb-0 text-dark">Daftar Mitra Pelaku UMKM</h3>
        <p class="text-muted small mb-0">Verifikasi kelayakan pedagang, perajin batik, dan penginapan homestay</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom mb-0">
            <thead>
                <tr>
                    <th>Nama Toko / Usaha</th>
                    <th>Nama Pemilik</th>
                    <th>Kategori</th>
                    <th>Kontak WhatsApp</th>
                    <th>Status Verifikasi</th>
                    <th>Aksi Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($merchants as $m)
                    <tr>
                        <td>
                            <strong class="text-primary">{{ $m->business_name }}</strong>
                            <small class="d-block text-muted text-truncate" style="max-width:200px;">{{ $m->address }}</small>
                        </td>
                        <td>{{ $m->owner_name }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $m->category }}</span></td>
                        <td>{{ $m->phone_number }}</td>
                        <td>
                            <span class="badge {{ $m->verification_status === 'VERIFIED' ? 'bg-success' : ($m->verification_status === 'PENDING' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ $m->verification_status }}
                            </span>
                        </td>
                        <td>
                            @if($m->verification_status !== 'VERIFIED')
                                <form action="{{ route('admin.umkm.verify', $m->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="VERIFIED">
                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                                        <i class="bi bi-check-lg"></i> Setujui
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.umkm.verify', $m->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="SUSPENDED">
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                        Tangguhkan
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada mitra UMKM terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
