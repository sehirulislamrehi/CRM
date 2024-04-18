<?php

namespace App\Services\Backend\ModuleServices\ProductModule\ProductCategory;

use App\Models\Backend\ProductModule\ProductCategoryNote;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * @author Alimul Mahfuz Tushar <automation33@mis.prangroup.com>
 * @copyright MIS RFL
 */
class ProductCategoryNoteService
{
    public function createNote($request): bool
    {
        $note_data=[];
        foreach ($request['note'] as $note_item){
            $note_data[]=[
                'note'=>$note_item,
                'is_active'=>true,
                'product_category_id'=>$request['pc_id'],
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ];
        }
        return DB::table('product_category_notes')->insert($note_data);
    }

    public function updateNote($data, $id): bool
    {
        $note = ProductCategoryNote::find($id);
        if ($note) {
            $note->note = $data->input('note');
            $note->is_active = $data->input('is_active');
            $note->save();
            return true;
        }
        return false;
    }
}
