@extends('layouts.public')

@section('title', 'Keranjang Belanja - Jumiang UMKM')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4 brand-font"><i class="bi bi-cart3 text-primary me-2"></i>Keranjang Belanja</h2>

    <div class="row g-4" x-data="{
        items: [
            { id: 1, name: 'Rengginang Lorjuk Super Khas Jumiang', price: 35000, qty: 2, merchant: 'Sentra Olahan Lorjuk Bu Maryam' },
            { id: 2, name: 'Keripik Ikan Teri Krispi Gurih', price: 20000, qty: 1, merchant: 'Sentra Olahan Lorjuk Bu Maryam' }
        ],
        subtotal() {
            return this.items.reduce((sum, item) => sum + (item.price * item.qty), 0);
        }
    }">
        <div class="col-lg-8">
            <div class="card card-custom p-4">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th style="width: 130px;">Jumlah</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in items" :key="item.id">
                                <tr>
                                    <td>
                                        <div class="fw-bold" x-text="item.name"></div>
                                        <small class="text-muted"><i class="bi bi-shop me-1"></i> <span x-text="item.merchant"></span></small>
                                    </td>
                                    <td>Rp <span x-text="item.price.toLocaleString('id-ID')"></span></td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm text-center" x-model.number="item.qty" min="1" max="100">
                                    </td>
                                    <td class="fw-bold text-primary">Rp <span x-text="(item.price * item.qty).toLocaleString('id-ID')"></span></td>
                                    <td>
                                        <button class="btn btn-outline-danger btn-sm rounded-circle" @click="items.splice(index, 1)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-custom p-4">
                <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Total Pembelian</span>
                    <span class="fw-bold fs-5 text-primary">Rp <span x-text="subtotal().toLocaleString('id-ID')"></span></span>
                </div>
                <div class="py-2 mb-3">
                    <small class="text-muted d-block">Opsi Pengambilan</small>
                    <span class="badge bg-info text-dark">Ambil di Gerai Pantai (Pick-up)</span>
                </div>
                <a href="{{ route('checkout') }}" class="btn btn-ocean w-100 py-2 rounded-pill fw-bold">
                    Lanjutkan ke Checkout <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
