@extends('layouts.app_template')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header justify-content-between">
                <h3 class="card-title">Buat Artikel</h3>
                <div>
                    <a href="#" class="btn bg-blue-lt border-dashed" onclick="saveArtikel()">Simpan</a>
                </div>
            </div>
            <div class="card-body" id="form">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Judul">Judul</label>
                            <input type="text" name="judul" id="judul" class="form-control" required placeholder="...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" cols="30" rows="3" class="form-control" required placeholder="..."></textarea>
                        </div>
                    </div>
                </div>
                <div style="height: 10px;"></div>
                <div id="summernote"></div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    let _artikel_id = '<?= json_decode($artikel_id) ?>';
    const _summernote = '#summernote';
    const _form = '#form';
    const _formJudul = _form + ' #judul';
    const _formDeskripsi = _form + ' #deskripsi';

    $(document).ready(function() {
        let summernote = $(_summernote).summernote({
            placeholder: 'Tulis disini!...',
            height: 500, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: true,
            lineHeights: 0.2,
            lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['fontsize', 'color', 'forecolor', 'backcolor']],
                ['font', ['fontname']],
                ['para', ['paragraph', 'hr']],
                ['style', ['ul', 'ol', 'height']],
                ['insert', ['link', 'picture', 'table', 'video']], // image and doc are customized buttons
                ['misc', ['codeview']],
            ],
        });

        // summernote.summernote('lineHeight', 0.3);
    });

    const getDataArtikel = () => {
        let judul = $(_formJudul).val();
        let deskripsi = $(_formDeskripsi).val();
        let artikel = $(_summernote).summernote('code');
        artikel = JSON.stringify(artikel);

        let data = {
            artikel_id: _artikel_id,
            judul: judul,
            deskripsi: deskripsi,
            artikel: artikel,
        };

        return data;
    }

    const saveArtikel = () => {
        let data = getDataArtikel();
        requestServer({
            url: url + '/api/artikel/store',
            data: data,
            onLoader: true,
            onSuccess: function(value) {
                close_swal(true, 'Berhasil tambah artikel', 'success');
                setData(value);
            },
        });
    }

    const setData = (value) => {
        _artikel_id = value.data.id;
        console.log(value);
    }
</script>
@endpush