@extends('layouts.app_template')
@section('content')
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="">
            <h2 class="fw-bold mb-0">Kegiatan</h2>
            <div class="mt-2 d-flex justify-content-end px-lg-4">
                <div id="date-view" class="me-3 bg-dark-lt border-dashed badge">
                    <div class="placeholder" style="width: 150px;"></div>
                </div>
                <div>
                    <span class="badge bg-primary-lt border-dashed cursor-pointer" onclick="createData()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="mt-3 px-lg-4">
                <div id="event-list-content">
                    <div class="card rounded-10 shadow-none border-dashed">
                        <div class="card-body">
                            Tidak ada kegiatan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div id="simple-calendar" class="calendar-container border-0"></div>
    </div>
</div>
@endsection
@section('modal')
<div class="modal modal-blur fade" id="modal-event" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Kegiatan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="text" class="form-control" name="date" readonly required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Kegiatan</label>
                    <textarea class="form-control" rows="3" name="deskripsi" placeholder="..."></textarea>
                </div>
                <div>
                    <label class="form-label">Warna</label>
                    <select name="color" id="color" class="form-control">
                        <option value="success">Hijau</option>
                        <option value="primary">Biru</option>
                        <option value="danger">Merah</option>
                        <option value="warning">Oren</option>
                        <option value="dark">Hitam</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                    Batal
                </a>
                <a href="#" class="btn btn-primary ms-auto" onclick="saveEvent()">
                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Simpan
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Confirm delete -->
<div class="modal modal-blur fade" id="modal-confirm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <span class="h2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <line x1="4" y1="7" x2="20" y2="7"></line>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                    </svg>
                </span>
                <h3>Hapus Event ?</h3>
                <div class="text-muted">Apakah yakin ingin menghapus event ini ?</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <a href="#" class="btn w-100" data-bs-dismiss="modal">
                                Batal
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" class="btn btn-danger w-100" onclick="saveEvent()">
                                Hapus
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    let _events = <?= json_encode($event) ?>;
    const _dateView = '#date-view';
    const _contentList = '#event-list-content';
    const calendar = '#simple-calendar';
    const _option = {
        showUrl: '/api/event/show',
        storeUrl: '/api/event/store',
        deleteUrl: '/api/event/delete'
    };
    let $calendar = '';
    let _currentDate = '';

    $(document).ready(function() {
        getCurrentEvent();
        eventInit();
    });

    // Get first data
    const getCurrentEvent = () => {
        let dates = new Date();
        let dateFormat = setDateFormat(dates);
        getData(dateFormat, function(value) {
            dateView({
                date: dateFormat
            });
            drawEventList(value);
        });
    }

    // Initialize first date calendar
    const eventInit = () => {
        let container = $(calendar).simpleCalendar({
            fixedStartDay: 0, // begin weeks by sunday
            disableEmptyDetails: false,
            displayEvent: true,
            events: [],
            onDateSelect: function(date, events) {
                let dates = new Date(date);
                let dateFormat = setDateFormat(dates);
                getData(dateFormat, function(value) {
                    dateView({
                        date: dateFormat
                    });
                    drawEventList(value);
                });
            }
        });
        $calendar = container.data('plugin_simpleCalendar');
        setCalendarEvent();
    }

    // Set calendar dot event
    const setCalendarEvent = () => {
        let events = [];
        $.each(_events, function(i, val) {
            events.push({
                startDate: val.tanggal,
                endDate: val.tanggal,
                summary: val.event
            });
        });
        $calendar.setEvents(events);
    }

    // get current content
    const getData = async (date, callback) => {
        requestServer({
            url: url + _option.showUrl,
            data: {
                tanggal: date,
            },
            onLoader: true,
            onSuccess: function(value) {
                close_swal(false);
                callback(value.data);
            },
        });
    }

    // Draw list event
    const drawEventList = (data) => {
        let view = data.length == 0 ? contentEmpty() : '';
        $.each(data, function(i, val) {
            view += contentView({
                id: val.id,
                title: val.event,
                color: val.color,
            });
        });
        $(_contentList).html(view);
    }

    // Date view
    const dateView = ({
        date = ''
    }) => {
        _currentDate = date;
        $(_dateView).html(date);
    }

    // Create view empty
    const contentEmpty = () => {
        let view = '<div class="card rounded-10 shadow-none border-dashed">' +
            '<div class="card-body">' +
            'Tidak ada kegiatan' +
            '</div>' +
            '</div>';
        return view;
    }

    // Create view item
    const contentView = ({
        id = '',
        title = '',
        color = '',
    }) => {
        let view = '<div class="card rounded-10 mb-1 shadow-none border-dashed">' +
            '<div class="card-body d-flex justify-content-between">' +
            '<div class="d-flex align-items-center">' +
            '<span class="status-dot status-dot-animated status-' + color + ' me-3"></span>' +
            title +
            '</div>' +
            '<div>' +
            '<span class="cursor-pointer" onclick="deleteData(\'' + id + '\')">' +
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
            '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>' +
            '<line x1="4" y1="7" x2="20" y2="7"></line>' +
            '<line x1="10" y1="11" x2="10" y2="17"></line>' +
            '<line x1="14" y1="11" x2="14" y2="17"></line>' +
            '<path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>' +
            '<path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>' +
            '</svg>' +
            '</span>' +
            '</div>' +
            '</div>' +
            '</div>';

        return view;
    }

    // Event action
    // Modal delete
    const _modalDeleteConfirm = '#modal-confirm';
    // Modal Event
    const _modal_event = '#modal-event';
    const _modal_event_date = _modal_event + ' input[name="date"]';
    const _modal_event_deskripsi = _modal_event + ' textarea[name="deskripsi"]';
    const _modal_event_color = _modal_event + ' select[name="color"]';
    let _draf_id = '';
    let _drafOptionRequest = {};

    const openModal = (modal) => {
        $(modal).modal("show");
    }

    const closeModal = (modal) => {
        $(modal).modal("hide");
    }

    const getModalData = () => {
        let tanggal = $(_modal_event_date).val();
        let event = $(_modal_event_deskripsi).val();
        let color = $(_modal_event_color).val();

        let data = {
            tanggal: tanggal,
            event: event,
            color: color,
        };

        if (_drafOptionRequest.action == 'delete') {
            data = {
                id: _draf_id,
            };
        }

        return data;
    }

    const validateEvent = () => {
        let data = getModalData();
        let validate = true;

        if (_drafOptionRequest.action != 'delete') {
            if (data.tanggal == '') {
                validate = false;
                notif('Tanggal harus diisi', 'error');
            }

            if (data.event == '') {
                validate = false;
                notif('Nama Event harus diisi', 'error');
            }
        }

        return validate ? data : validate;
    }

    const setDrafOptionRequest = ({
        url,
        action
    }) => {
        _drafOptionRequest = {
            url: url,
            action: action
        };
    }

    const createData = () => {
        setDrafOptionRequest({
            url: _option.storeUrl,
            action: 'store',
        });
        openModal(_modal_event);
        $(_modal_event_date).val(_currentDate);
        $(_modal_event_deskripsi).val('');
    }

    const deleteData = (id) => {
        _draf_id = id;
        setDrafOptionRequest({
            url: _option.deleteUrl,
            action: 'delete',
        });
        openModal(_modalDeleteConfirm);
    }

    const saveEvent = () => {
        // Initialize
        let requestUrl = _drafOptionRequest.url;
        let action = _drafOptionRequest.action;
        let data = validateEvent();
        // Send request
        if (data != false) {
            requestServer({
                url: url + requestUrl,
                data: data,
                onLoader: true,
                onSuccess: function(value) {
                    close_swal(true, 'Berhasil ' + action + ' event', 'success');
                    closeModal();
                    reloadPage();
                },
            });
        }
    }
</script>
@endpush