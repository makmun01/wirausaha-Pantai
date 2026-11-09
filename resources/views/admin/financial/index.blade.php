@extends('layouts.admin')

@section('title', 'Buku Kas Keuangan UMKM Mandiri')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold brand-font mb-0 text-dark">Buku Kas & Pembukuan Keuangan UMKM</h3>
        <p class="text-muted small mb-0">Pemisahan Rekening Usaha vs Pribadi sesuai Konsep Transformasi Digital UMKM</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.financial.profit_loss') }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
            <i class="bi bi-graph-up-arrow me-1"></i> Laporan Laba Rugi
        </a>
        <button class="btn btn-ocean btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalAddTx">
            <i class="bi bi-plus-circle me-1"></i> Catat Pemasukan / Pengeluaran
        </button>
    </div>
</div>

<!-- Separated Accounts Overview Cards -->
<div class="row g-4 mb-4">
    @foreach($accounts as $acc)
        <div class="col-md-6">
            <div class="card card-stat border-start border-4 {{ $acc->account_type === 'BUSINESS' ? 'border-primary' : 'border-secondary' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge {{ $acc->account_type === 'BUSINESS' ? 'bg-primary' : 'bg-secondary' }} mb-2">
                            {{ $acc->account_type === 'BUSINESS' ? 'REKENING KAS USAHA' : 'REKENING PRIBADI / KELUARGA' }}
                        </span>
                        <h5 class="fw-bold mb-1">{{ $acc->account_name }}</h5>
                        <small class="text-muted">Pencatatan kas terisolasi (Non-campur aduk)</small>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block">Saldo Berjalan</small>
                        <span class="fs-4 fw-bold {{ $acc->account_type === 'BUSINESS' ? 'text-primary' : 'text-secondary' }}">
                            Rp {{ number_format($acc->current_balance, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Financial Journal Table -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-journal-text me-2 text-primary"></i>Jurnal Mutasi Kas Harian</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Rekening Kas</th>
                    <th>Kategori Arus Kas</th>
                    <th>Keterangan</th>
                    <th>Jenis</th>
                    <th>Nominal Mutasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                    <tr>
                        <td>{{ $t->transaction_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge {{ $t->account->account_type === 'BUSINESS' ? 'bg-primary bg-opacity-10 text-primary border' : 'bg-secondary bg-opacity-10 text-secondary border' }}">
                                {{ $t->account->account_name }}
                            </span>
                        </td>
                        <td>{{ $t->category->name }}</td>
                        <td>{{ $t->description }}</td>
                        <td>
                            <span class="badge {{ $t->transaction_type === 'INCOME' ? 'bg-success' : 'bg-danger' }}">
                                {{ $t->transaction_type }}
                            </span>
                        </td>
                        <td class="fw-bold {{ $t->transaction_type === 'INCOME' ? 'text-success' : 'text-danger' }}">
                            {{ $t->transaction_type === 'INCOME' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada mutasi keuangan tercatat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $transactions->links() }}
    </div>
</div>

<!-- Modal Add Transaction -->
<div class="modal fade" id="modalAddTx" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Catat Transaksi Keuangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.financial.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Rekening Kas</label>
                        <select name="financial_account_id" class="form-select" required>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">
                                    {{ $acc->account_name }} ({{ $acc->account_type }}) - Saldo: Rp {{ number_format($acc->current_balance, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted" style="font-size:0.75rem;">Pastikan memilih Kas Usaha untuk transaksi operasional dagang.</small>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-bold">Jenis Transaksi</label>
                            <select name="transaction_type" class="form-select" required>
                                <option value="INCOME">Pemasukan (Income)</option>
                                <option value="EXPENSE">Pengeluaran (Expense)</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->type }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-bold">Nominal (IDR)</label>
                            <input type="number" name="amount" class="form-control" placeholder="50000" min="1" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold">Tanggal</label>
                            <input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan / Deskripsi Transaksi</label>
                        <input type="text" name="description" class="form-control" placeholder="Contoh: Beli minyak goreng & bumbu rempah lorjuk" required>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-ocean rounded-pill px-4 fw-bold">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
