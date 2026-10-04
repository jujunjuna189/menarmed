@php
    $users = $user instanceof \Illuminate\Contracts\Pagination\Paginator
        ? collect($user->items())
        : collect($user);
    $filteredUserIds = collect($userIds)->map(fn ($id) => (string) $id)->values();
    $totalUsers = $filteredUserIds->count() ?: (method_exists($user, 'total') ? $user->total() : $users->count());
    $monitorPageData = [
        'users' => $users->values(),
        'userIds' => $filteredUserIds,
        'total' => $totalUsers,
        'first' => method_exists($user, 'firstItem') ? ($user->firstItem() ?? 1) : 1,
        'currentPage' => method_exists($user, 'currentPage') ? $user->currentPage() : 1,
        'lastPage' => method_exists($user, 'lastPage') ? $user->lastPage() : 1,
    ];
@endphp

<div id="attendance-monitor">
    <div class="row row-deck row-cards mb-3">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body py-3">
                    <div class="text-muted small">Total Personel</div>
                    <div class="h2 mb-0" id="monitor-total">{{ $totalUsers }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body py-3">
                    <div class="text-muted small">Sudah Absen</div>
                    <div class="h2 text-green mb-0" id="monitor-present">0</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body py-3">
                    <div class="text-muted small">Belum Absen</div>
                    <div class="h2 text-red mb-0" id="monitor-absent">{{ $totalUsers }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th class="text-nowrap">No.</th>
                        <th>Personel</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Lokasi</th>
                        <th class="text-end">Waktu</th>
                    </tr>
                </thead>
                <tbody id="attendance-list">
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Mengambil data absensi...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center">
            <span class="status-dot status-dot-animated bg-green me-2" id="monitor-status-dot"></span>
            <span class="text-muted small" id="monitor-status">Menghubungkan data live...</span>
            <span class="text-muted small ms-auto" id="monitor-updated"></span>
        </div>
        @if (method_exists($user, 'total'))
        <div class="card-footer d-flex flex-column flex-sm-row gap-2 align-items-sm-center" id="monitor-pagination">
            <p class="m-0 text-muted">
                Menampilkan <span>{{ $user->firstItem() ?? 0 }}</span>
                sampai <span>{{ $user->lastItem() ?? 0 }}</span>
                dari <span>{{ $user->total() }}</span> data
            </p>
            @if ($user->lastPage() > 1)
            <ul class="pagination m-0 ms-auto">
                <li class="page-item {{ $user->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $controller->prevPagination($user->currentPage(), 'absensi', request()->all())->link }}" aria-label="Sebelumnya">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <polyline points="15 6 9 12 15 18"></polyline>
                        </svg>
                        Sebelumnya
                    </a>
                </li>
                @foreach($controller->counterPagination($user->lastPage(), $user->currentPage(), 'absensi', request()->all()) as $page)
                <li class="page-item {{ $page->is_active ? 'active' : '' }}">
                    <a class="page-link" href="{{ $page->link }}">{{ $page->lable }}</a>
                </li>
                @endforeach
                <li class="page-item {{ $user->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $controller->nextPagination($user->currentPage(), $user->lastPage(), 'absensi', request()->all())->link }}" aria-label="Berikutnya">
                        Berikutnya
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <polyline points="9 6 15 12 9 18"></polyline>
                        </svg>
                    </a>
                </li>
            </ul>
            @endif
        </div>
        @endif
    </div>
</div>

<script type="application/json" id="monitor-page-data">@json($monitorPageData)</script>

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let pageData = JSON.parse(document.getElementById('monitor-page-data').textContent);
    let users = pageData.users;
    let filteredUserIds = new Set(pageData.userIds);
    let totalUsers = pageData.total;
    let firstRowNumber = pageData.first;
    let lastPresences = [];
    let changingPage = false;
    const attendanceList = document.getElementById('attendance-list');
    const statusText = document.getElementById('monitor-status');
    const statusDot = document.getElementById('monitor-status-dot');
    const updatedText = document.getElementById('monitor-updated');
    let polling = false;

    const escapeHtml = value => String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    const formatTime = value => value
        ? new Intl.DateTimeFormat('id-ID', { hour: '2-digit', minute: '2-digit' }).format(new Date(value))
        : '-';

    const drawAttendance = presences => {
        lastPresences = presences;
        const presenceByUser = new Map(presences.map(item => [String(item.user_id), item]));
        const rows = users.map((user, index) => {
            const presence = presenceByUser.get(String(user.id));
            const hasLocation = presence && Number.isFinite(Number(presence.latitude)) && Number.isFinite(Number(presence.longitude));

            return `<tr>
                <td class="text-muted text-nowrap">${firstRowNumber + index}</td>
                <td>
                    <div class="fw-medium">${escapeHtml(user.name)}</div>
                    <div class="text-muted small">${escapeHtml(user.pangkat || '-')}</div>
                </td>
                <td>${presence
                    ? '<span class="badge bg-green-lt text-green">Sudah absen</span>'
                    : '<span class="badge bg-red-lt text-red">Belum absen</span>'}</td>
                <td>${escapeHtml(presence?.ket || '-')}</td>
                <td>${hasLocation
                    ? `<a href="${@json(route('track_maps'))}" class="text-decoration-none">Tersedia</a>`
                    : '<span class="text-muted">-</span>'}</td>
                <td class="text-end text-muted text-nowrap">${formatTime(presence?.created_at)}</td>
            </tr>`;
        });

        attendanceList.innerHTML = rows.length
            ? rows.join('')
            : '<tr><td colspan="6" class="text-center text-muted py-5">Tidak ada personel yang sesuai pencarian.</td></tr>';

        const presentCount = [...filteredUserIds].filter(userId => presenceByUser.has(userId)).length;
        document.getElementById('monitor-present').textContent = presentCount;
        document.getElementById('monitor-absent').textContent = Math.max(totalUsers - presentCount, 0);
    };

    const changePage = async target => {
        if (changingPage) return;
        changingPage = true;
        try {
            const response = await fetch(target, { headers: { 'Accept': 'text/html' } });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            const page = new DOMParser().parseFromString(await response.text(), 'text/html');
            const dataElement = page.getElementById('monitor-page-data');
            if (!dataElement) throw new Error('Data monitor tidak tersedia');
            const nextData = JSON.parse(dataElement.textContent);
            const footer = page.getElementById('monitor-pagination');
            if (!footer) throw new Error('Pagination tidak tersedia');
            document.getElementById('monitor-pagination').replaceWith(footer);
            pageData = nextData;
            users = pageData.users;
            filteredUserIds = new Set(pageData.userIds);
            totalUsers = pageData.total;
            firstRowNumber = pageData.first;
            document.getElementById('monitor-total').textContent = totalUsers;
            history.replaceState(null, '', target);
            drawAttendance(lastPresences);
        } catch (error) {
            statusText.textContent = 'Gagal mengganti halaman, mencoba kembali...';
        } finally {
            changingPage = false;
        }
    };

    document.getElementById('attendance-monitor').addEventListener('click', event => {
        const link = event.target.closest('#monitor-pagination a');
        if (!link) return;
        event.preventDefault();
        if (!link.closest('.disabled')) changePage(link.href);
    });

    document.getElementById('monitor-filter')?.addEventListener('submit', event => {
        event.preventDefault();
        const form = event.currentTarget;
        const target = new URL(form.action);
        target.search = new URLSearchParams(new FormData(form)).toString();
        changePage(target.href);
    });

    window.setInterval(() => {
        if (document.fullscreenElement?.id !== 'monitor-page' || document.hidden || pageData.lastPage <= 1) return;
        const target = new URL(window.location.href);
        target.searchParams.set('page[number]', pageData.currentPage >= pageData.lastPage ? 1 : pageData.currentPage + 1);
        changePage(target.href);
    }, 5000);

    const loadAttendance = async () => {
        if (polling || document.hidden) return;
        polling = true;

        try {
            const response = await fetch(`${url}/api/absensi/show_today_presence`, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token }
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const result = await response.json();
            drawAttendance(result.data?.today_presence || []);
            statusText.textContent = 'Data live aktif';
            statusDot.className = 'status-dot status-dot-animated bg-green me-2';
            updatedText.textContent = `Diperbarui ${new Intl.DateTimeFormat('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).format(new Date())}`;
        } catch (error) {
            statusText.textContent = 'Gagal memperbarui data, mencoba kembali...';
            statusDot.className = 'status-dot bg-red me-2';
        } finally {
            polling = false;
        }
    };

    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) loadAttendance();
    });

    loadAttendance();
    window.setInterval(loadAttendance, 10000);
});
</script>
@endpush
