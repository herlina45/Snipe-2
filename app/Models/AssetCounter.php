<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetCounter extends Model
{
    protected $table = 'asset_counters';

    protected $fillable = [
        'company_id',
        'department_code',
        'category_id',
        'sub_category_code',
        'month_year',
        'counter',
    ];
}
