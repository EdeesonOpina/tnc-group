<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Auth;
use Mail;
use Validator;
use App\Models\Expense;
use App\Models\ExpenseStatus;
use App\Models\ExpenseCategory;
use App\Models\ExpenseCategoryStatus;

class ExpenseController extends Controller
{
    public function show()
    {
        $period = now()->firstOfYear()->monthsUntil(now()->lastOfYear());

        $data = [];
        foreach ($period as $date)
        {
           $data[] = [
               'month' => $date->format('F'),
               'year' => $date->year,
           ];
        }

        $period = $data;

        return view('admin.reports.expenses.show', compact(
            'period'
        ));
    }

    public function search(Request $request)
    {
        $from_date = $request->input('from_date') ?? '*';
        $to_date = $request->input('to_date') ?? '*';
        $user_id = $request->input('user_id') ?? '*';

        return redirect()->route('admin.reports.expenses.filter', [$from_date, $to_date])->withInput();
    }

    public function filter($from_date, $to_date)
    {
        $from_date = $from_date . ' 00:00:00';
        $to_date = $to_date . ' 23:59:59';

        $expenses = Expense::whereBetween('date', [
            $from_date,$to_date
        ])
        ->where('status', '!=', ExpenseStatus::INACTIVE)
        ->get();

        $grand_total = Expense::whereBetween('date', [
            $from_date,$to_date
        ])
        ->where('status', '!=', ExpenseStatus::INACTIVE)
        ->sum('price');

        $expense_categories = ExpenseCategory::where('status', '!=', ExpenseStatus::INACTIVE)
                                        ->get();

        return view('admin.reports.expenses.view', compact(
            'expense_categories',
            'grand_total',
            'expenses',
            'from_date',
            'to_date'
        ));
    }
}
