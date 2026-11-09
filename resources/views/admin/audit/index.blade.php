@extends('layouts.admin')

@section('title', 'Log Audit Trail Sistem')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold brand-font mb-0 text-dark">Log Audit Trail & Aktivitas Sistem</h3>
        <p class="text-muted small mb-0">Catatan riwayat mutasi data krusial untuk akuntabilitas dan audit forensik (Append-Only)</p>
    </div>
    <span class="badge bg-secondary px-3 py-2">AUDIT TRAIL MOD-14</span>
</div>

<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom mb-0">
            <thead>
                <tr>
                    <th>Waktu Kejadian</th>
                    <th>Pengguna</th>
                    <th>Aksi Mutasi</th>
                    <th>Entitas Target</th>
                    <th>Perubahan Data (Old vs New)</th>
                    <th>IP & Peranti</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td><small class="text-muted">{{ $log->created_at }}</small></td>
                        <td>
                            <strong class="d-block">{{ $log->user->name ?? 'Tamu / Sistem' }}</strong>
                            <small class="text-muted">{{ $log->user->email ?? '-' }}</small>
                        </td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary border">{{ $log->action }}</span></td>
                        <td>{{ $log->entity_type ? "{$log->entity_type} #{$log->entity_id}" : '-' }}</td>
                        <td>
                            @if($log->old_values || $log->new_values)
                                <details class="small">
                                    <summary class="text-primary cursor-pointer">Lihat Perubahan JSON</summary>
                                    <pre class="bg-light p-2 rounded mt-1 mb-0 border" style="font-size:0.75rem;">Old: {{ json_encode($log->old_values) }}&#10;New: {{ json_encode($log->new_values) }}</pre>
                                </details>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <code class="small text-secondary">{{ $log->ip_address ?? '127.0.0.1' }}</code>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada catatan log aktivitas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $logs->links() }}
    </div>
</div>
@endsection
