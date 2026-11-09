@extends('layouts.admin')

@section('title', 'Manajemen Kemitraan & Kerja Sama (MoU)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold brand-font mb-0 text-dark">Arsip Kemitraan & Kerja Sama (MoU)</h3>
        <p class="text-muted small mb-0">Pencatatan nota kesepahaman akademisi, pemerintah, asosiasi usaha, dan sponsor</p>
    </div>
    <button class="btn btn-ocean btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalAddPartner">
        <i class="bi bi-plus-circle me-1"></i> Catat Mitra Baru
    </button>
</div>

<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom mb-0">
            <thead>
                <tr>
                    <th>Lembaga / Mitra</th>
                    <th>Tipe Kemitraan</th>
                    <th>Narahubung</th>
                    <th>Periode Kerja Sama</th>
                    <th>Status</th>
                    <th>Ruang Lingkup / Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($partnerships as $p)
                    <tr>
                        <td><strong class="text-primary">{{ $p->partner_name }}</strong></td>
                        <td><span class="badge bg-light text-dark border">{{ $p->partnership_type }}</span></td>
                        <td>
                            {{ $p->contact_person }}
                            <small class="d-block text-muted">{{ $p->phone_number ?? '-' }}</small>
                        </td>
                        <td>{{ $p->start_date->format('d/m/Y') }} s/d {{ $p->end_date ? $p->end_date->format('d/m/Y') : 'Seterusnya' }}</td>
                        <td>
                            <span class="badge {{ $p->status === 'ACTIVE' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td><small class="text-secondary">{{ $p->notes ?? '-' }}</small></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada data kemitraan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Add Partnership -->
<div class="modal fade" id="modalAddPartner" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Catat Kemitraan / MoU Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.partnerships.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lembaga / Mitra</label>
                        <input type="text" name="partner_name" class="form-control" placeholder="Contoh: Universitas Madura / Bank Jatim" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-bold">Jenis Kemitraan</label>
                            <select name="partnership_type" class="form-select" required>
                                <option value="AKADEMISI">Akademisi (KKN / Riset)</option>
                                <option value="PEMERINTAH">Pemerintah (Dinas / CSR)</option>
                                <option value="KOMUNITAS">Asosiasi / Komunitas</option>
                                <option value="SPONSOR">Sponsor Komersial</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold">Narahubung (PIC)</label>
                            <input type="text" name="contact_person" class="form-control" placeholder="Nama PIC" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-bold">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold">Tanggal Berakhir</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ruang Lingkup / Catatan Kerja Sama</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Jelaskan poin penting MoU..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-ocean rounded-pill px-4 fw-bold">Simpan Kemitraan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
