<?php
namespace App\Helpers;

use App\Models\AssetCounter;
use Carbon\Carbon;

class AssetTagHelper
{
    public static function generateAssetTag($asset)
    {
        $companyCode = $asset->company->company_code ?? 'WM';
        $companyId = $asset->company_id;

        $departmentCode = $asset->custom_fields['_snipeit_department_2'] ?? 'IT';

        $category = $asset->model->category;
        $categoryCode = $category->category_code ?? '01';
        $categoryId = $category->id ?? null;

        $subCategoryCode = $asset->model->model_number ?? '01';

        $purchaseDate = $asset->purchase_date ?? now();
        $monthYear = Carbon::parse($purchaseDate)->format('my');

        \Log::info('AssetTagHelper dipanggil');
        // Find or create counter
        $counter = AssetCounter::firstOrCreate(
            [
                'company_id' => $companyId,
                'department_code' => $departmentCode,
                'category_id' => $categoryId,
                'sub_category_code' => $subCategoryCode,
                'month_year' => $monthYear,
            ],
            ['counter' => 0]
        );

        $counter->increment('counter');
        $counter->refresh(); // ambil data terbaru dari DB
        $runningCode = str_pad($counter->counter, 3, '0', STR_PAD_LEFT);

        
        return "{$companyCode}{$departmentCode}-{$categoryCode}{$subCategoryCode}-{$runningCode}-{$monthYear}";
    }
}
