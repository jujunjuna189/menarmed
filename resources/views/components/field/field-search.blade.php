<form action="javascript:onSearch()" method="get">
    <div class="form-group">
        <input type="text" name="search" id="search" class="form-control" placeholder="Cari...">
    </div>
</form>
@push('script')
<script>
    const _inputSearch = 'input[name="search"]';
    const onSearch = () => {
        let searchData = $(_inputSearch).val();
        if (searchData != '') {
            location.href = "<?= url()->current() . '?filter[name]=' ?>" + searchData;
        } else {
            location.href = '<?= url()->current() ?>';
        }
    }
</script>
@endpush