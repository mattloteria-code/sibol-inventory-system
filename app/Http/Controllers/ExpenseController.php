<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\IngredientPurchase;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Services\ProfitService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'all');

        [$start, $end] = ProfitService::resolveDateRange($period);
        $rangeLabels = [
            'daily' => 'Today',
            'weekly' => 'This Week',
            'monthly' => 'This Month',
            'yearly' => 'This Year',
            'all' => 'All Time',
        ];
        $rangeLabel = $rangeLabels[$period] ?? 'All Time';

        $expensesQuery = Expense::with('expenseCategory')->whereBetween('expense_date', [$start, $end]);

        $expenses = (clone $expensesQuery)->orderByDesc('expense_date')->paginate(15)->withQueryString();

        $totalForPeriod = (clone $expensesQuery)->sum('amount');

        $ingredientSpendForPeriod = IngredientPurchase::whereBetween('purchase_date', [$start, $end])->sum('total_cost');

        $categories = ExpenseCategory::orderBy('name')->get();

        return view('expenses.index', compact(
            'expenses', 'totalForPeriod', 'period', 'rangeLabel', 'categories', 'ingredientSpendForPeriod'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ExpenseCategory::orderBy('name')->get();

        return view('expenses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request)
    {
        Expense::create($request->validated());

        return redirect()->route('expenses.index')
        ->with('success', 'Expense recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::orderBy('name')->get();

        return view('expenses.edit', compact('expenses', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        $expense->update($request->validated());

        return redirect()->route('expenses.index')
        ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
        ->with('success', 'Expense deleted successfully.');
    }
}
