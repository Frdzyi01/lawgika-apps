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
      - "{{ __('room_benefit.view_detail') }}" button links to detail page
      - Status badge colors: info/warning/success/danger
      - Admin check-in/out now reflects real active-session detection
--}}
@php use App\Models\RoomBenefit; @endphp

<div class="card shadow mb-4" style="border-left: 4px solid #198754;">
    <div class="card-header py-3 d-flex align-items-center justify-content-between"
         style="background: linear-gradient(135deg,#d4edda 0%,#c3e6cb 100%);">
        <h6 class="m-0 font-weight-bold" style="color:#155724;">
            🎁 {{ __('room_benefit.title') }}
        </h6>
        <span class="badge bg-success">{{ __('room_benefit.shared_pool') }}</span>
    </div>
    <div class="card-body">
        @if($benefits->isEmpty())
            <div class="text-center py-3 text-muted">
                <i class="fa fa-info-circle me-1"></i>
                {{ __('room_benefit.no_active') }}
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th>{{ __('room_benefit.order_no') }}</th>
                        @if($isAdmin ?? false)
                        <th>{{ __('room_benefit.customer') }}</th>
                        @endif
                        <th>{{ __('room_benefit.package') }}</th>
                        <th>{{ __('room_benefit.total') }}</th>
                        <th>{{ __('room_benefit.used') }}</th>
                        <th>{{ __('room_benefit.remaining') }}</th>
                        <th>{{ __('room_benefit.valid_until') }}</th>
                        <th>{{ __('room_benefit.status') }}</th>
                        <th>{{ __('room_benefit.action') }}</th>
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

                        // ── {{ __('room_benefit.modal.btn.detail') }} route (role-aware) ──────────────────────────────────────────
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
                                    __('room_benefit.modal.status.in_use') => 'bg-warning text-dark',
                                    __('room_benefit.modal.status.finished')          => 'bg-success',
                                    'Expired'          => 'bg-danger',
                                    'Nonaktif'         => 'bg-secondary',
                                ];
                                $badgeClass = $badgeStyles[$statusLabel] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                @if($statusLabel === __('room_benefit.modal.status.in_use'))
                                    <i class="fa fa-circle-dot fa-beat me-1"></i>
                                @endif
                                {{ match($statusLabel) { 'Siap Digunakan' => __('room_benefit.status.ready'), 'Sedang Digunakan' => __('room_benefit.status.in_use'), 'Selesai' => __('room_benefit.status.finished'), 'Expired' => __('room_benefit.status.expired'), 'Nonaktif' => __('room_benefit.status.inactive'), default => $statusLabel } }}
                            </span>
                        </td>

                        {{-- Aksi: {{ __('room_benefit.view_detail') }} + (Admin) Check-In/Out --}}
                        <td>
                            <div class="d-flex flex-column gap-1">

                                {{-- [{{ __('room_benefit.view_detail') }}] — visible to both admin and customer --}}
                                <a href="{{ $detailRoute }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-eye me-1"></i>{{ __('room_benefit.view_detail') }}
                                </a>

                                @php
                                    $res = \App\Models\MeetingRoomBooking::where('benefit_id', $b->id)->orderBy('date', 'desc')->get();
                                @endphp
                                @if($res->count() > 0)
                                    <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalBenefitBookings-{{ $b->id }}">
                                        <i class="fa fa-ticket-alt me-1"></i> {{ __('room_benefit.reservation') }} ({{ $res->count() }})
                                    </button>
                                @endif

                                {{-- Admin-only check-in / check-out buttons --}}
                                @if($isAdmin ?? false)
                                    @if($statusLabel === 'Siap Digunakan' || $statusLabel === __('room_benefit.modal.status.in_use'))
                                        @if($isCheckedIn)
                                            <form action="{{ route('admin.benefits.checkout', [$b->id, $roomType ?? 'meeting']) }}"
                                                  method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-warning text-dark w-100"
                                                        onclick="return confirm('{{ __('room_benefit.checkout') }} sesi ini?')">
                                                    <i class="fa fa-sign-out-alt me-1"></i>{{ __('room_benefit.checkout') }}
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
                                                        onclick="return confirm('{{ __('room_benefit.checkin') }} benefit?')">
                                                    <i class="fa fa-sign-in-alt me-1"></i>{{ __('room_benefit.checkin') }}
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-muted small text-center">{{ match($statusLabel) { 'Siap Digunakan' => __('room_benefit.status.ready'), 'Sedang Digunakan' => __('room_benefit.status.in_use'), 'Selesai' => __('room_benefit.status.finished'), 'Expired' => __('room_benefit.status.expired'), 'Nonaktif' => __('room_benefit.status.inactive'), default => $statusLabel } }}</span>
                                    @endif
                                @endif

                            </div>

                            @if($res->count() > 0)
                            <!-- Modal Popup for Benefit Reservations -->
                            <div class="modal fade" id="modalBenefitBookings-{{ $b->id }}" tabindex="-1" aria-labelledby="modalBenefitBookingsLabel-{{ $b->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                                        <div class="modal-header bg-primary text-white border-0 py-3" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                                            <h5 class="modal-title fw-bold fs-6 mb-0" id="modalBenefitBookingsLabel-{{ $b->id }}">
                                                <i class="fa fa-ticket-alt me-2"></i> {{ __('room_benefit.reservation') }} Benefit - {{ $b->order->order_number ?? '#'.$b->order_id }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4" style="background-color: #f8fafc;">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div class="small text-muted">
                                                    {{ __('room_benefit.modal.desc') }}
                                                </div>
                                                <span class="badge bg-primary bg-opacity-10 text-primary">{{ $res->count() }} Booking</span>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover mb-0 align-middle" style="font-size: 0.85rem; background-color: #fff; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden;">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th class="ps-3" style="width: 8%;">{{ __('room_benefit.modal.id') }}</th>
                                                            @if($isAdmin ?? false)
                                                            <th style="width: 25%;">{{ __('room_benefit.modal.customer_name') }}</th>
                                                            @endif
                                                            <th style="width: 32%;">{{ __('room_benefit.modal.time_participants') }}</th>
                                                            <th style="width: 20%;">{{ __('room_benefit.modal.status') }}</th>
                                                            <th class="pe-3" style="width: 15%;">{{ __('room_benefit.modal.action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($res as $booking)
                                                        <tr>
                                                            <td class="ps-3 text-muted fw-bold">#{{ $booking->id }}</td>
                                                            @if($isAdmin ?? false)
                                                            <td>
                                                                <span class="fw-semibold text-dark">{{ $booking->name }}</span>
                                                                @if($booking->user)
                                                                    <br><small class="text-muted" style="font-size: 0.75rem;">{{ $booking->user->email }}</small>
                                                                @endif
                                                            </td>
                                                            @endif
                                                            <td>
                                                                <i class="fa fa-calendar-day text-muted me-1"></i> {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}
                                                                <strong class="text-dark ms-1"><i class="fa fa-clock text-muted me-1"></i> {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</strong>
                                                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;"><i class="fa fa-users me-1"></i> {{ $booking->participants }} Orang</small>
                                                            </td>
                                                            <td>
                                                                @if ($booking->status === 'pending')
                                                                    <span class="badge bg-warning text-dark"><i class="fa fa-hourglass-half me-1"></i> {{ __('room_benefit.modal.status.pending') }}</span>
                                                                @elseif($booking->status === 'approved')
                                                                    <span class="badge bg-success"><i class="fa fa-check-circle me-1"></i> {{ __('room_benefit.modal.status.approved') }}</span>
                                                                @elseif($booking->status === 'rejected')
                                                                    <span class="badge bg-danger"><i class="fa fa-times-circle me-1"></i> {{ __('room_benefit.modal.status.rejected') }}</span>
                                                                @elseif($booking->status === 'checkin')
                                                                    <span class="badge bg-primary"><i class="fa fa-play-circle me-1"></i> {{ __('room_benefit.modal.status.in_use') }}</span>
                                                                @elseif($booking->status === 'paused')
                                                                    <span class="badge bg-secondary"><i class="fa fa-pause-circle me-1"></i> {{ __('room_benefit.modal.status.paused') }}</span>
                                                                @elseif($booking->status === 'selesai')
                                                                    <span class="badge bg-dark"><i class="fa fa-flag-checkered me-1"></i> {{ __('room_benefit.modal.status.finished') }}</span>
                                                                @endif
                                                            </td>
                                                            <td class="pe-3">
                                                                <div class="d-flex gap-1 flex-wrap">
                                                                    <a href="{{ url(($isAdmin ?? false ? 'admin' : 'customer') . '/meeting-room/' . $booking->id . '/detail') }}"
                                                                        class="btn btn-sm btn-info py-1 px-2 text-white" style="font-size:0.75rem;" target="_blank"><i class="fas fa-eye"></i> {{ __('room_benefit.modal.btn.detail') }}</a>

                                                                    @if($isAdmin ?? false)
                                                                        @if ($booking->status === 'pending')
                                                                            <form action="{{ url('admin/meeting-room/' . $booking->id . '/benefit-approve') }}" method="POST" style="display:inline;">
                                                                                @csrf
                                                                                <button class="btn btn-sm btn-success py-1 px-2" style="font-size:0.75rem;" onclick="return confirm('Setujui reservasi ini?')"><i class="fas fa-check"></i> {{ __('room_benefit.modal.btn.approve') }}</button>
                                                                            </form>
                                                                            <form action="{{ url('admin/meeting-room/' . $booking->id . '/benefit-reject') }}" method="POST" style="display:inline;">
                                                                                @csrf
                                                                                <button class="btn btn-sm btn-danger py-1 px-2" style="font-size:0.75rem;" onclick="return confirm('Tolak reservasi ini?')"><i class="fas fa-times"></i> {{ __('room_benefit.modal.btn.reject') }}</button>
                                                                            </form>
                                                                        @elseif($booking->status === 'approved' || $booking->status === 'paused')
                                                                            <form action="{{ url('admin/meeting-room/' . $booking->id . '/checkin') }}" method="POST" style="display:inline;">
                                                                                @csrf
                                                                                <button class="btn btn-sm btn-success py-1 px-2" style="font-size:0.75rem;" onclick="return confirm('{{ __('room_benefit.checkin') }}?')">{{ __('room_benefit.checkin') }}</button>
                                                                            </form>
                                                                        @elseif($booking->status === 'checkin')
                                                                            <form action="{{ url('admin/meeting-room/' . $booking->id . '/checkout') }}" method="POST" style="display:inline;">
                                                                                @csrf
                                                                                <button class="btn btn-sm btn-warning text-dark py-1 px-2" style="font-size:0.75rem;" onclick="return confirm('{{ __('room_benefit.checkout') }}?')">{{ __('room_benefit.checkout') }}</button>
                                                                            </form>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
