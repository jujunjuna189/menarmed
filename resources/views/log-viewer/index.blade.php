@extends('layouts.app_template')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header justify-content-between">
                <h3 class="card-title">Log Viewer</h3>
                <div>
                    @if($selectedFile)
                    <a href="{{ route('log-viewer.download', ['file' => $selectedFile]) }}" class="btn bg-blue-lt border-dashed">
                        Download
                    </a>
                    @endif
                    <a href="{{ route('log-viewer.index', ['file' => $selectedFile]) }}" class="btn bg-green-lt border-dashed">
                        Refresh
                    </a>
                </div>
            </div>
            <div class="card-body border-bottom py-3">
                <form method="get" action="{{ route('log-viewer.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">File Log</label>
                        <select name="file" class="form-select" onchange="this.form.submit()">
                            @forelse($files as $file)
                            <option value="{{ $file['name'] }}" @if($file['name'] === $selectedFile) selected @endif>
                                {{ $file['name'] }} - {{ $file['size'] }}
                            </option>
                            @empty
                            <option value="">Belum ada file log</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-4">
                        @if($selectedFile)
                        <div class="text-muted">Menampilkan bagian akhir dari {{ $selectedFile }}</div>
                        @endif
                    </div>
                </form>
            </div>
            <div class="card-body p-0">
                <pre class="m-0 p-3 bg-dark text-white" style="min-height: 500px; max-height: 70vh; overflow: auto; white-space: pre-wrap; word-break: break-word;"><code>{{ $content ?: 'Belum ada isi log.' }}</code></pre>
            </div>
        </div>
    </div>
</div>
@endsection
