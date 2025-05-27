<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\TicketCounterHelper;

class TicketCounter extends Model
{
    use SoftDeletes; // Tambah ini

    protected $fillable = [
        'department_id',
        'department_code',
        'month_year',
        'counter',
    ];

    public static function generateTicketNumber($departmentId, $requestDate = null)
    {
        return TicketCounterHelper::generateTicketNumber($departmentId, $requestDate);
    }
}