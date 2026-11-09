@extends('layouts.admin')

@section('title', 'Buku Mutasi Inventori & Stok')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold brand-font mb-0 text-dark">Buku Mutasi Inventori & Stok</h3>
        <p class="text-muted small mb-0">Catatan pergerakan stok masuk, keluar, dan penyesuaian (Materi RPS Minggu 5: White Box Testing)</p>
    </div>
    <button class="btn btn-ocean btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalStockAdjust">
        <i class="bi bi-plus-slash-minus me-1"></i> Catat Mutasi Stok Manual
    </button>
</div>

<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom mb-0">
            <thead>
                <tr>
                    <th>Waktu Mutasi</th>
                    <th>Nama Produk</th>
                    <th>Gerai UMKM</th>
                    <th>Tipe Mutasi</th>
                    <th>Kuantitas</th>
                    <th>Stok Sebelum</th>
                    <th>Stok Sesudah</th>
                    <th>Referensi / Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at }}</td>
                        <td><strong class="text-primary">{{ $log->product->name }}</strong></td>
                        <td>{{ $log->product->merchant->business_name }}</td>
                        <td>
                            <span class="badge {{ $log->type === 'IN' ? 'bg-success' : ($log->type === 'OUT' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ $log->type }}
                            </span>
                        </td>
                        <td class="fw-bold">{{ $log->type === 'IN' ? '+' : ($log->type === 'OUT' ? '-' : '') }}{{ $log->quantity }}</td>
                        <td>{{ $log->previous_stock }}</td>
                        <td class="fw-bold text-dark">{{ $log->current_stock }}</td>
                        <td>
                            <span class="small d-block text-secondary">{{ $log->reference_type ?? '-' }}</span>
                            <small class="text-muted">{{ $log->notes }}</small>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">Belum ada riwayat mutasi stok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $logs->links() }}
    </div>
</div>

<!-- Modal Stock Adjustment -->
<div class="modal fade" id="modalStockAdjust" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Catat Mutasi Stok Manual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.inventory.adjust') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Produk</label>
                        <select name="product_id" class="form-select" required>
                            @foreach(\App\Models\Product::all() as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->name }} (Stok saat ini: {{ $prod->stock }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Jenis Mutasi</label>
                        <select name="type" class="form-select" required>
                            <option value="IN">Stok Masuk (Produksi / Pembelian Bahan Baru)</option>
                            <option value="OUT">Stok Keluar (Penjualan Offline / Rusak)</option>
                            <option value="ADJUSTMENT">Penyesuaian Fisik (Stock Opname)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Jumlah Kuantitas</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan / Alasan Mutasi</label>
                        <input type="text" name="notes" class="form-control" placeholder="Contoh: Produksi harian 50 bungkus" required>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-ocean rounded-pill px-4 fw-bold">Simpan Mutasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
