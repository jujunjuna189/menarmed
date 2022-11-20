@extends('layouts.app_template')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <ul class="nav nav-tabs" data-bs-toggle="tabs">
                <li class="nav-item">
                    <a href="#tabs-armed" class="nav-link fw-bold active" data-bs-toggle="tab" onclick="switchTab(1)">
                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <circle cx="12" cy="7" r="4" />
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                        </svg>
                        Armed
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#tabs-kostrad" class="nav-link fw-bold" data-bs-toggle="tab" onclick="switchTab(2)">
                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <circle cx="12" cy="7" r="4" />
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                        </svg>
                        Kostrad
                    </a>
                </li>
            </ul>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active show" id="tabs-armed">
                        <div class="text-end">
                            <a href="#" class="badge bg-primary-lt py-2 border-dashed text-decoration-none" onclick="createPejabat()">Tambah Armed</a>
                        </div>
                        <div class="my-4 px-3" id="content">
                            <?php for ($i = 0; $i < 5; $i++) : ?>
                                <div class="d-flex align-items-center my-3">
                                    <div class="rounded-circle border-dashed d-flex justify-content-center align-items-center bg-teal-lt" style="width: 50px; height: 50px">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                        </svg>
                                    </div>
                                    <div class="ms-3 placeholder-glow d-inline">
                                        <div>
                                            <div class="placeholder" style="width: 150px;"></div>
                                        </div>
                                        <div>
                                            <div class="placeholder" style="width: 100px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-0">
                            <?php endfor ?>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabs-kostrad">
                        <div class="text-end">
                            <a href="#" class="badge bg-primary-lt py-2 border-dashed text-decoration-none" onclick="createPejabat()">Tambah Kostrad</a>
                        </div>
                        <div class="my-4 px-3" id="content">
                            <?php for ($i = 0; $i < 5; $i++) : ?>
                                <div class="d-flex align-items-center my-3">
                                    <div class="rounded-circle border-dashed d-flex justify-content-center align-items-center bg-teal-lt" style="width: 50px; height: 50px">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                        </svg>
                                    </div>
                                    <div class="ms-3 placeholder-glow d-inline">
                                        <div>
                                            <div class="placeholder" style="width: 150px;"></div>
                                        </div>
                                        <div>
                                            <div class="placeholder" style="width: 100px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-0">
                            <?php endfor ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
<div class="modal modal-blur fade" id="modal-pejabat" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pejabat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" required>
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control" name="nama" required placeholder="...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Pangkat</label>
                    <input type="text" class="form-control" name="pangkat" required placeholder="...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nrp</label>
                    <input type="text" class="form-control" name="nrp" required placeholder="...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Jabatan</label>
                    <input type="text" class="form-control" name="jabatan" required placeholder="...">
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
                <h3>Hapus Data ?</h3>
                <div class="text-muted">Apakah yakin ingin menghapus data ini ?</div>
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
    const _tabs_armed = '#tabs-armed';
    const _tabs_kostrad = '#tabs-kostrad';
    // Tab default
    let _currentTab = 1;
    // Set data pejabat untuk kebutuhan saat update dan isi form pejabat
    let _dataPejabat = {};
    // Option
    let _drafOption = {};
    let _option = [{
            tab: 1,
            pejabat: 'armed',
            tabContent: _tabs_armed + ' #content',
            storeUrl: '/api/armed/store',
            updateUrl: '/api/armed/update',
            deleteUrl: '/api/armed/delete'
        },
        {
            tab: 2,
            pejabat: 'kostrad',
            tabContent: _tabs_kostrad + ' #content',
            storeUrl: '/api/kostrad/store',
            updateUrl: '/api/kostrad/update',
            deleteUrl: '/api/kostrad/delete',
        },
    ];

    $(document).ready(function() {
        switchTab(1); // set value 1 with default
    });

    // Setting tab swith
    const switchTab = (tab) => {
        // Initialize
        _currentTab = tab;
        _drafOption = _option.find((x) => x.tab == tab);
        let pejabat = _drafOption.pejabat; // armed or kostrad
        // Get data
        getData(pejabat, function(value) {
            _dataPejabat = {
                pejabat: pejabat,
                data: value,
            };
            drawContent();
        });
    }
    // Memulau content
    const getData = async (jabatan, callback) => {
        requestServer({
            url: url + '/api/' + jabatan + '/show',
            onLoader: true,
            onSuccess: function(value) {
                close_swal(false);
                callback(value.data);
            },
        });
    }

    // Looping data ke content
    const drawContent = () => {
        // initialize
        let dataPejabat = _dataPejabat.data;
        let tabContent = _drafOption.tabContent;
        let view = '';
        // Loop
        $.each(dataPejabat, function(i, val) {
            view += contentView({
                id: val.id,
                nama: val.nama,
                jabatan: val.jabatan,
                lates: val.lates,
            });
        });
        // Set view
        $(tabContent).html(view);
    }

    // Render html content
    const contentView = ({
        id = '',
        nama = '',
        jabatan = '',
        lates = ''
    }) => {
        let view = '<div class="d-flex align-items-center my-3">' +
            '<div class="rounded-circle border-dashed d-flex justify-content-center align-items-center bg-teal-lt" style="width: 50px; height: 50px">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
            '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>' +
            '<circle cx="12" cy="7" r="4"></circle>' +
            '<path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>' +
            '</svg>' +
            '</div>' +
            '<div class="ms-3">' +
            '<h3 class="fw-bold mb-0">' + nama + '</h3>' +
            '<small>' + jabatan + '</small>' +
            updateComponent(id) +
            '</div>' +
            '<div class="ms-auto">' +
            '<span class="badge bg-success-lt">' + (lates ?? '') + '</span>' +
            '</div>' +
            '</div>' +
            '<hr class="my-0">';

        return view;
    }

    // Component button update
    // id pejabat integer required
    const updateComponent = (id) => {
        let view = '<div class="mt-1">' +
            '<span class="badge bg-yellow-lt border-dashed cursor-pointer" onclick="updatePejabat(\'' + id + '\')">' +
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
            '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>' +
            '<path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>' +
            '<path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>' +
            '<path d="M16 5l3 3"></path>' +
            '</svg>' +
            '</span>' +
            '<span class="ms-2 badge bg-red-lt border-dashed cursor-pointer" onclick="deletePejabat(\'' + id + '\')">' +
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
            '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>' +
            '<line x1="4" y1="7" x2="20" y2="7"></line>' +
            '<line x1="10" y1="11" x2="10" y2="17"></line>' +
            '<line x1="14" y1="11" x2="14" y2="17"></line>' +
            '<path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>' +
            '<path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>' +
            '</svg>' +
            '</span>' +
            '</div>';

        return view;
    }

    // Modal delete
    const _modalDeleteConfirm = '#modal-confirm';
    // Pejabat create
    const _modalPejabat = '#modal-pejabat';
    const _inputPejabatId = _modalPejabat + ' input[name="id"]';
    const _inputPejabatNama = _modalPejabat + ' input[name="nama"]';
    const _inputPejabatPangkat = _modalPejabat + ' input[name="pangkat"]';
    const _inputPejabatNrp = _modalPejabat + ' input[name="nrp"]';
    const _inputPejabatJabatan = _modalPejabat + ' input[name="jabatan"]';
    // _draf untuk store ke database
    let _drafOptionRequest = {};

    // Open Modal
    const openModal = (modal) => {
        $(modal).modal("show");
    }

    // Close Modal
    const closeModal = (modal) => {
        $(modal).modal("hide");
    }

    // Clear form pejabat
    const clearFormPejabat = () => {
        $(_inputPejabatId).val('');
        $(_inputPejabatNama).val('');
        $(_inputPejabatPangkat).val('');
        $(_inputPejabatNrp).val('');
        $(_inputPejabatJabatan).val('');
    }

    const resetDefault = () => {
        clearFormPejabat();
        _drafOptionRequest = {};
    }

    // set value form if update action
    // id pejabat armed atau kostrad digunakan untuk mencari data pejabat
    const setValueFormPejabat = (id) => {
        // Get 1 data pejabat by id
        let dataPejabatFirst = _dataPejabat.data.find((x) => x.id == id);
        // Cari pejabat dari global data pejabat
        $(_inputPejabatId).val(dataPejabatFirst.id);
        $(_inputPejabatNama).val(dataPejabatFirst.nama);
        $(_inputPejabatPangkat).val(dataPejabatFirst.pangkat);
        $(_inputPejabatNrp).val(dataPejabatFirst.nrp);
        $(_inputPejabatJabatan).val(dataPejabatFirst.jabatan);
    }

    // Get data pejabat dari form
    const getDataPejabat = () => {
        let id = $(_inputPejabatId).val();
        let nama = $(_inputPejabatNama).val();
        let pangkat = $(_inputPejabatPangkat).val();
        let nrp = $(_inputPejabatNrp).val();
        let jabatan = $(_inputPejabatJabatan).val();

        let data = {
            nama: nama,
            pangkat: pangkat,
            nrp: nrp,
            jabatan: jabatan,
        };

        if (_drafOptionRequest.action == 'update') {
            data = {
                id: id,
                ...data,
            };
        }

        if (_drafOptionRequest.action == 'delete') {
            data = {
                id: id,
            };
        }

        return data;
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

    // Create pejabat
    const createPejabat = () => {
        setDrafOptionRequest({
            url: _drafOption.storeUrl,
            action: 'store',
        });
        clearFormPejabat();
        openModal(_modalPejabat);
    }

    // Update pejabat
    // id integer required pejabat armed atau kostrad
    const updatePejabat = (id) => {
        setDrafOptionRequest({
            url: _drafOption.updateUrl,
            action: 'update',
        });
        setValueFormPejabat(id);
        openModal(_modalPejabat);
    }

    // Delete pejabat
    const deletePejabat = (id) => {
        setDrafOptionRequest({
            url: _drafOption.deleteUrl,
            action: 'delete',
        });
        setValueFormPejabat(id);
        openModal(_modalDeleteConfirm);
    }

    // Set data in local
    const setDataLocal = (value) => {
        _dataPejabat.push(value);
    }

    // save dan store data pejabat ke database
    const saveEvent = () => {
        // Initialize
        let requestUrl = _drafOptionRequest.url;
        let action = _drafOptionRequest.action;
        let data = getDataPejabat();
        // Send request
        requestServer({
            url: url + requestUrl,
            data: data,
            onLoader: true,
            onSuccess: function(value) {
                close_swal(true, 'Berhasil ' + action + ' kemampuan', 'success');
                closeModal();
                reloadPage();
            },
        });
    }
</script>
@endpush