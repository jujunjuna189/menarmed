@extends('layouts.app_template')

@section('content')
<style>
    #map-monitor:fullscreen { background: #f4f6fa; padding: 1rem; overflow: auto; }
    #map-track { height: 620px; min-height: 200px; background: #e9ecef; }
    .map-personnel-list { max-height: 470px; overflow-y: auto; }
    @media (max-width: 991.98px) {
        #map-track { height: 480px; }
        .map-personnel-list { max-height: 260px; }
    }
</style>

<div id="map-monitor">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="status-dot status-dot-animated bg-red" id="map-status-dot"></span>
                <h2 class="page-title mb-0">Peta Kehadiran</h2>
            </div>
            <div class="text-muted mt-1">Lokasi absensi personel pada {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</div>
        </div>
        <div class="d-flex gap-2 d-print-none">
            <a href="{{ route('absensi') }}" class="btn btn-outline-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12h14"/><path d="M5 12l6 6"/><path d="M5 12l6 -6"/></svg>
                Monitor Absensi
            </a>
            <button type="button" class="btn btn-icon btn-outline-secondary" id="map-fullscreen" title="Tampilkan layar penuh" aria-label="Tampilkan layar penuh">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 8v-2a2 2 0 0 1 2 -2h2"/><path d="M4 16v2a2 2 0 0 0 2 2h2"/><path d="M16 4h2a2 2 0 0 1 2 2v2"/><path d="M16 20h2a2 2 0 0 0 2 -2v-2"/></svg>
            </button>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4 col-xl-3">
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body py-3">
                            <div class="text-muted small">Sudah Absen</div>
                            <div class="h2 mb-0" id="map-present-count">0</div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-body py-3">
                            <div class="text-muted small">Lokasi Valid</div>
                            <div class="h2 text-green mb-0" id="map-location-count">0</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Personel Terpantau</h3>
                        <div class="text-muted small" id="map-update-text">Menghubungkan data live...</div>
                    </div>
                </div>
                <div class="list-group list-group-flush map-personnel-list" id="map-personnel-list">
                    <div class="list-group-item text-center text-muted py-4">
                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                        Memuat lokasi...
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-xl-9">
            <div class="card overflow-hidden">
                <div id="map-track" aria-label="Peta lokasi absensi personel"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const users = @json($user);
    const usersById = new Map(users.map(user => [String(user.id), user]));
    const markerByUser = new Map();
    const listElement = document.getElementById('map-personnel-list');
    const updateText = document.getElementById('map-update-text');
    const statusDot = document.getElementById('map-status-dot');
    const mapElement = document.getElementById('map-track');
    const map = L.map(mapElement).setView([-6.5569, 107.4462], 13);
    let firstFit = true;
    let polling = false;

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19
    }).addTo(map);

    const escapeHtml = value => String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    const validCoordinates = presence => {
        const latitude = Number(presence.latitude);
        const longitude = Number(presence.longitude);
        return Number.isFinite(latitude) && Number.isFinite(longitude)
            && latitude >= -90 && latitude <= 90
            && longitude >= -180 && longitude <= 180;
    };

    const formatTime = value => new Intl.DateTimeFormat('id-ID', {
        hour: '2-digit', minute: '2-digit'
    }).format(new Date(value));

    const drawMap = presences => {
        const validPresences = presences.filter(item => usersById.has(String(item.user_id)) && validCoordinates(item));
        const activeIds = new Set(validPresences.map(item => String(item.user_id)));

        markerByUser.forEach((marker, userId) => {
            if (!activeIds.has(userId)) {
                map.removeLayer(marker);
                markerByUser.delete(userId);
            }
        });

        validPresences.forEach(presence => {
            const userId = String(presence.user_id);
            const user = usersById.get(userId);
            const coordinates = [Number(presence.latitude), Number(presence.longitude)];
            let marker = markerByUser.get(userId);

            if (!marker) {
                const popup = document.createElement('div');
                const name = document.createElement('strong');
                const details = document.createElement('div');
                name.textContent = user.name;
                details.className = 'text-muted mt-1';
                details.textContent = `${presence.ket || 'Sudah absen'} - ${formatTime(presence.created_at)}`;
                popup.append(name, details);
                marker = L.marker(coordinates).bindPopup(popup).addTo(map);
                markerByUser.set(userId, marker);
            } else {
                marker.setLatLng(coordinates);
            }
        });

        listElement.innerHTML = validPresences.length
            ? validPresences.map(presence => {
                const user = usersById.get(String(presence.user_id));
                return `<button type="button" class="list-group-item list-group-item-action" data-user-id="${user.id}">
                    <div class="d-flex align-items-center">
                        <span class="status-dot bg-green me-2"></span>
                        <div class="flex-fill text-start">
                            <div class="fw-medium">${escapeHtml(user.name)}</div>
                            <div class="text-muted small">${escapeHtml(user.pangkat || '-')} · ${formatTime(presence.created_at)}</div>
                        </div>
                    </div>
                </button>`;
            }).join('')
            : '<div class="list-group-item text-center text-muted py-4">Belum ada lokasi absensi yang valid.</div>';

        document.getElementById('map-present-count').textContent = presences.filter(item => usersById.has(String(item.user_id))).length;
        document.getElementById('map-location-count').textContent = validPresences.length;

        if (firstFit && validPresences.length) {
            const bounds = L.latLngBounds(validPresences.map(item => [Number(item.latitude), Number(item.longitude)]));
            map.fitBounds(bounds, { padding: [40, 40], maxZoom: 16 });
            firstFit = false;
        }
    };

    const loadLocations = async () => {
        if (polling || document.hidden) return;
        polling = true;

        try {
            const response = await fetch(`${url}/api/absensi/show_today_presence`, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token }
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const result = await response.json();
            drawMap(result.data?.today_presence || []);
            statusDot.className = 'status-dot status-dot-animated bg-green';
            updateText.textContent = `Diperbarui ${new Intl.DateTimeFormat('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).format(new Date())}`;
        } catch (error) {
            statusDot.className = 'status-dot bg-red';
            updateText.textContent = 'Gagal memperbarui lokasi, mencoba kembali...';
        } finally {
            polling = false;
        }
    };

    listElement.addEventListener('click', function (event) {
        const item = event.target.closest('[data-user-id]');
        if (!item) return;
        const marker = markerByUser.get(item.dataset.userId);
        if (marker) {
            map.setView(marker.getLatLng(), 17);
            marker.openPopup();
        }
    });

    document.getElementById('map-fullscreen').addEventListener('click', async function () {
        const monitor = document.getElementById('map-monitor');
        if (!document.fullscreenElement) await monitor.requestFullscreen();
        else await document.exitFullscreen();
    });

    const resizeMap = () => {
        const availableHeight = window.innerHeight - mapElement.getBoundingClientRect().top - 24;
        mapElement.style.height = `${Math.max(200, availableHeight)}px`;
        map.invalidateSize();
    };

    window.addEventListener('resize', resizeMap);
    document.addEventListener('fullscreenchange', () => window.requestAnimationFrame(resizeMap));
    window.requestAnimationFrame(resizeMap);
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) loadLocations();
    });

    loadLocations();
    window.setInterval(loadLocations, 10000);
});
</script>
@endpush
