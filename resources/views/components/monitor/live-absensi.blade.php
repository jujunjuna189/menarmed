<div class="row row-deck row-cards" id="absensi-list">
    @foreach($user as $val)
    <div class="col-md-3 col-lg-2 text-center">
        <div class="card placeholder-glow">
            <div class="card-body">
                <div class="mb-3 h2">{{ $val->name }}</div>
                <div class="placeholder placeholder-xs col-10"></div>
                <div class="placeholder placeholder-xs col-11"></div>
                <div class="mt-3">
                    <a href="#" tabindex="-1" class="btn btn-danger disabled placeholder col-4" aria-hidden="true"></a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<!-- JS Script live and reltime -->
@push('script')
<script>
    let _absensiList = '#absensi-list';
    let _user = <?= json_encode($user) ?>;
    let _todayPresence = [];

    $(document).ready(function() {
        absenTodayPresence();
        absenLive();
    });

    const absenLive = () => {
        setInterval(function() {
            absenTodayPresence();
        }, 3000);
    }

    const absenTodayPresence = () => {
        requestServer({
            url: url + '/api/absensi/show_today_presence',
            onLoader: false,
            onSuccess: function(value) {
                _todayPresence = value.data.today_presence;
                absenArrayProses();
            }
        });
    }

    const absenArrayProses = () => {
        $.each(_user, function(i, val) {
            var arrayFindIndex = _todayPresence.findIndex((x) => x.user_id == val.id);

            if (arrayFindIndex >= 0) {
                _user[i] = {
                    ...val,
                    ket: _todayPresence[arrayFindIndex].ket,
                    latitude: _todayPresence[arrayFindIndex].latitude,
                    longitude: _todayPresence[arrayFindIndex].longitude,
                    presence_at: _todayPresence[arrayFindIndex].created_at,
                };
            } else {
                _user[i] = {
                    ...val,
                    ket: null,
                    latitude: null,
                    longitude: null,
                    presence_at: null,
                };
            }
        });

        absenDraw();
    }

    const absenDraw = () => {
        var view = '';
        $(_absensiList).empty();
        $.each(_user, function(i, val) {
            view += val.ket != null ? absenComponentListOn({
                name: val.name,
                ket: val.ket,
            }) : absenComponentListOf({
                name: val.name
            });
        });

        $(_absensiList).append(view);
    }

    const absenComponentListOf = ({
        name = "Nama Lengkap",
    }) => {
        var view = '<div class="col-md-3 col-lg-2 text-center">' +
            '<div class="card placeholder-glow">' +
            '<div class="card-body">' +
            '<div class="mb-3 h2">' + name + '</div>' +
            '<div class="placeholder placeholder-xs col-10"></div>' +
            '<div class="placeholder placeholder-xs col-11"></div>' +
            '<div class="mt-3">' +
            '<a href="#" tabindex="-1" class="btn btn-danger disabled placeholder col-4" aria-hidden="true"></a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';

        return view;
    }

    const absenComponentListOn = ({
        name = "Nama Lengkap",
        ket = ""
    }) => {
        var view = '<div class="col-md-3 col-lg-2 text-center">' +
            '<div class="card placeholder-glow">' +
            '<div class="card-body">' +
            '<div class="mb-3 h2">' + name + '</div>' +
            '<div>Keterangan</div>' +
            '<div class="fw-bold">' + ket + '</div>' +
            '<div class="mt-3">' +
            '<a href="#" tabindex="-1" class="bg-green-lt text-green py-1 px-3 fw-bold rounded disabled" aria-hidden="true">Sudah Absen</a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';

        return view;
    }
</script>
@endpush