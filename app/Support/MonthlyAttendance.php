<?php

namespace App\Support;

use App\Models\AbsensiModel;
use App\Models\User;
use Carbon\Carbon;

class MonthlyAttendance
{
    public static function people(string $search)
    {
        return User::where('role', '!=', 1)->when($search !== '', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->orderBy('name')->orderBy('id');
    }

    public static function records(Carbon $month, $ids)
    {
        return AbsensiModel::personnel()->whereIn('user_id', $ids)
            ->where('created_at', '>=', $month->copy()->startOfMonth())
            ->where('created_at', '<', $month->copy()->startOfMonth()->addMonth())
            ->orderBy('created_at')->orderBy('id')->get()
            ->groupBy(function ($record) { return $record->user_id . ':' . $record->created_at->format('j'); });
    }

    public static function code($status): string
    {
        $status = trim((string) $status);
        $codes = ['hadir' => 'H', 'izin' => 'I', 'ijin' => 'I', 'sakit' => 'S'];
        return $codes[mb_strtolower($status)] ?? ($status !== '' ? mb_strtoupper($status) : '?');
    }
}
