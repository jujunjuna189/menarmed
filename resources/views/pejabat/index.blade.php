@extends('layouts.app_template')
@section('content')
<style>
    .pejabat-page .btn, #modal-pejabat .btn { box-shadow: none !important; }
    .pejabat-page .table td { padding-top: 10px; padding-bottom: 10px; }
    .pejabat-jabatan-preview { display: inline-flex; align-items: center; gap: 6px; }
    .pejabat-view { display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; flex: 0 0 24px; padding: 0; border: 0; border-radius: 4px; background: transparent; color: #667382; cursor: pointer; }
    .pejabat-view:hover { background: #f1f3f5; color: #206bc4; }
    .pejabat-view:focus-visible { outline: 2px solid #206bc4; outline-offset: 2px; }
    .pejabat-view .icon { width: 15px; height: 15px; margin: 0; }
    .pejabat-action { width: 30px; height: 30px; padding: 0; border: 0; background: transparent; }
    .pejabat-menu { position: fixed !important; z-index: 1050; border: 1px solid #dce1e7; box-shadow: none; }
    .pejabat-toolbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; border-bottom: 1px solid #dce1e7; padding-right: 16px; }
    .pejabat-toolbar .nav-tabs { border: 0; }
    .pejabat-filters { display: flex; gap: 8px; flex-wrap: wrap; padding: 8px 0; }
    .pejabat-filters .input-icon { width: 220px; }
    @media (max-width: 575.98px) {
        .pejabat-toolbar { padding: 0 12px; }
        .pejabat-filters { width: 100%; }
        .pejabat-filters .input-icon { width: 100%; }
    }
</style>
<h2 class="page-title mb-3">Pejabat</h2>
<div class="row pejabat-page">
    <div class="col-md-12">
        <div class="card">
            <div class="pejabat-toolbar">
            <ul class="nav nav-tabs" data-bs-toggle="tabs">
                <li class="nav-item">
                    <a href="#tabs-armed" class="nav-link fw-bold {{ $active_tab === 'armed' ? 'active' : '' }}" data-bs-toggle="tab" onclick="switchTab(1)">
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
                    <a href="#tabs-kostrad" class="nav-link fw-bold {{ $active_tab === 'kostrad' ? 'active' : '' }}" data-bs-toggle="tab" onclick="switchTab(2)">
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
            <form action="{{ route('pejabat') }}" method="GET" class="pejabat-filters">
                <input type="hidden" name="tab" id="pejabat-filter-tab" value="{{ $active_tab }}">
                <div class="input-icon">
                    <span class="input-icon-addon"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="10" cy="10" r="7"/><path d="m21 21-6-6"/></svg></span>
                    <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Cari pejabat" aria-label="Cari nama, pangkat, NRP, atau jabatan">
                </div>
                <select name="sort" class="form-select w-auto" onchange="this.form.requestSubmit()" aria-label="Urutan pejabat">
                    @foreach(['-created_at' => 'Terbaru', 'created_at' => 'Terlama', 'nama' => 'Nama A-Z', '-nama' => 'Nama Z-A', 'nrp' => 'NRP A-Z', '-nrp' => 'NRP Z-A'] as $value => $label)
                        <option value="{{ $value }}" {{ $sort === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-primary" onclick="createPejabat()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                    <span id="pejabat-add-label">Tambah {{ ucfirst($active_tab) }}</span>
                </button>
            </form>
            </div>
            <div class="card-body p-0">
                <div class="tab-content">
                    <div class="tab-pane {{ $active_tab === 'armed' ? 'active show' : '' }}" id="tabs-armed">
                        @include('pejabat.partials.table', ['records' => $armed, 'label' => 'Armed', 'type' => 'armed'])
                    </div>
                    <div class="tab-pane {{ $active_tab === 'kostrad' ? 'active show' : '' }}" id="tabs-kostrad">
                        @include('pejabat.partials.table', ['records' => $kostrad, 'label' => 'Kostrad', 'type' => 'kostrad'])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
<div class="modal fade" id="modal-view-jabatan" tabindex="-1" aria-labelledby="view-jabatan-title" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="view-jabatan-title">Jabatan Pejabat</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" style="overflow-wrap: anywhere">
                <div class="text-muted small mb-1">Nama</div>
                <div class="fw-medium mb-3" id="view-jabatan-name"></div>
                <div class="text-muted small mb-1">Jabatan</div>
                <p class="mb-0" id="view-jabatan-value" style="white-space: pre-line"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-pejabat" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <form class="modal-content" id="pejabat-form">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pejabat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id">
                <div class="mb-3">
                    <label for="pejabat-nama" class="form-label required">Nama</label>
                    <input type="text" class="form-control" id="pejabat-nama" name="nama" required placeholder="Nama">
                </div>
                <div class="mb-3">
                    <label for="pejabat-pangkat" class="form-label required">Pangkat</label>
                    <input type="text" class="form-control" id="pejabat-pangkat" name="pangkat" required placeholder="Pangkat">
                </div>
                <div class="mb-3">
                    <label for="pejabat-nrp" class="form-label required">NRP</label>
                    <input type="text" class="form-control" id="pejabat-nrp" name="nrp" required placeholder="NRP">
                </div>
                <div class="mb-3">
                    <label for="pejabat-jabatan" class="form-label required">Jabatan</label>
                    <input type="text" class="form-control" id="pejabat-jabatan" name="jabatan" required placeholder="Jabatan">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary ms-auto" id="save-pejabat">
                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('script')
<script>
document.querySelectorAll('.pejabat-view').forEach(button => {
    button.addEventListener('click', () => {
        document.getElementById('view-jabatan-name').textContent = button.dataset.name;
        document.getElementById('view-jabatan-value').textContent = button.dataset.jabatan;
        const element = document.getElementById('modal-view-jabatan');
        (bootstrap.Modal.getInstance(element) || new bootstrap.Modal(element)).show();
    });
});
</script>
<script>
    let _currentTab = {{ $active_tab === 'kostrad' ? 2 : 1 }};
    let _dataPejabat = {};
    let _drafOption = {};
    const _dataByTab = {
        1: @json($armed->items()),
        2: @json($kostrad->items()),
    };
    let _option = [{
            tab: 1,
            pejabat: 'armed',
            storeUrl: '/api/armed/store',
            updateUrl: '/api/armed/update',
            deleteUrl: '/api/armed/delete'
        },
        {
            tab: 2,
            pejabat: 'kostrad',
            storeUrl: '/api/kostrad/store',
            updateUrl: '/api/kostrad/update',
            deleteUrl: '/api/kostrad/delete',
        },
    ];

    const switchTab = (tab) => {
        _currentTab = tab;
        document.getElementById('pejabat-filter-tab').value = tab === 2 ? 'kostrad' : 'armed';
        document.getElementById('pejabat-add-label').textContent = tab === 2 ? 'Tambah Kostrad' : 'Tambah Armed';
        _drafOption = _option.find((x) => x.tab == tab);
        _dataPejabat = {
            pejabat: _drafOption.pejabat,
            data: _dataByTab[tab],
        };
    };

    switchTab(_currentTab);

    // Modal delete
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
        const element = document.querySelector(modal);
        (bootstrap.Modal.getInstance(element) || new bootstrap.Modal(element)).show();
    }

    // Close Modal
    const closeModal = (modal) => {
        bootstrap.Modal.getInstance(document.querySelector(modal))?.hide();
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
        let dataPejabatFirst = _dataPejabat.data.find((x) => x.id == id);
        if (!dataPejabatFirst) {
            return;
        }
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
        document.querySelector(_modalPejabat + ' .modal-title').textContent = 'Tambah Pejabat ' + _drafOption.pejabat;
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
        document.querySelector(_modalPejabat + ' .modal-title').textContent = 'Edit Pejabat ' + _drafOption.pejabat;
        openModal(_modalPejabat);
    }

    const deletePejabat = async (id) => {
        const item = _dataPejabat.data.find(record => record.id == id);
        const endpoint = _drafOption.deleteUrl;
        if (!item || !await confirmDelete('Hapus pejabat "' + item.nama + '"?')) return;
        $.ajax({ url: url + endpoint, type: 'POST', dataType: 'json', data: { id: id }, headers: { 'X-CSRF-TOKEN': token } })
            .done(result => {
                if (String(result.status).toLowerCase() !== 'success') {
                    notif('Pejabat gagal dihapus.', 'danger');
                    return;
                }
                sessionStorage.setItem('pejabat-message', 'Pejabat berhasil dihapus.');
                location.reload();
            }).fail(() => notif('Pejabat gagal dihapus.', 'danger'));
    };

    // Set data in local
    const setDataLocal = (value) => {
        _dataPejabat.push(value);
    }

    const saveEvent = () => {
        const form = document.getElementById('pejabat-form');
        const button = document.getElementById('save-pejabat');
        if (button.disabled || !form.reportValidity()) return;
        button.disabled = true;
        button.textContent = 'Menyimpan...';
        $.ajax({
            url: url + _drafOptionRequest.url, type: 'POST', dataType: 'json',
            data: getDataPejabat(), headers: { 'X-CSRF-TOKEN': token }
        }).done(result => {
            if (String(result.status).toLowerCase() !== 'success') {
                notif('Pejabat gagal disimpan.', 'danger');
                return;
            }
            sessionStorage.setItem('pejabat-message', 'Pejabat berhasil disimpan.');
            location.reload();
        }).fail(response => {
            const errors = response.responseJSON?.errors;
            notif(errors ? Object.values(errors).flat().join(' ') : 'Pejabat gagal disimpan.', 'danger');
        }).always(() => { button.disabled = false; button.textContent = 'Simpan'; });
    };
    document.getElementById('pejabat-form').addEventListener('submit', event => {
        event.preventDefault();
        saveEvent();
    });
    document.querySelectorAll('.pejabat-action').forEach(button => {
        const menu = button.nextElementSibling;
        button.addEventListener('shown.bs.dropdown', () => {
            document.body.appendChild(menu);
            const bounds = button.getBoundingClientRect();
            menu.style.setProperty('transform', 'none');
            menu.style.setProperty('inset', 'auto');
            menu.style.left = Math.max(8, bounds.right - menu.offsetWidth) + 'px';
            menu.style.top = Math.max(8, bounds.bottom + menu.offsetHeight + 4 > innerHeight ? bounds.top - menu.offsetHeight - 4 : bounds.bottom + 4) + 'px';
        });
        button.addEventListener('hidden.bs.dropdown', () => {
            button.parentElement.appendChild(menu);
            menu.removeAttribute('style');
        });
    });
    const message = sessionStorage.getItem('pejabat-message');
    if (message) {
        sessionStorage.removeItem('pejabat-message');
        notif(message, 'success');
    }
</script>
@endpush
