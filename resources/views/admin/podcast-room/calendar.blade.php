@extends('layouts-admin.admin')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

<style>
/* ===== Calendar Page Styling ===== */
.cal-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
    flex-wrap: wrap;
    gap: 16px;
}
.cal-page-title {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.3px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.cal-page-subtitle {
    font-size: 0.84rem;
    color: #64748b;
    margin: 4px 0 0 0;
}

/* Navigation Bar */
.cal-nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px 14px 0 0;
    flex-wrap: wrap;
    gap: 12px;
}
.cal-nav-btn {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 0.82rem;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    transition: all 0.15s;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.cal-nav-btn:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
}
.cal-nav-btn.active {
    background: #6366f1;
    color: #ffffff;
    border-color: #6366f1;
}
.cal-nav-range {
    font-size: 1rem;
    font-weight: 700;
    color: #0f172a;
    text-align: center;
}

/* Calendar Grid */
.cal-container {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-top: none;
    border-radius: 0 0 14px 14px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    position: relative;
}
.cal-grid {
    display: grid;
    grid-template-columns: 62px repeat(7, 1fr);
    min-height: 700px;
}

/* Day Headers */
.cal-day-header-row {
    display: grid;
    grid-template-columns: 62px repeat(7, 1fr);
    border-bottom: 2px solid #e2e8f0;
    position: sticky;
    top: 0;
    z-index: 10;
    background: #fafbfc;
}
.cal-day-header-corner {
    padding: 12px 4px;
    text-align: center;
    font-size: 0.7rem;
    color: #94a3b8;
    font-weight: 600;
    border-right: 1px solid #f1f5f9;
}
.cal-day-header {
    padding: 10px 6px;
    text-align: center;
    border-right: 1px solid #f1f5f9;
}
.cal-day-header:last-child { border-right: none; }
.cal-day-name {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #94a3b8;
    margin-bottom: 2px;
}
.cal-day-num {
    font-size: 1.25rem;
    font-weight: 800;
    color: #334155;
    line-height: 1.2;
}
.cal-day-header.is-today .cal-day-num {
    background: #6366f1;
    color: #ffffff;
    border-radius: 50%;
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Time Grid */
.cal-body {
    display: grid;
    grid-template-columns: 62px repeat(7, 1fr);
    position: relative;
}
.cal-time-col {
    border-right: 1px solid #f1f5f9;
}
.cal-time-slot {
    height: 60px;
    padding: 2px 6px 0 0;
    text-align: right;
    font-size: 0.7rem;
    font-weight: 600;
    color: #94a3b8;
    border-bottom: 1px solid #f8fafc;
    position: relative;
}
.cal-day-col {
    position: relative;
    border-right: 1px solid #f1f5f9;
}
.cal-day-col:last-child { border-right: none; }
.cal-hour-line {
    height: 60px;
    border-bottom: 1px solid #f1f5f9;
}

/* Now indicator */
.cal-now-line {
    position: absolute;
    left: 0;
    right: 0;
    height: 2px;
    background: #ef4444;
    z-index: 8;
    pointer-events: none;
}
.cal-now-line::before {
    content: '';
    position: absolute;
    left: -5px;
    top: -4px;
    width: 10px;
    height: 10px;
    background: #ef4444;
    border-radius: 50%;
}

/* Events */
.cal-event {
    position: absolute;
    left: 3px;
    right: 3px;
    border-radius: 6px;
    padding: 4px 7px;
    font-size: 0.72rem;
    line-height: 1.3;
    cursor: pointer;
    overflow: hidden;
    z-index: 5;
    transition: box-shadow 0.15s, transform 0.1s;
    border-left: 3px solid transparent;
}
.cal-event:hover {
    box-shadow: 0 4px 14px rgba(0,0,0,0.15);
    transform: scale(1.02);
    z-index: 9;
}
.cal-event-title {
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.cal-event-time {
    font-weight: 500;
    opacity: 0.85;
    font-size: 0.68rem;
}
.cal-event-status {
    font-size: 0.62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    opacity: 0.9;
    margin-top: 1px;
}

/* Status Colors */
.cal-event.status-pending {
    background: #fffbeb;
    color: #92400e;
    border-left-color: #f59e0b;
}
.cal-event.status-approved {
    background: #eef2ff;
    color: #3730a3;
    border-left-color: #6366f1;
}
.cal-event.status-checkin {
    background: #f0fdf4;
    color: #166534;
    border-left-color: #22c55e;
}
.cal-event.status-selesai {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    border-left: 3px solid #94a3b8;
}

/* Popover */
.cal-popover-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 1050;
}
.cal-popover-overlay.show { display: block; }
.cal-popover {
    position: fixed;
    z-index: 1060;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    padding: 0;
    width: 320px;
    max-width: 90vw;
    animation: calPopIn 0.15s ease-out;
}
@keyframes calPopIn {
    from { opacity: 0; transform: translateY(6px) scale(0.96); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
.cal-popover-header {
    padding: 16px 18px 12px;
    border-bottom: 1px solid #f1f5f9;
}
.cal-popover-title {
    font-size: 1rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 2px 0;
}
.cal-popover-room {
    font-size: 0.78rem;
    color: #64748b;
    font-weight: 500;
}
.cal-popover-body {
    padding: 12px 18px 16px;
}
.cal-popover-row {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 10px;
}
.cal-popover-row:last-child { margin-bottom: 0; }
.cal-popover-icon {
    width: 18px;
    text-align: center;
    color: #94a3b8;
    font-size: 0.82rem;
    flex-shrink: 0;
    margin-top: 2px;
}
.cal-popover-label {
    font-size: 0.72rem;
    color: #94a3b8;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.cal-popover-value {
    font-size: 0.85rem;
    color: #1e293b;
    font-weight: 600;
}
.cal-popover-footer {
    padding: 10px 18px 14px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    gap: 8px;
}
.cal-popover-footer .btn {
    font-size: 0.8rem;
    font-weight: 700;
    padding: 7px 16px;
    border-radius: 8px;
}

/* Status badge in popover */
.cal-status-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 5px;
}
.cal-status-dot.pending  { background: #f59e0b; }
.cal-status-dot.approved { background: #6366f1; }
.cal-status-dot.checkin  { background: #22c55e; }
.cal-status-dot.selesai  { background: #94a3b8; }

/* Loading / Error / Empty */
.cal-overlay-msg {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.85);
    z-index: 15;
    flex-direction: column;
    gap: 8px;
}
.cal-overlay-msg .spinner-border { width: 2rem; height: 2rem; }
.cal-overlay-msg p {
    font-size: 0.88rem;
    font-weight: 600;
    color: #64748b;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .cal-grid, .cal-day-header-row, .cal-body {
        min-width: 700px;
    }
    .cal-container {
        overflow-x: auto;
    }
}
</style>

<div class="container-fluid py-2">
    {{-- Page Header --}}
    <div class="cal-page-header">
        <div>
            <h1 class="cal-page-title">
                <i class="fa-solid fa-calendar-days text-primary"></i> Kalender Reservasi Podcast Room
            </h1>
            <p class="cal-page-subtitle">Visualisasi jadwal penggunaan Studio Podcast dalam tampilan mingguan.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ url('admin/podcast-room') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3 fw-bold" style="font-size:0.85rem;">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Calendar Navigation --}}
    <div class="cal-nav" id="calNav">
        <div class="d-flex gap-2">
            <button class="cal-nav-btn" id="btnPrev" onclick="calNavigate(-1)">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button class="cal-nav-btn active" id="btnToday" onclick="calToday()">
                Hari Ini
            </button>
            <button class="cal-nav-btn" id="btnNext" onclick="calNavigate(1)">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
        <div class="cal-nav-range" id="calRange"></div>
        <div>
            <span class="badge bg-light text-dark border px-3 py-2 fw-bold" style="font-size:0.8rem;">
                <i class="fa-solid fa-calendar-week me-1 text-primary"></i> Week View
            </span>
        </div>
    </div>

    {{-- Calendar Container --}}
    <div class="cal-container" id="calContainer">
        {{-- Day Headers --}}
        <div class="cal-day-header-row" id="calHeaders"></div>
        {{-- Body: time column + 7 day columns --}}
        <div class="cal-body" id="calBody"></div>
        {{-- Loading overlay --}}
        <div class="cal-overlay-msg" id="calLoading" style="display:none;">
            <div class="spinner-border text-primary" role="status"></div>
            <p>Memuat jadwal reservasi...</p>
        </div>
        {{-- Error overlay --}}
        <div class="cal-overlay-msg" id="calError" style="display:none;">
            <i class="fa-solid fa-triangle-exclamation text-danger fs-3"></i>
            <p>Gagal memuat jadwal reservasi.</p>
            <button class="btn btn-sm btn-outline-primary fw-bold" onclick="calFetchEvents()">Coba Lagi</button>
        </div>
    </div>
</div>

{{-- Popover --}}
<div class="cal-popover-overlay" id="popoverOverlay" onclick="calClosePopover()"></div>
<div class="cal-popover" id="popover" style="display:none;"></div>
@endsection

@push('scripts')
<script>
(function() {
    const EVENTS_URL  = "{{ url('admin/podcast-room/calendar-events') }}";
    const HOURS_START = 8;
    const HOURS_END   = 22;
    const HOUR_HEIGHT = 60; // px per hour

    const DAY_NAMES_SHORT = ['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB'];
    const MONTH_NAMES = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

    const STATUS_LABELS = {
        pending:  'Menunggu Approval',
        approved: 'Reserved',
        checkin:  'Sedang Digunakan',
        selesai:  'Selesai (Sudah Checkout)',
    };

    let currentWeekStart = getMonday(new Date());
    let eventsData = [];

    function getMonday(d) {
        const dt = new Date(d);
        const day = dt.getDay();
        const diff = dt.getDate() - day + (day === 0 ? -6 : 1);
        dt.setDate(diff);
        dt.setHours(0, 0, 0, 0);
        return dt;
    }

    function formatDateISO(d) {
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${dd}`;
    }

    function getWeekDays(monday) {
        const days = [];
        for (let i = 0; i < 7; i++) {
            const d = new Date(monday);
            d.setDate(monday.getDate() + i);
            days.push(d);
        }
        return days;
    }

    function isToday(d) {
        const now = new Date();
        return d.getFullYear() === now.getFullYear() &&
               d.getMonth() === now.getMonth() &&
               d.getDate() === now.getDate();
    }

    // ── Render ────────────────────────────────────────────────────────────────

    function renderHeaders(days) {
        const el = document.getElementById('calHeaders');
        let html = '<div class="cal-day-header-corner">WIB</div>';
        days.forEach(d => {
            const today = isToday(d) ? ' is-today' : '';
            html += `<div class="cal-day-header${today}">
                <div class="cal-day-name">${DAY_NAMES_SHORT[d.getDay()]}</div>
                <div class="cal-day-num">${d.getDate()}</div>
            </div>`;
        });
        el.innerHTML = html;
    }

    function renderBody(days) {
        const el = document.getElementById('calBody');
        let html = '';

        // Time column
        html += '<div class="cal-time-col">';
        for (let h = HOURS_START; h <= HOURS_END; h++) {
            html += `<div class="cal-time-slot">${String(h).padStart(2,'0')}:00</div>`;
        }
        html += '</div>';

        // Day columns
        days.forEach((d, idx) => {
            html += `<div class="cal-day-col" data-date="${formatDateISO(d)}" data-col="${idx}">`;
            for (let h = HOURS_START; h <= HOURS_END; h++) {
                html += `<div class="cal-hour-line" data-hour="${h}"></div>`;
            }
            html += '</div>';
        });

        el.innerHTML = html;
    }

    function renderRange(days) {
        const first = days[0];
        const last  = days[6];
        let text = '';
        if (first.getMonth() === last.getMonth()) {
            text = `${first.getDate()} – ${last.getDate()} ${MONTH_NAMES[first.getMonth()]} ${first.getFullYear()}`;
        } else if (first.getFullYear() === last.getFullYear()) {
            text = `${first.getDate()} ${MONTH_NAMES[first.getMonth()]} – ${last.getDate()} ${MONTH_NAMES[last.getMonth()]} ${first.getFullYear()}`;
        } else {
            text = `${first.getDate()} ${MONTH_NAMES[first.getMonth()]} ${first.getFullYear()} – ${last.getDate()} ${MONTH_NAMES[last.getMonth()]} ${last.getFullYear()}`;
        }
        document.getElementById('calRange').textContent = text;
    }

    function renderNowLine() {
        // Remove old
        document.querySelectorAll('.cal-now-line').forEach(e => e.remove());

        const now = new Date();
        const todayStr = formatDateISO(now);
        const col = document.querySelector(`.cal-day-col[data-date="${todayStr}"]`);
        if (!col) return;

        const h = now.getHours();
        const m = now.getMinutes();
        if (h < HOURS_START || h > HOURS_END) return;

        const topPx = (h - HOURS_START) * HOUR_HEIGHT + (m / 60) * HOUR_HEIGHT;
        const line = document.createElement('div');
        line.className = 'cal-now-line';
        line.style.top = topPx + 'px';
        col.appendChild(line);
    }

    function renderEvents() {
        // Clear old events
        document.querySelectorAll('.cal-event').forEach(e => e.remove());

        eventsData.forEach(ev => {
            const col = document.querySelector(`.cal-day-col[data-date="${ev.date}"]`);
            if (!col) return;

            const [sh, sm] = ev.start_time.split(':').map(Number);
            const [eh, em] = ev.end_time.split(':').map(Number);

            const startMin = sh * 60 + sm;
            const endMin   = eh * 60 + em;
            const gridStartMin = HOURS_START * 60;

            const topPx    = ((startMin - gridStartMin) / 60) * HOUR_HEIGHT;
            const heightPx = Math.max(((endMin - startMin) / 60) * HOUR_HEIGHT, 24);

            const statusClass = 'status-' + (ev.status || 'approved');
            const statusLabel = STATUS_LABELS[ev.status] || ev.status;
            const showStatus  = heightPx >= 48;

            const block = document.createElement('div');
            block.className = `cal-event ${statusClass}`;
            block.style.top    = topPx + 'px';
            block.style.height = heightPx + 'px';
            block.innerHTML = `
                <div class="cal-event-title">${escHtml(ev.title)}</div>
                <div class="cal-event-time">${ev.start_time} – ${ev.end_time}</div>
                ${showStatus ? `<div class="cal-event-status">${statusLabel}</div>` : ''}
            `;

            block.addEventListener('click', function(e) {
                e.stopPropagation();
                calShowPopover(ev, this);
            });

            col.appendChild(block);
        });
    }

    // ── Data Fetch ────────────────────────────────────────────────────────────

    function calFetchEvents() {
        const days = getWeekDays(currentWeekStart);
        const start = formatDateISO(days[0]);
        const end   = formatDateISO(days[6]);

        document.getElementById('calLoading').style.display = 'flex';
        document.getElementById('calError').style.display = 'none';

        fetch(`${EVENTS_URL}?start=${start}&end=${end}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => {
            if (!r.ok) throw new Error('Network error');
            return r.json();
        })
        .then(data => {
            eventsData = data;
            document.getElementById('calLoading').style.display = 'none';
            renderEvents();
        })
        .catch(() => {
            document.getElementById('calLoading').style.display = 'none';
            document.getElementById('calError').style.display = 'flex';
        });
    }

    // ── Navigation ────────────────────────────────────────────────────────────

    window.calNavigate = function(dir) {
        const d = new Date(currentWeekStart);
        d.setDate(d.getDate() + dir * 7);
        currentWeekStart = d;
        calRender();
    };

    window.calToday = function() {
        currentWeekStart = getMonday(new Date());
        calRender();
    };

    // ── Popover ───────────────────────────────────────────────────────────────

    window.calShowPopover = function(ev, targetEl) {
        const popover  = document.getElementById('popover');
        const overlay  = document.getElementById('popoverOverlay');
        const statusLabel = STATUS_LABELS[ev.status] || ev.status;
        const statusDotClass = ev.status || 'approved';

        popover.innerHTML = `
            <div class="cal-popover-header">
                <div class="cal-popover-title">${escHtml(ev.title)}</div>
                <div class="cal-popover-room"><i class="fa-solid fa-microphone-lines me-1"></i>${escHtml(ev.room_name)}</div>
            </div>
            <div class="cal-popover-body">
                <div class="cal-popover-row">
                    <div class="cal-popover-icon"><i class="fa-solid fa-calendar-day"></i></div>
                    <div>
                        <div class="cal-popover-label">Tanggal</div>
                        <div class="cal-popover-value">${formatDisplayDate(ev.date)}</div>
                    </div>
                </div>
                <div class="cal-popover-row">
                    <div class="cal-popover-icon"><i class="fa-solid fa-clock"></i></div>
                    <div>
                        <div class="cal-popover-label">Waktu</div>
                        <div class="cal-popover-value">${ev.start_time} – ${ev.end_time} WIB</div>
                    </div>
                </div>
                <div class="cal-popover-row">
                    <div class="cal-popover-icon"><i class="fa-solid fa-hashtag"></i></div>
                    <div>
                        <div class="cal-popover-label">Order</div>
                        <div class="cal-popover-value">${escHtml(ev.order_number || '-')}</div>
                    </div>
                </div>
                <div class="cal-popover-row">
                    <div class="cal-popover-icon"><i class="fa-solid fa-circle-info"></i></div>
                    <div>
                        <div class="cal-popover-label">Status</div>
                        <div class="cal-popover-value"><span class="cal-status-dot ${statusDotClass}"></span>${statusLabel}</div>
                    </div>
                </div>
            </div>
            <div class="cal-popover-footer">
                <a href="${ev.detail_url}" class="btn btn-primary btn-sm flex-fill"><i class="fa-solid fa-eye me-1"></i> Lihat Detail</a>
                <button class="btn btn-outline-secondary btn-sm" onclick="calClosePopover()"><i class="fa-solid fa-xmark"></i></button>
            </div>
        `;

        // Position
        const rect = targetEl.getBoundingClientRect();
        let top  = rect.top + window.scrollY;
        let left = rect.right + 10;

        if (left + 330 > window.innerWidth) {
            left = rect.left - 330;
            if (left < 10) left = 10;
        }
        if (top + 350 > window.innerHeight + window.scrollY) {
            top = Math.max(10, window.innerHeight + window.scrollY - 370);
        }

        popover.style.top  = top + 'px';
        popover.style.left = left + 'px';
        popover.style.display = 'block';
        overlay.classList.add('show');
    };

    window.calClosePopover = function() {
        document.getElementById('popover').style.display = 'none';
        document.getElementById('popoverOverlay').classList.remove('show');
    };

    // ── Helpers ────────────────────────────────────────────────────────────────

    function escHtml(str) {
        if (!str) return '';
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    function formatDisplayDate(dateStr) {
        const d = new Date(dateStr + 'T00:00:00');
        return `${d.getDate()} ${MONTH_NAMES[d.getMonth()]} ${d.getFullYear()}`;
    }

    // ── Init ──────────────────────────────────────────────────────────────────

    function calRender() {
        const days = getWeekDays(currentWeekStart);
        renderHeaders(days);
        renderBody(days);
        renderRange(days);
        renderNowLine();
        calFetchEvents();
    }

    calRender();

    // Update now line every minute
    setInterval(renderNowLine, 60000);
})();
</script>
@endpush
