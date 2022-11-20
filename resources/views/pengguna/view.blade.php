@extends('layouts.app_template')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card rounded-10">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-2 d-flex justify-content-center mb-3">
                        <div class="rounded-circle border-dashed d-flex justify-content-center align-items-center bg-teal-lt" style="width: 100px; height: 100px">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="col-md-10 d-flex align-items-center">
                        <div class="text-center text-md-start w-100">
                            <h2 class="my-0">{{ $user->name }}</h2>
                            Username : <small>{{ $user->email }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3 rounded-10">
            <div class="card-body p-4">
                @if(empty($user->kemampuanModel))
                <div class="bg-dark-lt p-5 d-flex justify-content-center rounded-10">
                    <div class="text-center">
                        <div class="h3 mb-3">Belum ada kemampuan</div>
                        <a href="#" class="btn bg-primary-lt border-dashed" onclick="createKemampuan()">Tambah Kemampuan</a>
                    </div>
                </div>
                @else
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between fw-bold h4 text-muted px-2">
                            <div>Kemampuan</div>
                            <div>Total</div>
                        </div>
                        <div class="d-flex justify-content-between bg-light text-dark rounded-10 mb-1 py-2 px-3">
                            <div class="h4 mb-0 fw-normal">Lari</div>
                            <div class="fw-bold">{{ $user->kemampuanModel->lari }}</div>
                        </div>
                        <div class="d-flex justify-content-between bg-light text-dark rounded-10 mb-1 py-2 px-3">
                            <div class="h4 mb-0 fw-normal">Renang</div>
                            <div class="fw-bold">{{ $user->kemampuanModel->renang }}</div>
                        </div>
                        <div class="d-flex justify-content-between bg-light text-dark rounded-10 mb-1 py-2 px-3">
                            <div class="h4 mb-0 fw-normal">Jatri</div>
                            <div class="fw-bold">{{ $user->kemampuanModel->jatri }}</div>
                        </div>
                        <div class="d-flex justify-content-between bg-light text-dark rounded-10 mb-1 py-2 px-3">
                            <div class="h4 mb-0 fw-normal">Jatrat</div>
                            <div class="fw-bold">{{ $user->kemampuanModel->jatrat }}</div>
                        </div>
                        <div class="d-flex justify-content-between bg-light text-dark rounded-10 mb-1 py-2 px-3">
                            <div class="h4 mb-0 fw-normal">Pistol</div>
                            <div class="fw-bold">{{ $user->kemampuanModel->pistol }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between fw-bold h4 text-muted px-2">
                            <div>Kemampuan</div>
                            <div>Total</div>
                        </div>
                        <div class="d-flex justify-content-between bg-light text-dark rounded-10 mb-1 py-2 px-3">
                            <div class="h4 mb-0 fw-normal">Push Up</div>
                            <div class="fw-bold">{{ $user->kemampuanModel->push_up }}</div>
                        </div>
                        <div class="d-flex justify-content-between bg-light text-dark rounded-10 mb-1 py-2 px-3">
                            <div class="h4 mb-0 fw-normal">Sit Up</div>
                            <div class="fw-bold">{{ $user->kemampuanModel->sit_up }}</div>
                        </div>
                        <div class="d-flex justify-content-between bg-light text-dark rounded-10 mb-1 py-2 px-3">
                            <div class="h4 mb-0 fw-normal">Pull Up</div>
                            <div class="fw-bold">{{ $user->kemampuanModel->pull_up }}</div>
                        </div>
                        <div class="d-flex justify-content-between bg-light text-dark rounded-10 mb-1 py-2 px-3">
                            <div class="h4 mb-0 fw-normal">Shutle Run</div>
                            <div class="fw-bold">{{ $user->kemampuanModel->shutle_run }}</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="#" class="btn bg-yellow-lt border-dashed" onclick="updateKemampuan()">Ubah Kemampuan</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
<div class="modal modal-blur fade" id="modal-kemampuan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kemampuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="mb-3">
                    <label class="form-label">Lari</label>
                    <div class="input-group input-group-flat">
                        <input type="number" class="form-control" name="lari" autocomplete="off">
                        <span class="input-group-text ps-3 fw-bolder">
                            Meter
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Renang</label>
                    <div class="input-group input-group-flat">
                        <input type="number" class="form-control" name="renang" autocomplete="off">
                        <span class="input-group-text ps-3 fw-bolder">
                            Detik
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Menembak Senjata Ringan</label>
                    <div class="input-group input-group-flat">
                        <input type="number" class="form-control" name="jatri" autocomplete="off">
                        <span class="input-group-text ps-3 fw-bolder">
                            Poin
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Menembak Senjata Berat</label>
                    <div class="input-group input-group-flat">
                        <input type="number" class="form-control" name="jatrat" autocomplete="off">
                        <span class="input-group-text ps-3 fw-bolder">
                            Poin
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Menembak Pistol</label>
                    <div class="input-group input-group-flat">
                        <input type="number" class="form-control" name="pistol" autocomplete="off">
                        <span class="input-group-text ps-3 fw-bolder">
                            Poin
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Push-Up</label>
                    <div class="input-group input-group-flat">
                        <input type="number" class="form-control" name="push_up" autocomplete="off">
                        <span class="input-group-text ps-3 fw-bolder">
                            x
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Sit-Up</label>
                    <div class="input-group input-group-flat">
                        <input type="number" class="form-control" name="sit_up" autocomplete="off">
                        <span class="input-group-text ps-3 fw-bolder">
                            x
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pull-Up</label>
                    <div class="input-group input-group-flat">
                        <input type="number" class="form-control" name="pull_up" autocomplete="off">
                        <span class="input-group-text ps-3 fw-bolder">
                            x
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Shutle-Run</label>
                    <div class="input-group input-group-flat">
                        <input type="number" class="form-control" name="shutle_run" autocomplete="off">
                        <span class="input-group-text ps-3 fw-bolder">
                            Detik
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                    Batal
                </a>
                <a href="#" class="btn btn-primary ms-auto" onclick="saveKemampuan()">
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
@endsection
@push('script')
<script>
    const _modalKemampuan = '#modal-kemampuan';
    const _inputUser_id = _modalKemampuan + ' ' + 'input[name="user_id"]';
    const _inputLari = _modalKemampuan + ' ' + 'input[name="lari"]';
    const _inputRenang = _modalKemampuan + ' ' + 'input[name="renang"]';
    const _inputJatri = _modalKemampuan + ' ' + 'input[name="jatri"]';
    const _inputJatrat = _modalKemampuan + ' ' + 'input[name="jatrat"]';
    const _inputPistol = _modalKemampuan + ' ' + 'input[name="pistol"]';
    const _inputPush_up = _modalKemampuan + ' ' + 'input[name="push_up"]';
    const _inputSit_up = _modalKemampuan + ' ' + 'input[name="sit_up"]';
    const _inputPull_up = _modalKemampuan + ' ' + 'input[name="pull_up"]';
    const _inputShutle_run = _modalKemampuan + ' ' + 'input[name="shutle_run"]';
    // data kemampuan
    const _kemampuan = <?= json_encode($user->kemampuanModel) ?>;

    const openModal = () => {
        $(_modalKemampuan).modal("show");
    }

    const closeModal = () => {
        $(_modalKemampuan).modal("hide");
    }

    const clearModalKemampuan = () => {
        $(_inputLari).val('');
        $(_inputRenang).val('');
        $(_inputJatri).val('');
        $(_inputJatrat).val('');
        $(_inputPistol).val('');
        $(_inputPush_up).val('');
        $(_inputSit_up).val('');
        $(_inputPull_up).val('');
        $(_inputShutle_run).val('');
    }

    const setModelKemampuan = () => {
        $(_inputLari).val(getNumberFromString(_kemampuan.lari));
        $(_inputRenang).val(getNumberFromString(_kemampuan.renang));
        $(_inputJatri).val(getNumberFromString(_kemampuan.jatri));
        $(_inputJatrat).val(getNumberFromString(_kemampuan.jatrat));
        $(_inputPistol).val(getNumberFromString(_kemampuan.pistol));
        $(_inputPush_up).val(getNumberFromString(_kemampuan.push_up));
        $(_inputSit_up).val(getNumberFromString(_kemampuan.sit_up));
        $(_inputPull_up).val(getNumberFromString(_kemampuan.pull_up));
        $(_inputShutle_run).val(getNumberFromString(_kemampuan.shutle_run));
    }

    const getNumberFromString = (value) => {
        return value.match(/[\d.]+/);
    }

    const createKemampuan = () => {
        openModal();
        clearModalKemampuan();
    }

    const updateKemampuan = () => {
        console.log(_kemampuan.lari);
        openModal();
        setModelKemampuan();
    }

    const getDataKemampuan = () => {
        let user_id = $(_inputUser_id).val();
        let lari = $(_inputLari).val() + ' Meter';
        let renang = $(_inputRenang).val() + ' Detik';
        let jatri = $(_inputJatri).val() + ' Poin';
        let jatrat = $(_inputJatrat).val() + ' Poin';
        let pistol = $(_inputPistol).val() + ' Poin';
        let push_up = $(_inputPush_up).val() + ' x';
        let sit_up = $(_inputSit_up).val() + ' x';
        let pull_up = $(_inputPull_up).val() + ' x';
        let shutle_run = $(_inputShutle_run).val() + ' Detik';

        let data = {
            user_id: user_id,
            lari: lari,
            renang: renang,
            jatri: jatri,
            jatrat: jatrat,
            pistol: pistol,
            push_up: push_up,
            sit_up: sit_up,
            pull_up: pull_up,
            shutle_run: shutle_run,
        };

        return data;
    }

    const saveKemampuan = () => {
        let data = getDataKemampuan();
        requestServer({
            url: url + '/api/pengguna/kemampuan/store',
            data: data,
            onLoader: true,
            onSuccess: function(value) {
                close_swal(true, 'Berhasil tambah kemampuan', 'success');
                closeModal();
                reloadPage();
            },
        });
    }
</script>
@endpush