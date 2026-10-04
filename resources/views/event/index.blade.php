@extends('layouts.app_template')

@section('content')
<style>
    .event-calendar { min-width: 0; }
    .event-calendar .calendar { padding: 16px; color: #182433; text-transform: none; }
    .event-calendar .calendar header { position: relative; min-height: 40px; padding: 0 44px; display: flex; justify-content: center; align-items: center; }
    .event-calendar .calendar header .month { font-size: 16px; line-height: 22px; font-weight: 600; }
    .event-calendar .calendar header .month .year { display: inline; font-size: 14px; font-weight: 400; color: #667382; }
    .event-calendar .calendar header .simple-calendar-btn { width: 36px; height: 36px; top: 2px; border: 1px solid #dce1e7; border-radius: 6px; color: #667382; background: #fff; }
    .event-calendar .calendar header .simple-calendar-btn:before { top: 12px; left: 12px; width: 7px; height: 7px; border-width: 2px 2px 0 0; }
    .event-calendar .calendar header .simple-calendar-btn:hover { background: #f1f4f8; color: #182433; }
    .event-calendar .calendar table { table-layout: fixed; margin: 16px 0 0; border-collapse: collapse; }
    .event-calendar .calendar thead { font-size: 11px; font-weight: 600; color: #667382; }
    .event-calendar .calendar thead td { padding: 10px 0; background: #f6f8fb; border-bottom: 1px solid #e6eaf0; }
    .event-calendar .calendar tbody td { height: 52px; padding: 4px 0; border-bottom: 1px solid #f0f2f5; }
    .event-calendar .calendar .day { display: inline-flex; align-items: center; justify-content: center; width: 100%; max-width: 40px; height: 40px; line-height: normal; border: 1px solid transparent; border-radius: 6px; font-size: 13px; font-weight: 500; }
    .event-calendar .calendar .day:hover { border: 1px solid #dce1e7; background: #f1f4f8; }
    .event-calendar .calendar .day.today { background: #eaf2fc; color: #206bc4; font-weight: 700; }
    .event-calendar .calendar .day.wrong-month { color: #a7afb9; }
    .event-calendar .calendar .day.is-selected { background: #206bc4; color: #fff; border-color: #206bc4; }
    .event-calendar .calendar .day:focus-visible,
    .event-calendar .calendar .simple-calendar-btn:focus-visible { outline: 2px solid #206bc4; outline-offset: 2px; }
    .event-calendar .calendar .day.has-event::after { display: none; }
    .event-calendar .calendar .event-dots {
        position: absolute;
        left: 50%;
        bottom: 3px;
        display: flex;
        gap: 2px;
        transform: translateX(-50%);
    }
    .event-calendar .calendar .event-dot { width: 5px; height: 5px; border-radius: 50%; }
    .event-calendar .calendar .day.is-selected .event-dot { box-shadow: 0 0 0 1px #fff; }
    .event-color-option { min-width: 6.5rem; }
    .event-name { min-width: 0; overflow-wrap: anywhere; }
    .event-actions { display: flex; gap: 6px; flex-shrink: 0; }
    .event-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        padding: 0;
        border: 1px solid #cbd3dd;
        border-radius: 6px;
        background: #f1f4f8;
        color: #526173;
        cursor: pointer;
        transition: background .15s, color .15s;
    }
    .event-action .icon { width: 18px; height: 18px; margin: 0; }
    .event-action:hover { background: #eaf2fc; border-color: #8eb6e5; color: #206bc4; }
    .event-action-delete { background: #fff1f0; border-color: #e9b8b5; color: #c03935; }
    .event-action-delete:hover { background: #fce0de; border-color: #d58d88; color: #a52925; }
    .event-action:focus-visible { outline: 2px solid currentColor; outline-offset: 2px; }
    .event-add {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 36px;
        padding: 8px 14px;
        border: 1px solid #b9cee8;
        border-radius: 6px;
        background: #f0f6fd;
        color: #206bc4;
        font-size: 13px;
        font-weight: 600;
        line-height: 20px;
        cursor: pointer;
    }
    .event-add .icon { width: 18px; height: 18px; margin: 0; flex-shrink: 0; }
    .event-add:hover { background: #e1edfc; border-color: #8eb6e5; }
    .event-add:focus-visible { outline: 2px solid #206bc4; outline-offset: 2px; }
    .event-add-primary { background: #206bc4; border-color: #206bc4; color: #fff; }
    .event-add-primary:hover { background: #1a5ba8; border-color: #1a5ba8; }
</style>

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
    <div>
        <h2 class="page-title mb-1">Kegiatan</h2>
        <div class="text-muted">Kelola agenda dan jadwal kegiatan satuan</div>
    </div>
    <button type="button" class="event-add event-add-primary d-print-none" id="create-event">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5v14"/><path d="M5 12h14"/></svg>
        Tambah Kegiatan
    </button>
</div>

<div class="row row-deck row-cards mb-3">
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body py-3">
                <div class="text-muted small">Total Kegiatan</div>
                <div class="h2 mb-0" id="total-events">{{ number_format($summary['total']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body py-3">
                <div class="text-muted small">Bulan Ini</div>
                <div class="h2 text-blue mb-0" id="month-events">{{ number_format($summary['this_month']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body py-3">
                <div class="text-muted small">Akan Datang</div>
                <div class="h2 text-green mb-0" id="upcoming-events">{{ number_format($summary['upcoming']) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div>
                    <h3 class="card-title" id="selected-date-title">Agenda Hari Ini</h3>
                    <div class="text-muted small"><span id="selected-event-count">0</span> kegiatan pada tanggal terpilih</div>
                </div>
                <button type="button" class="event-add ms-auto d-print-none" id="create-event-selected">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                    Tambah
                </button>
            </div>
            <div class="list-group list-group-flush" id="event-list">
                <div class="list-group-item text-center text-muted py-5">Memuat agenda...</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card event-calendar">
            <div class="card-header d-flex align-items-center justify-content-between gap-2">
                <div>
                    <h3 class="card-title">Kalender</h3>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="calendar-today">Hari ini</button>
            </div>
            <div class="card-body p-0">
                <div id="simple-calendar" class="calendar-container border-0"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal modal-blur fade" id="modal-event" tabindex="-1" aria-labelledby="modal-event-title" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form id="event-form">
                <div class="modal-header">
                    <h3 class="modal-title" id="modal-event-title">Tambah Kegiatan</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="event-date">Tanggal</label>
                        <input type="date" class="form-control" id="event-date" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="event-name">Nama Kegiatan</label>
                        <textarea class="form-control" id="event-name" name="event" rows="3" maxlength="255" placeholder="Masukkan nama atau agenda kegiatan" required></textarea>
                    </div>
                    <div>
                        <label class="form-label">Warna Penanda</label>
                        <div class="form-selectgroup form-selectgroup-boxes d-flex flex-wrap gap-2">
                            @foreach([
                                'success' => ['Hijau', 'bg-green'],
                                'primary' => ['Biru', 'bg-blue'],
                                'danger' => ['Merah', 'bg-red'],
                                'warning' => ['Oranye', 'bg-yellow'],
                                'dark' => ['Hitam', 'bg-dark'],
                            ] as $value => [$label, $class])
                            <label class="form-selectgroup-item event-color-option">
                                <input type="radio" name="color" value="{{ $value }}" class="form-selectgroup-input" {{ $value === 'primary' ? 'checked' : '' }}>
                                <span class="form-selectgroup-label d-flex align-items-center">
                                    <span class="status-dot {{ $class }} me-2"></span>{{ $label }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="invalid-feedback d-block mt-3" id="event-error"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto" id="save-event">Simpan Kegiatan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let events = @json($event);
    let selectedDate = formatLocalDate(new Date());
    let editingId = null;
    const allowedColors = ['success', 'primary', 'danger', 'warning', 'dark'];
    const colorClasses = {
        success: 'bg-green', primary: 'bg-blue', danger: 'bg-red', warning: 'bg-yellow', dark: 'bg-dark'
    };
    const eventList = document.getElementById('event-list');
    const eventForm = document.getElementById('event-form');
    const eventModal = new bootstrap.Modal(document.getElementById('modal-event'));
    const storeUrl = @json(route('event.store'));
    const updateUrl = @json(route('event.update', ['event' => '__ID__']));
    const deleteUrl = @json(route('event.destroy', ['event' => '__ID__']));

    function formatLocalDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function parseLocalDate(value) {
        const [year, month, day] = value.split('-').map(Number);
        return new Date(year, month - 1, day);
    }

    function formatDisplayDate(value) {
        return new Intl.DateTimeFormat('id-ID', {
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
        }).format(parseLocalDate(value));
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function normalizedDate(event) {
        return String(event.tanggal).substring(0, 10);
    }

    function refreshSummary() {
        const today = formatLocalDate(new Date());
        const currentMonth = today.substring(0, 7);
        document.getElementById('total-events').textContent = events.length;
        document.getElementById('month-events').textContent = events.filter(item => normalizedDate(item).startsWith(currentMonth)).length;
        document.getElementById('upcoming-events').textContent = events.filter(item => normalizedDate(item) >= today).length;
    }

    function drawSelectedDate() {
        const selectedEvents = events
            .filter(item => normalizedDate(item) === selectedDate)
            .sort((left, right) => Number(left.id) - Number(right.id));
        document.getElementById('selected-date-title').textContent = formatDisplayDate(selectedDate);
        document.getElementById('selected-event-count').textContent = selectedEvents.length;

        eventList.innerHTML = selectedEvents.length
            ? selectedEvents.map(item => {
                const color = allowedColors.includes(item.color) ? item.color : 'primary';
                return `<div class="list-group-item">
                    <div class="d-flex align-items-center gap-3">
                        <span class="status-dot status-dot-animated ${colorClasses[color]}"></span>
                        <div class="flex-fill fw-medium event-name">${escapeHtml(item.event)}</div>
                        <div class="event-actions d-print-none">
                            <button type="button" class="event-action" data-action="edit" data-id="${item.id}" title="Edit kegiatan" aria-label="Edit kegiatan">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3l-11 11l-4 1l1 -4z"/></svg>
                            </button>
                            <button type="button" class="event-action event-action-delete" data-action="delete" data-id="${item.id}" title="Hapus kegiatan" aria-label="Hapus kegiatan">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7h16"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3h6v3"/></svg>
                            </button>
                        </div>
                    </div>
                </div>`;
            }).join('')
            : `<div class="list-group-item text-center py-5">
                <div class="text-muted mb-3">Belum ada kegiatan pada tanggal ini.</div>
                <button type="button" class="event-add" data-action="create">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah kegiatan
                </button>
            </div>`;
    }

    function calendarEvents() {
        return events.map(item => ({
            startDate: normalizedDate(item),
            endDate: normalizedDate(item),
            summary: item.event,
            color: allowedColors.includes(item.color) ? item.color : 'primary'
        }));
    }

    function drawCalendarDots(dayElement) {
        const dayEvents = dayElement.data('todayEvents') || [];
        if (!dayEvents.length) return;

        const dots = $('<span class="event-dots" aria-hidden="true"></span>');
        dayEvents.slice(0, 4).forEach(item => {
            const color = allowedColors.includes(item.color) ? item.color : 'primary';
            dots.append(`<span class="event-dot ${colorClasses[color]}"></span>`);
        });
        dayElement.append(dots);
    }

    const calendarContainer = $('#simple-calendar').simpleCalendar({
        months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        days: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
        fixedStartDay: 1,
        disableEmptyDetails: false,
        disableEventDetails: false,
        displayEvent: true,
        events: calendarEvents(),
        onDateSelect: function (date) {
            selectedDate = formatLocalDate(new Date(date));
            markSelectedDate();
            drawSelectedDate();
        },
        onMonthChange: function () {
            window.setTimeout(markSelectedDate, 0);
        },
        onDayCreate: function (dayElement) {
            drawCalendarDots(dayElement);
            dayElement.attr({ role: 'button', tabindex: '0', 'aria-label': formatDisplayDate(formatLocalDate(new Date(dayElement.data('date')))) });
        }
    });
    const calendar = calendarContainer.data('plugin_simpleCalendar');

    document.getElementById('calendar-today').addEventListener('click', () => {
        const today = new Date();
        selectedDate = formatLocalDate(today);
        calendar.changeMonth((today.getFullYear() - calendar.currentDate.getFullYear()) * 12 + today.getMonth() - calendar.currentDate.getMonth());
        markSelectedDate();
        drawSelectedDate();
    });

    calendarContainer.on('keydown', '.day', function (event) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            this.click();
        }
    });

    function markSelectedDate() {
        document.querySelectorAll('#simple-calendar .day').forEach(day => {
            const dayDate = formatLocalDate(new Date(day.dataset.date));
            day.classList.toggle('is-selected', dayDate === selectedDate);
            day.setAttribute('aria-pressed', dayDate === selectedDate ? 'true' : 'false');
        });
    }

    function refreshPageData() {
        calendar.setEvents(calendarEvents());
        markSelectedDate();
        refreshSummary();
        drawSelectedDate();
    }

    function openCreateModal() {
        editingId = null;
        eventForm.reset();
        document.getElementById('modal-event-title').textContent = 'Tambah Kegiatan';
        document.getElementById('save-event').textContent = 'Simpan Kegiatan';
        document.getElementById('event-date').value = selectedDate;
        document.querySelector('input[name="color"][value="primary"]').checked = true;
        document.getElementById('event-error').textContent = '';
        eventModal.show();
    }

    function openEditModal(id) {
        const item = events.find(event => String(event.id) === String(id));
        if (!item) return;
        editingId = item.id;
        document.getElementById('modal-event-title').textContent = 'Edit Kegiatan';
        document.getElementById('save-event').textContent = 'Simpan Perubahan';
        document.getElementById('event-date').value = normalizedDate(item);
        document.getElementById('event-name').value = item.event;
        const color = allowedColors.includes(item.color) ? item.color : 'primary';
        document.querySelector(`input[name="color"][value="${color}"]`).checked = true;
        document.getElementById('event-error').textContent = '';
        eventModal.show();
    }

    async function requestJson(requestUrl, options) {
        const response = await fetch(requestUrl, {
            ...options,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                ...(options.headers || {})
            }
        });
        const result = await response.json();
        if (!response.ok) {
            const validationMessage = result.errors ? Object.values(result.errors).flat()[0] : null;
            throw new Error(validationMessage || result.message || 'Permintaan tidak dapat diproses.');
        }
        return result;
    }

    eventForm.addEventListener('submit', async function (event) {
        event.preventDefault();
        const saveButton = document.getElementById('save-event');
        const errorElement = document.getElementById('event-error');
        const payload = Object.fromEntries(new FormData(eventForm).entries());
        saveButton.disabled = true;
        errorElement.textContent = '';

        try {
            const result = await requestJson(
                editingId ? updateUrl.replace('__ID__', editingId) : storeUrl,
                { method: editingId ? 'PATCH' : 'POST', body: JSON.stringify(payload) }
            );
            if (editingId) {
                events = events.map(item => String(item.id) === String(editingId) ? result.data : item);
            } else {
                events.push(result.data);
            }
            selectedDate = normalizedDate(result.data);
            eventModal.hide();
            refreshPageData();
            notif(result.message, 'success');
        } catch (error) {
            errorElement.textContent = error.message;
        } finally {
            saveButton.disabled = false;
        }
    });

    eventList.addEventListener('click', async function (event) {
        const actionButton = event.target.closest('[data-action]');
        if (!actionButton) return;
        if (actionButton.dataset.action === 'create') openCreateModal();
        if (actionButton.dataset.action === 'edit') openEditModal(actionButton.dataset.id);
        if (actionButton.dataset.action === 'delete') {
            const item = events.find(current => String(current.id) === String(actionButton.dataset.id));
            if (!item || actionButton.disabled) return;
            if (!await confirmDelete('Hapus kegiatan "' + item.event + '"?')) return;
            actionButton.disabled = true;
            try {
                const result = await requestJson(deleteUrl.replace('__ID__', item.id), { method: 'DELETE' });
                events = events.filter(current => String(current.id) !== String(item.id));
                refreshPageData();
                notif(result.message, 'success');
            } catch (error) {
                notif(error.message, 'error');
            } finally {
                actionButton.disabled = false;
            }
        }
    });

    document.getElementById('create-event').addEventListener('click', openCreateModal);
    document.getElementById('create-event-selected').addEventListener('click', openCreateModal);
    refreshSummary();
    drawSelectedDate();
    markSelectedDate();
});
</script>
@endpush
