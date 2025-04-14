@extends('layouts.app_template')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header justify-content-between">
                <h3 class="card-title">{{ $role->role }}</h3>
                @if($role->role == 'Admin')
                <div>
                    <span class="btn bg-blue-lt border-dashed" onclick="openModalUser()">Tambah Admin</span>
                </div>
                @endif
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
                            <form action="javascript:onSearch()" method="get">
                                <input type="text" name="search" class="form-control form-control-sm" aria-label="Search invoice">
                            </form>
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
                            <th>Nama</th>
                            <th>Username</th>
                            @if($table->kemampuan)
                            <th>Kemampuan</th>
                            @endif
                            @if($table->aksi)
                            <th style="width: 10rem;" class="bg-dark text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengguna as $val)
                        <tr>
                            <td><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"></td>
                            <td>{{ $no++ }}</td>
                            <td>{{ $val->name ?? '-' }}</td>
                            <td>{{ $val->email }}</td>
                            @if($table->kemampuan)
                            <th>@if(empty($val->kemampuanModel)) <span class="badge bg-red-lt">Belum ada data</span> @else <span class="badge bg-success-lt">Tersedia</span> @endif</th>
                            @endif
                            @if($table->aksi)
                            <td class="text-center">
                                <a href="{{ route('pengguna.view', ['user_id' => $val->id]) }}" class="btn btn-icon border-dashed" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="12" cy="12" r="2"></circle>
                                        <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"></path>
                                    </svg>
                                </a>
                                <!-- <a href="#" class="btn btn-icon border-dashed" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ubah">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M12 15l8.385 -8.415a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3z"></path>
                                        <path d="M16 5l3 3"></path>
                                        <path d="M9 7.07a7.002 7.002 0 0 0 1 13.93a7.002 7.002 0 0 0 6.929 -5.999"></path>
                                    </svg>
                                </a>
                                <a href="#" class="btn btn-icon border-dashed" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <line x1="4" y1="7" x2="20" y2="7"></line>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                    </svg>
                                </a> -->
                            </td>
                            @endif
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
<div class="modal modal-blur fade" id="modal-user" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto">
                <div id="list-user">
                    <div class="border p-3 rounded bg-white"></div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                    Batal
                </a>
                <div class="ms-auto">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    const _inputSearch = 'input[name="search"]';

    const onSearch = () => {
        let searchData = $(_inputSearch).val();
        location.href = "<?= url()->current() . '?filter[name]=' ?>" + searchData + "<?= '&key=' . $role->key ?>";
    }
</script>
@endpush
@push('script')
<script>
    let _modal_user = '#modal-user';
    let _modal_list_user = '#modal-user #list-user';

    const openModalUser = () => {
        $(_modal_user).modal("show");
        getUser();
    }

    const closeModal = () => {
        $(_modal_user).modal("hide");
    }

    const getUser = () => {
        requestServer({
            url: url + '/pengguna/json?key=3',
            type: 'get',
            onLoader: true,
            onSuccess: function(value) {
                close_swal(false);
                $(_modal_list_user).empty();
                $.each(value.data.pengguna, (index, item) => {
                    var element = `<div class="border py-2 px-3 rounded bg-white mb-1" style="display: flex; justify-content: space-between">
                        <div>
                            ${item.name}
                            <div class="">${item.email}</div>
                        </div>
                        <div>
                            <div class="btn btn-primary px-2 py-2" onclick="saveAdmin(${item.id})">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round" ><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                            </div>
                        </div>
                    </div>`;
                    $(_modal_list_user).append(element);
                });
            },
        });
    }

    const saveAdmin = (id) => {
        requestServer({
            url: url + '/pengguna/update-role',
            data: {
                id: id
            },
            onLoader: true,
            onSuccess: function(value) {
                close_swal(true, 'Berhasil Tambah Admin', 'success');
                closeModal();
                reloadPage();
            },
        });
    }
</script>
@endpush