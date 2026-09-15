@extends('layouts.app_template')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header justify-content-between">
                <h3 class="card-title">E-Learning</h3>
                <div>
                    <span class="btn bg-blue-lt border-dashed" onclick="openModalELearning()">Tambah E-Learning</span>
                </div>
            </div>
            <div class="card-body border-bottom py-3">
                <div class="d-flex">
                    <div class="text-muted">
                        Show
                        <div class="mx-2 d-inline-block">
                            <input type="text" class="form-control form-control-sm" value="8" size="3" aria-label="Invoices count">
                        </div>
                        entries
                    </div>
                    <div class="ms-auto text-muted">
                        Search:
                        <div class="ms-2 d-inline-block">
                            <input type="text" class="form-control form-control-sm" aria-label="Search invoice">
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"></th>
                            <th class="w-1">No.
                                <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-dark icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <polyline points="6 15 12 9 18 15"></polyline>
                                </svg>
                            </th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>(URL) Tujuan</th>
                            <th style="width: 10rem;" class="bg-dark text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($learning as $val)
                        <tr>
                            <td><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"></td>
                            <td>{{ $no++ }}</td>
                            <td>{{ $val->judul ?? '...' }}</td>
                            <td>{{ substr($val->deskripsi, 0, 20)}}...</td>
                            <td>{{ substr($val->path, 0, 25) }}...</td>
                            <td class="text-center">
                                <span onclick="window.open('<?= $val->path ?>', '_target')" class="btn btn-icon border-dashed" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="12" cy="12" r="2"></circle>
                                        <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"></path>
                                    </svg>
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-muted">Showing <span>1</span> to <span>8</span> of <span>16</span> entries</p>
                <ul class="pagination m-0 ms-auto">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                            <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <polyline points="15 6 9 12 15 18"></polyline>
                            </svg>
                            prev
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item active"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">
                            next
                            <!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <polyline points="9 6 15 12 9 18"></polyline>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
<div class="modal modal-blur fade" id="modal-e-learning" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah E-Learning</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Judul</label>
                    <input type="text" class="form-control" name="judul" placeholder="..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" rows="3" name="deskripsi" placeholder="..."></textarea>
                </div>
                <div>
                    <label class="form-label">(URL) Tujuan</label>
                    <input type="text" class="form-control" name="path" placeholder="https://docs.google.com/presentation" required>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                    Batal
                </a>
                <a href="#" class="btn btn-primary ms-auto" onclick="saveELearning()">
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
    let _modal_learning = '#modal-e-learning';
    let _modal_learning_judul = _modal_learning + ' input[name="judul"]';
    let _modal_learning_deskripsi = _modal_learning + ' textarea[name="deskripsi"]';
    let _modal_learning_path = _modal_learning + ' input[name="path"]';

    const openModalELearning = () => {
        $(_modal_learning).modal("show");
    }

    const closeModal = () => {
        $(_modal_learning).modal("hide");
    }

    const getModalData = () => {
        let judul = $(_modal_learning_judul).val();
        let deskripsi = $(_modal_learning_deskripsi).val();
        let path = $(_modal_learning_path).val();

        let data = {
            judul: judul,
            deskripsi: deskripsi,
            path: path,
        };

        return data;
    }

    const saveELearning = () => {
        let data = getModalData();
        requestServer({
            url: url + '/api/e-learning/store',
            data: data,
            onLoader: true,
            onSuccess: function(value) {
                close_swal(true, 'Berhasil tambah e-learning', 'success');
                closeModal();
                reloadPage();
            },
        });
    }
</script>
@endpush