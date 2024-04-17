<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SubCategoryRequest;
use App\Models\SubCategory;

class SubCategoriesController extends Controller
{
    public function store(SubCategoryRequest $request)
    {
        SubCategory::create([
            'main_category_id' => $request->input('main_category_id'), // メインカテゴリーIDを取得
            'sub_category' => $request->input('sub_category'),
        ]);

        return redirect()->back()->with('success', 'サブカテゴリーが登録されました');
    }
}
