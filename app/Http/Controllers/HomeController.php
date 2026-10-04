<?php

namespace App\Http\Controllers;

use App\Models\AbsensiModel;
use App\Models\PerizinanKendaraanModel;
use App\Models\PerizinanModel;
use App\Models\PerizinanRanpurModel;
use App\Models\RoleModel;
use App\Models\SaranModel;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $today = Carbon::today();
        $now = Carbon::now();
        $personnelCount = User::where('role', '!=', 1)->count();
        $attendanceToday = AbsensiModel::whereDate('created_at', $today)
            ->whereHas('userModel', function ($query) {
                $query->where('role', '!=', 1);
            })
            ->distinct('user_id')
            ->count('user_id');

        $activePermitQueries = [
            'Personel' => PerizinanModel::query(),
            'Ranpur' => PerizinanRanpurModel::query(),
            'Angkutan' => PerizinanKendaraanModel::query(),
        ];

        $activePermits = collect($activePermitQueries)->map(function ($query) use ($now) {
            return $query->where('keluar', '<=', $now)
                ->where(function ($permit) use ($now) {
                    $permit->whereNull('masuk')->orWhere('masuk', '>', $now);
                })
                ->count();
        });

        $attendanceTrend = collect(range(6, 0))->map(function ($daysAgo) use ($today) {
            $date = $today->copy()->subDays($daysAgo);

            return [
                'label' => $date->locale('id')->isoFormat('ddd, D MMM'),
                'total' => AbsensiModel::whereDate('created_at', $date)
                    ->whereHas('userModel', function ($query) {
                        $query->where('role', '!=', 1);
                    })
                    ->distinct('user_id')
                    ->count('user_id'),
            ];
        });

        $roleLabels = RoleModel::pluck('role', 'key');
        $personnelByRole = User::where('role', '!=', 1)
            ->selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->orderBy('role')
            ->get()
            ->map(function ($item) use ($roleLabels) {
                return [
                    'label' => $roleLabels->get($item->role, 'Role ' . $item->role),
                    'total' => (int) $item->total,
                ];
            });

        return view('home', [
            'summary' => [
                'personnel' => $personnelCount,
                'attendance_today' => $attendanceToday,
                'attendance_rate' => $personnelCount > 0
                    ? round(($attendanceToday / $personnelCount) * 100)
                    : 0,
                'active_permits' => $activePermits->sum(),
                'suggestions_this_month' => SaranModel::whereBetween('created_at', [
                    $now->copy()->startOfMonth(),
                    $now->copy()->endOfMonth(),
                ])->count(),
            ],
            'activePermits' => $activePermits,
            'attendanceTrend' => $attendanceTrend,
            'personnelByRole' => $personnelByRole,
            'recentAttendances' => AbsensiModel::with('userModel:id,name,pangkat')
                ->latest('created_at')
                ->limit(6)
                ->get(),
            'updatedAt' => $now,
        ]);
    }
}
