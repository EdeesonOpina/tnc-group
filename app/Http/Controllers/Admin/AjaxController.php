<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SubCategory;
use App\Models\ExpenseCompany;
use App\Models\SubCategoryStatus;
use App\Models\ExpenseCompanyStatus;
use App\Models\ProjectSubCategory;
use App\Models\ProjectSubCategoryStatus;

class AjaxController extends Controller
{
    public function sub_categories(Request $request)
    {
        $sub_categories = SubCategory::select('id', 'name')
                                ->where('category_id', $request->category_id)
                                ->where('status', SubCategoryStatus::ACTIVE)
                                ->orderBy('name', 'asc')
                                ->get();

        return response()->json($sub_categories);
    }

    public function companies(Request $request)
    {
        $companies = ExpenseCompany::select('id', 'name')
                                ->where('category_id', $request->category_id)
                                ->where('status', ExpenseCompanyStatus::ACTIVE)
                                ->orderBy('name', 'asc')
                                ->get();

        return response()->json($companies);
    }

    public function project_sub_categories(Request $request)
    {
        $sub_categories = ProjectSubCategory::select('id', 'name')
                                ->where('category_id', $request->category_id)
                                ->where('status', ProjectSubCategoryStatus::ACTIVE)
                                ->orderBy('name', 'asc')
                                ->get();

        return response()->json($sub_categories);
    }

    public function autocompleteSearch(Request $request)
    {
        $query = $request->get('query');
        $filterResult = User::select('firstname')->where('firstname', 'LIKE', '%'. $query . '%')->get();
        return response()->json($filterResult);
    } 
}
