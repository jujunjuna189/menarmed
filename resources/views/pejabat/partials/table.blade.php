<div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap mb-0">
        <thead>
            <tr>
                <th class="w-1">No.</th>
                <th>Nama</th>
                <th>Pangkat</th>
                <th>NRP</th>
                <th>Jabatan</th>
                <th>Dibuat</th>
                <th class="w-1 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $key => $pejabat)
            <tr>
                <td>{{ $records->firstItem() + $key }}</td>
                <td class="fw-bold">{{ $pejabat->nama ?: '-' }}</td>
                <td>{{ $pejabat->pangkat ?: '-' }}</td>
                <td>{{ $pejabat->nrp ?: '-' }}</td>
                <td>
                    @if($pejabat->jabatan)
                        <div class="pejabat-jabatan-preview">
                            <span>{{ \Illuminate\Support\Str::limit($pejabat->jabatan, 40) }}</span>
                            @if(\Illuminate\Support\Str::length($pejabat->jabatan) > 40)
                        <button type="button" class="pejabat-view" data-name="{{ $pejabat->nama }}" data-jabatan="{{ $pejabat->jabatan }}" title="Lihat jabatan lengkap" aria-label="Lihat jabatan lengkap {{ $pejabat->nama }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="2"/><path d="M2 12c5-9 15-9 20 0-5 9-15 9-20 0"/></svg>
                        </button>
                            @endif
                        </div>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    @if($pejabat->created_at && $pejabat->created_at->copy()->addDays(2)->isFuture())
                    <span class="badge bg-success-lt">Baru</span>
                    @elseif($pejabat->created_at)
                    <time class="text-muted" datetime="{{ $pejabat->created_at->toIso8601String() }}" title="{{ $pejabat->created_at->locale('id')->translatedFormat('d F Y H:i') }}">{{ $pejabat->created_at->locale('id')->diffForHumans() }}</time>
                    @else
                    <span class="text-muted small">Tanggal tidak tercatat</span>
                    @endif
                </td>
                <td class="text-center">
                    <div class="dropdown">
                        <button type="button" class="btn btn-icon pejabat-action" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi pejabat" aria-label="Aksi pejabat {{ $pejabat->nama }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/></svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end pejabat-menu">
                            <button type="button" class="dropdown-item" onclick="updatePejabat({{ $pejabat->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m16 3 5 5-12 12H4v-5L16 3m-2 2 5 5"/></svg>
                                Edit
                            </button>
                            <div class="dropdown-divider"></div>
                            <button type="button" class="dropdown-item text-danger" onclick="deletePejabat({{ $pejabat->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M9 6V4h6v2M5 6l1 14h12l1-14M10 10v6m4-6v6"/></svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-4">Data {{ $label }} belum tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="card-footer d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
    <p class="m-0 text-muted">
        Menampilkan {{ $records->firstItem() ?? 0 }} sampai {{ $records->lastItem() ?? 0 }}
        dari {{ $records->total() }} data
    </p>
    <ul class="pagination m-0 ms-auto">
        <li class="page-item {{ $records->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $records->appends(['tab' => $type])->url(max(1, $records->currentPage() - 1)) }}" aria-label="Sebelumnya">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 6-6 6 6 6"/></svg>Sebelumnya
            </a>
        </li>
        @for($number = max(1, $records->currentPage() - 2); $number <= min($records->lastPage(), max(5, $records->currentPage() + 2)); $number++)
            <li class="page-item {{ $number === $records->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $records->url($number) }}">{{ $number }}</a></li>
        @endfor
        <li class="page-item {{ $records->hasMorePages() ? '' : 'disabled' }}">
            <a class="page-link" href="{{ $records->url(min($records->lastPage(), $records->currentPage() + 1)) }}" aria-label="Berikutnya">
                Berikutnya<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 6 6 6-6 6"/></svg>
            </a>
        </li>
    </ul>
</div>
