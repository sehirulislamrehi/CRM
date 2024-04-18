<?php

namespace App\Services\Backend\ModuleServices\ProductModule\ProductCategory;

use App\Models\Backend\ProductModule\ProductCategoryProblem;
use Illuminate\Support\Facades\DB;

class ProductCategoryProblemService
{
    public function createProductCategoryProblem($request): bool
    {
        $category_id=$request->input('pc_id');
        $problem_en=$request->input('problem_en');
        $problem_bn=$request->input('problem_bn');
        $product_problems=[];
        foreach ($problem_en as $key => $value) {
            $product_problems[] = [
                'product_category_id'=>$category_id,
                'name' => $value,
                'name_bn'=>$problem_bn[$key],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return DB::table('product_category_problems')->insert($product_problems);

    }
}
