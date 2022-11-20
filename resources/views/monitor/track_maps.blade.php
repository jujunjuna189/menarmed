@extends('layouts.app_template')
@section('content')
<div class="row g-3 align-items-center mb-4">
    <div class="col-auto">
        <span class="status-indicator status-red status-indicator-animated">
            <span class="status-indicator-circle"></span>
            <span class="status-indicator-circle"></span>
            <span class="status-indicator-circle"></span>
        </span>
    </div>
    <div class="col">
        <h2 class="page-title">
            Tracking Absensi Personil {{ date('d M Y') }}
        </h2>
        <div class="text-muted">
            <ul class="list-inline list-inline-dots mb-0">
                <li class="list-inline-item"><span class="text-red">Live</span></li>
                <li class="list-inline-item">Monitoring tracking maps absensi personil</li>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="ratio ratio-21x9">
                <div>
                    <div id="map-track" class="w-100 h-100"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    let _user = <?= json_encode($user) ?>;
    let _todayPresence = [];
    let _marker = [];

    // Initialize maps
    var map = L.map('map-track').setView([-5.1500288, 119.4618396], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

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
        $.each(_marker, function(index, value) {
            map.removeLayer(value);
        });

        _marker = [];

        $.each(_user, function(i, val) {
            if (val.latitude != null) {
                _marker.push(L.marker([val.latitude, val.longitude]).bindPopup(val.name));
            }
        });

        $.each(_marker, function(index, value) {
            map.addLayer(value);
        });
    }
</script>
@endpush