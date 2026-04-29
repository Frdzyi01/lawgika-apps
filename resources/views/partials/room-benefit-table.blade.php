{{--
    Reusable partial: Benefit dari Paket PT table
    -----------------------------------------------
    Variables expected:
      $benefits   — Collection of RoomBenefit models (with 'order' loaded)
      $roomLabel  — String, e.g. "Meeting Room" or "Podcast Room"
      $isAdmin    — bool, whether to show admin-only check-in/out controls
      $roomType   — 'meeting' | 'podcast'  (used in route params for admin controls)

    UPDATED:
      - Dynamic status (computed from logs, never stored)
      - "Lihat Detail" button links to detail page
      - Status badge colors: info/warning/success/danger
      - Admin check-in/out now reflects real active-session detection
--}}
@php use App\Models\RoomBenefit; @endphp

<div class="card shadow mb-4" style="border-left: 4px solid #198754;">
    <div class="card-header py-3 d-flex align-items-center justify-content-between"
         style="background: linear-gradient(135deg,#d4edda 0%,#c3e6cb 100%);">
        <h6 class="m-0 font-weight-bold" style="color:#155724;">
            🎁 Benefit dari Paket PT
        </h6>
        <span class="badge bg-success">Shared Pool</span>
    </div>
    <div class="card-body">
        @if($benefits->isEmpty())
            <div class="text-center py-3 text-muted">
                <i class="fa fa-info-circle me-1"></i>
                Tidak ada benefit ruangan yang aktif.
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th>No Order</th>
                        @if($isAdmin ?? false)
                        <th>Customer</th>
                        @endif
                        <th>Paket</th>
                        <th>Total</th>
                        <th>Dipakai</th>
                        <th>Sisa</th>
                        <th>Berlaku s/d</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($benefits as $b)
                    @php
                        // ── Dynamic status (NEVER stored — computed from live data) ──────────
                        $statusLabel = $b->status_label;    // uses getStatusLabelAttribute()
                        $statusColor = $b->status_color;    // uses getStatusColorAttribute()

                        $remainingPct = $b->total_minutes > 0
                            ? round(($b->remaining_minutes / $b->total_minutes) * 100)
                            : 0;

                        // ── Active session detection via logs ─────────────────────────────────
                        $lastLog     = $b->logs()->latest('action_at')->first();
                        $isCheckedIn = $lastLog && $lastLog->action === 'checkin';

                        // ── Detail route (role-aware) ──────────────────────────────────────────
                        $detailRoute = ($isAdmin ?? false)
                            ? route('admin.benefits.detail', $b->id)
                            : route('customer.benefits.detail', $b->id);
                    @endphp
                    <tr>
                        {{-- No Order --}}
                        <td>
                            <code style="font-size:.8rem;">
                                {{ $b->order->order_number ?? '#'.$b->order_id }}
                            </code>
                        </td>

                        {{-- Customer (admin only) --}}
                        @if($isAdmin ?? false)
                        <td>
                            {{ $b->user->name ?? '–' }}<br>
                            <small class="text-muted">{{ $b->user->email ?? '' }}</small>
                        </td>
                        @endif

                        {{-- Paket --}}
                        <td><span class="badge bg-primary">{{ $b->paket }}</span></td>

                        {{-- Total --}}
                        <td>{{ RoomBenefit::formatMinutes($b->total_minutes) }}</td>

                        {{-- Dipakai --}}
                        <td>
                            {{ RoomBenefit::formatMinutes($b->used_minutes) }}
                            <div class="progress mt-1" style="height:4px;">
                                <div class="progress-bar bg-warning"
                                     style="width:{{ 100 - $remainingPct }}%"></div>
                            </div>
                        </td>

                        {{-- Sisa --}}
                        <td>
                            @if($b->remaining_minutes <= 0)
                                <span class="badge bg-danger">Habis</span>
                            @else
                                <strong class="text-success">
                                    {{ RoomBenefit::formatMinutes($b->remaining_minutes) }}
                                </strong>
                            @endif
                        </td>

                        {{-- Berlaku s/d --}}
                        <td>
                            {{ $b->expired_at?->format('d M Y') ?? '–' }}
                        </td>

                        {{-- Dynamic Status Badge --}}
                        <td>
                            @php
                                $badgeStyles = [
                                    'Siap Digunakan'   => 'bg-info text-dark',
                                    'Sedang Digunakan' => 'bg-warning text-dark',
                                    'Selesai'          => 'bg-success',
                                    'Expired'          => 'bg-danger',
                                    'Nonaktif'         => 'bg-secondary',
                                ];
                                $badgeClass = $badgeStyles[$statusLabel] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                @if($statusLabel === 'Sedang Digunakan')
                                    <i class="fa fa-circle-dot fa-beat me-1"></i>
                                @endif
                                {{ $statusLabel }}
                            </span>
                        </td>

                        {{-- Aksi: Lihat Detail + (Admin) Check-In/Out --}}
                        <td>
                            <div class="d-flex flex-column gap-1">

                                {{-- [Lihat Detail] — visible to both admin and customer --}}
                                <a href="{{ $detailRoute }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-eye me-1"></i>Lihat Detail
                                </a>

                                {{-- Admin-only check-in / check-out buttons --}}
                                @if($isAdmin ?? false)
                                    @if($statusLabel === 'Siap Digunakan' || $statusLabel === 'Sedang Digunakan')
                                        @if($isCheckedIn)
                                            <form action="{{ route('admin.benefits.checkout', [$b->id, $roomType ?? 'meeting']) }}"
                                                  method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-warning text-dark w-100"
                                                        onclick="return confirm('Check Out sesi ini?')">
                                                    <i class="fa fa-sign-out-alt me-1"></i>Check Out
                                                </button>
                                            </form>
                                            <small class="text-muted text-center d-block">
                                                CI: {{ $lastLog->action_at->format('H:i') }}
                                            </small>
                                        @else
                                            <form action="{{ route('admin.benefits.checkin', [$b->id, $roomType ?? 'meeting']) }}"
                                                  method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-success w-100"
                                                        onclick="return confirm('Check In benefit?')">
                                                    <i class="fa fa-sign-in-alt me-1"></i>Check In
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-muted small text-center">{{ $statusLabel }}</span>
                                    @endif
                                @endif

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
