@extends('layouts.admin')

@section('title', 'Manajemen Pesanan Marketplace')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold brand-font mb-0 text-dark">Daftar Transaksi Pesanan Masuk</h3>
        <p class="text-muted small mb-0">Kelola status pemenuhan pesanan (Materi RPS Minggu 4: State Transition Testing)</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom mb-0">
            <thead>
                <tr>
                    <th>Invoice & Waktu</th>
                    <th>Pemesan</th>
                    <th>Gerai UMKM</th>
                    <th>Detail Barang</th>
                    <th>Total</th>
                    <th>Status Saat Ini</th>
                    <th>Ubah Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                    <tr>
                        <td>
                            <strong class="text-primary">{{ $o->invoice_no }}</strong>
                            <small class="d-block text-muted">{{ $o->created_at->format('d/m/Y H:i') }}</small>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $o->user->name }}</span>
                            <small class="d-block text-muted">{{ $o->delivery_option }}: {{ $o->delivery_notes ?? '-' }}</small>
                        </td>
                        <td>{{ $o->merchant->business_name }}</td>
                        <td>
                            <ul class="list-unstyled small mb-0">
                                @foreach($o->items as $item)
                                    <li>{{ $item->quantity }}x {{ $item->product->name }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="fw-bold">Rp {{ number_format($o->total_amount, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $o->status === 'COMPLETED' || $o->status === 'PAID' ? 'bg-success' : ($o->status === 'CANCELLED' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ $o->status }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.orders.update_status', $o->id) }}" method="POST" class="d-flex gap-1">
                                @csrf
                                <select name="status" class="form-select form-select-sm" style="width:130px;">
                                    <option value="PENDING" {{ $o->status === 'PENDING' ? 'selected' : '' }}>Pending</option>
                                    <option value="PAID" {{ $o->status === 'PAID' ? 'selected' : '' }}>Paid</option>
                                    <option value="PROCESSING" {{ $o->status === 'PROCESSING' ? 'selected' : '' }}>Processing</option>
                                    <option value="READY_FOR_PICKUP" {{ $o->status === 'READY_FOR_PICKUP' ? 'selected' : '' }}>Ready</option>
                                    <option value="COMPLETED" {{ $o->status === 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                                    <option value="CANCELLED" {{ $o->status === 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bi bi-check2"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Belum ada transaksi pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $orders->links() }}
    </div>
</div>
@endsection
