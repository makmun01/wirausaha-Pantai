@extends('layouts.admin')

@section('title', 'Katalog Produk UMKM')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold brand-font mb-0 text-dark">Manajemen Produk UMKM</h3>
        <p class="text-muted small mb-0">Kelola daftar harga, stok persediaan, dan foto produk jualan</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-ocean btn-sm rounded-pill px-3">
        <i class="bi bi-plus-circle me-1"></i> Tambah Produk Baru
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom mb-0">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Gerai UMKM</th>
                    <th>Kategori</th>
                    <th>Harga Satuan</th>
                    <th>Stok</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                    <tr>
                        <td>
                            <strong class="text-primary">{{ $p->name }}</strong>
                            <small class="d-block text-muted">{{ $p->unit }}</small>
                        </td>
                        <td>{{ $p->merchant->business_name }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $p->category->name }}</span></td>
                        <td class="fw-bold">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $p->stock <= $p->low_stock_threshold ? 'bg-danger' : 'bg-success' }}">
                                {{ $p->stock }} {{ $p->unit }}
                            </span>
                            @if($p->stock <= $p->low_stock_threshold)
                                <small class="text-danger d-block" style="font-size:0.7rem;">Stok Menipis!</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $p->is_active ? 'bg-primary' : 'bg-secondary' }}">
                                {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada produk terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>
@endsection
