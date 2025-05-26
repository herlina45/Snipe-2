<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\TicketCounterHelper;

class TicketCounter extends Model
{
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
