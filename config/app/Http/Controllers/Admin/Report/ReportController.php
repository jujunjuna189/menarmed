<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\AbsensiModel;
use App\Models\GudangSenjataModel;
use App\Models\LogistikModel;
use App\Models\PerizinanKendaraanModel;
use App\Models\PerizinanModel;
use App\Models\PerizinanRanpurModel;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ReportController extends Controller
{
    public function absensi(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);
        $pageSize = $request->input('page.size', 10);

        $query = AbsensiModel::with('userModel')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereHas('userModel', function($q) use ($search) {
                if (isset($search['name'])) {
                    $q->where('name', 'like', '%' . $search['name'] . '%');
                }
            })
            ->orderBy('created_at', 'desc');

        $data['report'] = QueryBuilder::for($query)
            ->paginate($pageSize)->appends($request->input());
            
        $data['no'] = 0;
        $data['controller'] = $this;
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        $data['page_size'] = $pageSize;
        $data['search_name'] = $search['name'] ?? '';

        $data['summaryData'] = \App\Models\AbsensiModel::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereHas('userModel', function($q) use ($search) {
                if (isset($search['name'])) {
                    $q->where('name', 'like', '%' . $search['name'] . '%');
                }
            })
            ->select('ket', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('ket')
            ->get();

        return view('rekap_data.absensi', $data);
    }

    public function updateAbsensi(Request $request, $id)
    {
        $request->validate([
            'ket' => 'required|string|max:255',
            'created_at' => 'required|date',
        ]);

        $absensi = AbsensiModel::findOrFail($id);
        $absensi->update([
            'ket' => $request->ket,
            'created_at' => $request->created_at,
        ]);

        return redirect()->back()->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function exportAbsensi(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AbsensiExport($startDate, $endDate, $search), 'rekap_absensi_' . $startDate . '_to_' . $endDate . '.xlsx');
    }

    public function exportAbsensiPdf(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);

        $query = AbsensiModel::with('userModel')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereHas('userModel', function($q) use ($search) {
                if (isset($search['name'])) {
                    $q->where('name', 'like', '%' . $search['name'] . '%');
                }
            })
            ->orderBy('id', 'desc');

        $data['report'] = $query->get();
            
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('rekap_data.absensi_pdf', $data);
        return $pdf->download('rekap_absensi_' . $startDate . '_to_' . $endDate . '.pdf');
    }

    public function perizinan(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);
        $pageSize = $request->input('page.size', 10);

        $query = PerizinanModel::with('userModel')
            ->whereBetween('keluar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereHas('userModel', function($q) use ($search) {
                if (isset($search['name'])) {
                    $q->where('name', 'like', '%' . $search['name'] . '%');
                }
            })
            ->orderBy('keluar', 'desc');

        $data['report'] = QueryBuilder::for($query)
            ->paginate($pageSize)->appends($request->input());
            
        $data['no'] = 1;
        $data['controller'] = $this;
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        $data['page_size'] = $pageSize;
        $data['search_name'] = $search['name'] ?? '';
        
        return view('rekap_data.perizinan', $data);
    }

    public function updatePerizinan(Request $request, $id)
    {
        $request->validate([
            'tujuan' => 'required|string|max:255',
            'jenis_kendaraan' => 'required|string|max:255',
            'keluar' => 'required|date',
            'masuk' => 'required|date',
        ]);

        $perizinan = PerizinanModel::findOrFail($id);
        $perizinan->update([
            'tujuan' => $request->tujuan,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'keluar' => $request->keluar,
            'masuk' => $request->masuk,
        ]);

        return redirect()->back()->with('success', 'Data perizinan berhasil diperbarui.');
    }

    public function exportPerizinan(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PerizinanExport($startDate, $endDate, $search), 'rekap_perizinan_' . $startDate . '_to_' . $endDate . '.xlsx');
    }

    public function exportPerizinanPdf(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);

        $query = PerizinanModel::with('userModel')
            ->whereBetween('keluar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereHas('userModel', function($q) use ($search) {
                if (isset($search['name'])) {
                    $q->where('name', 'like', '%' . $search['name'] . '%');
                }
            })
            ->orderBy('id', 'desc');

        $data['report'] = $query->get();
            
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('rekap_data.perizinan_pdf', $data);
        return $pdf->download('rekap_perizinan_' . $startDate . '_to_' . $endDate . '.pdf');
    }

    public function ranpur(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);
        $pageSize = $request->input('page.size', 10);

        $query = PerizinanRanpurModel::with('userModel')
            ->whereBetween('keluar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereHas('userModel', function($q) use ($search) {
                if (isset($search['name'])) {
                    $q->where('name', 'like', '%' . $search['name'] . '%');
                }
            })
            ->orderBy('keluar', 'desc');

        $data['report'] = QueryBuilder::for($query)
            ->paginate($pageSize)->appends($request->input());
            
        $data['no'] = 0;
        $data['controller'] = $this;
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        $data['page_size'] = $pageSize;
        $data['search_name'] = $search['name'] ?? '';

        return view('rekap_data.ranpur', $data);
    }

    public function updateRanpur(Request $request, $id)
    {
        $request->validate([
            'tujuan' => 'required|string|max:255',
            'jenis_kendaraan' => 'required|string|max:255',
            'keluar' => 'nullable|date',
            'masuk' => 'nullable|date',
        ]);

        $ranpur = PerizinanRanpurModel::findOrFail($id);
        $ranpur->update([
            'tujuan' => $request->tujuan,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'keluar' => $request->keluar,
            'masuk' => $request->masuk,
        ]);

        return redirect()->back()->with('success', 'Data ranpur berhasil diperbarui.');
    }

    public function exportRanpur(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\RanpurExport($startDate, $endDate, $search), 'rekap_ranpur_' . $startDate . '_to_' . $endDate . '.xlsx');
    }

    public function exportRanpurPdf(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);

        $query = PerizinanRanpurModel::with('userModel')
            ->whereBetween('keluar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereHas('userModel', function($q) use ($search) {
                if (isset($search['name'])) {
                    $q->where('name', 'like', '%' . $search['name'] . '%');
                }
            })
            ->orderBy('keluar', 'desc');

        $data['report'] = $query->get();
            
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('rekap_data.ranpur_pdf', $data);
        return $pdf->download('rekap_ranpur_' . $startDate . '_to_' . $endDate . '.pdf');
    }

    public function kendaraan(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);
        $pageSize = $request->input('page.size', 10);

        $query = PerizinanKendaraanModel::with('userModel')
            ->whereBetween('keluar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereHas('userModel', function($q) use ($search) {
                if (isset($search['name'])) {
                    $q->where('name', 'like', '%' . $search['name'] . '%');
                }
            })
            ->orderBy('keluar', 'desc');

        $data['report'] = QueryBuilder::for($query)
            ->paginate($pageSize)->appends($request->input());
            
        $data['no'] = 0;
        $data['controller'] = $this;
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        $data['page_size'] = $pageSize;
        $data['search_name'] = $search['name'] ?? '';

        return view('rekap_data.kendaraan', $data);
    }

    public function updateKendaraan(Request $request, $id)
    {
        $request->validate([
            'tujuan' => 'required|string|max:255',
            'jenis_kendaraan' => 'required|string|max:255',
            'keluar' => 'nullable|date',
            'masuk' => 'nullable|date',
        ]);

        $kendaraan = PerizinanKendaraanModel::findOrFail($id);
        $kendaraan->update([
            'tujuan' => $request->tujuan,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'keluar' => $request->keluar,
            'masuk' => $request->masuk,
        ]);

        return redirect()->back()->with('success', 'Data angkutan berhasil diperbarui.');
    }

    public function exportKendaraan(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PerizinanKendaraanExport($startDate, $endDate, $search), 'rekap_angkutan_' . $startDate . '_to_' . $endDate . '.xlsx');
    }

    public function exportKendaraanPdf(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);

        $query = PerizinanKendaraanModel::with('userModel')
            ->whereBetween('keluar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereHas('userModel', function($q) use ($search) {
                if (isset($search['name'])) {
                    $q->where('name', 'like', '%' . $search['name'] . '%');
                }
            })
            ->orderBy('keluar', 'desc');

        $data['report'] = $query->get();
            
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('rekap_data.kendaraan_pdf', $data);
        return $pdf->download('rekap_angkutan_' . $startDate . '_to_' . $endDate . '.pdf');
    }


    public function gudang_senjata(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);
        $pageSize = $request->input('page.size', 10);

        $query = GudangSenjataModel::with('userModel')
            ->whereBetween('keluar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereHas('userModel', function($q) use ($search) {
                if (isset($search['name'])) {
                    $q->where('name', 'like', '%' . $search['name'] . '%');
                }
            })
            ->orderBy('keluar', 'desc');

        $data['report'] = QueryBuilder::for($query)
            ->paginate($pageSize)->appends($request->input());
            
        $data['no'] = 0;
        $data['controller'] = $this;
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        $data['page_size'] = $pageSize;
        $data['search_name'] = $search['name'] ?? '';

        return view('rekap_data.gudang_senjata', $data);
    }

    public function updateGudangSenjata(Request $request, $id)
    {
        $request->validate([
            'batrai_keluar' => 'nullable|string|max:255',
            'batrai_masuk' => 'nullable|string|max:255',
            'keluar' => 'nullable|date',
            'masuk' => 'nullable|date',
        ]);

        $gudang = GudangSenjataModel::findOrFail($id);
        $gudang->update([
            'batrai_keluar' => $request->batrai_keluar,
            'batrai_masuk' => $request->batrai_masuk,
            'keluar' => $request->keluar,
            'masuk' => $request->masuk,
        ]);

        return redirect()->back()->with('success', 'Data gudang senjata berhasil diperbarui.');
    }

    public function exportGudangSenjata(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\GudangSenjataExport($startDate, $endDate, $search), 'rekap_gudang_senjata_' . $startDate . '_to_' . $endDate . '.xlsx');
    }

    public function exportGudangSenjataPdf(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);

        $query = GudangSenjataModel::with('userModel')
            ->whereBetween('keluar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereHas('userModel', function($q) use ($search) {
                if (isset($search['name'])) {
                    $q->where('name', 'like', '%' . $search['name'] . '%');
                }
            })
            ->orderBy('keluar', 'desc');

        $data['report'] = $query->get();
            
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('rekap_data.gudang_senjata_pdf', $data);
        return $pdf->download('rekap_gudang_senjata_' . $startDate . '_to_' . $endDate . '.pdf');
    }

    public function logistik(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);
        $pageSize = $request->input('page.size', 10);

        $query = LogistikModel::with('userModel')
            ->whereBetween('keluar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereHas('userModel', function($q) use ($search) {
                if (isset($search['name'])) {
                    $q->where('name', 'like', '%' . $search['name'] . '%');
                }
            })
            ->orderBy('keluar', 'desc');

        $data['report'] = QueryBuilder::for($query)
            ->paginate($pageSize)->appends($request->input());
            
        $data['no'] = 0;
        $data['controller'] = $this;
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        $data['page_size'] = $pageSize;
        $data['search_name'] = $search['name'] ?? '';

        return view('rekap_data.logistik', $data);
    }

    public function updateLogistik(Request $request, $id)
    {
        $request->validate([
            'keluar' => 'nullable|date',
            'masuk' => 'nullable|date',
        ]);

        $logistik = LogistikModel::findOrFail($id);
        $logistik->update([
            'keluar' => $request->keluar,
            'masuk' => $request->masuk,
        ]);

        return redirect()->back()->with('success', 'Data logistik berhasil diperbarui.');
    }

    public function exportLogistik(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LogistikExport($startDate, $endDate, $search), 'rekap_logistik_' . $startDate . '_to_' . $endDate . '.xlsx');
    }

    public function exportLogistikPdf(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $search = $request->get('filter', []);

        $query = LogistikModel::with('userModel')
            ->whereBetween('keluar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereHas('userModel', function($q) use ($search) {
                if (isset($search['name'])) {
                    $q->where('name', 'like', '%' . $search['name'] . '%');
                }
            })
            ->orderBy('keluar', 'desc');

        $data['report'] = $query->get();
            
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('rekap_data.logistik_pdf', $data);
        return $pdf->download('rekap_logistik_' . $startDate . '_to_' . $endDate . '.pdf');
    }
}
