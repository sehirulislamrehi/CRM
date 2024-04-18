<?php

namespace App\Models\Backend\TicketingModule\Traits;

use App\Models\Backend\CommonModule\Brand;
use App\Models\Backend\CommonModule\BusinessUnit;
use App\Models\Backend\CommonModule\Thana;
use App\Models\Backend\ProductModule\ProductCategory;
use App\Models\Backend\ProductModule\ProductCategoryNote;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

trait TicketDetailsHelperTrait
{

    function getBuOptionHTML(): string
    {
        $bu = BusinessUnit::all();
        $optionString = '';

        foreach ($bu as $item) {
            $isSelected = $item->id == $this->business_unit_id ? 'selected' : '';
            $optionString .= '<option value="' . $item->id . '" ' . $isSelected . '>' . $item->name . '</option>';
        }

        return $optionString;
    }

    function getBrandOptionHTML(): string
    {
        $productCategoryId = $this->product_category_id;
        $brand_ids = DB::table('brand_product_category')->where('product_category_id', $productCategoryId)->pluck('brand_id');
        $brand = Brand::whereIn('id', $brand_ids)->get();
        $optionString = '';

        foreach ($brand as $item) {
            $isSelected = $item->id == $this->brand_id ? 'selected' : '';
            $optionString .= '<option value="' . $item->id . '" ' . $isSelected . '>' . $item->name . '</option>';
        }

        return $optionString;
    }

    function productCategoryOptionHTML(): string
    {
        $brand = Brand::find($this->brand_id);

        if ($brand) {
            $productCategories = $brand->product_category;

            $optionString = '';

            foreach ($productCategories as $item) {
                $isSelected = $item->id == $this->product_category_id ? 'selected' : '';
                $optionString .= '<option value="' . $item->id . '" ' . $isSelected . '>' . $item->name . '</option>';
            }

            return $optionString;
        }

        return '';
    }

    function problemOptionHTML(): string
    {
        $lang_code = App::getLocale();
        $productCategory = ProductCategory::find($this->product_category_id);
        $selectedProblem = DB::table('ticket_detail_problems')->where('ticket_details_id', $this->id)->pluck('product_category_problem_id')->toArray();
        $optionString = '';
        if ($productCategory) {
            if ($lang_code === 'bn') {
                $productCategoryProblem = $productCategory->product_category_problem()
                    ->select('id', 'name_bn as name', 'product_category_id')
                    ->get();
            } else {
                $productCategoryProblem = $productCategory->product_category_problem;
            }

            foreach ($productCategoryProblem as $item) {
                $isSelected = in_array($item->id, $selectedProblem) ? 'selected' : '';
                $optionString .= '<option value="' . $item->id . '" ' . $isSelected . '>' . $item->name . '</option>';
            }
        }
        return $optionString;
    }

    function categoryNoteHTML(): string
    {
        $optionString = '<h6>NB:</h6><ul>';
        $notes = ProductCategoryNote::where('product_category_id', $this->product_category_id)->get();

        foreach ($notes as $item) {
            $optionString .= '<li>' . $item->note . '</li>';
        }

        $optionString .= '</ul>';
        return $optionString;
    }


    function selectedServiceCenterOption($customer_thana_id): string
    {
        $thana = Thana::find($customer_thana_id);
        $optionString = '';
        if ($thana) {
            $serviceCenters = $thana->service_center;
            foreach ($serviceCenters as $item) {
                $isSelected = $item->id == $this->service_center_id ? 'selected' : '';
                $optionString .= '<option value="' . $item->id . '" ' . $isSelected . '>' . $item->name . '</option>';
            }
        }
        return $optionString;

    }

}
