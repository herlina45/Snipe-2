<?php

namespace App\Helpers;

use App\Models\TicketCounter;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TicketCounterHelper
{
    public static function generateTicketNumber($departmentId, $requestDate = null)
    {
        // Ambil kode departemen
        $department = Department::findOrFail($departmentId);
        $deptCode = strtoupper(substr($department->code ?? $department->name, 0, 2));

        // Pake tanggal request atau tanggal sekarang
        $date = $requestDate ? Carbon::parse($requestDate) : now();
        $monthYear = $date->format('my');

        // Gunakan transaksi untuk menghindari race condition
        return DB::transaction(function () use ($departmentId, $deptCode, $monthYear) {
            // Cari atau bikin counter, pastikan nggak ambil yang di-soft delete
            $counter = TicketCounter::whereNull('deleted_at')->firstOrCreate(
                [
                    'department_id' => $departmentId,
                    'department_code' => $deptCode,
                    'month_year' => $monthYear,
                ],
                ['counter' => 0]
            );

            // Increment counter
            $counter->increment('counter');
            $counter->refresh();

            // Format nomor urut jadi 3 digit
            $runningNumber = str_pad($counter->counter, 3, '0', STR_PAD_LEFT);

            // Gabungin formatnya
            return "TN{$deptCode}-{$runningNumber}-{$monthYear}";
        });
    }
}