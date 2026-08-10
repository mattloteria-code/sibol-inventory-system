<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $modelOptions = [
            'App\Models\Product' => 'Product',
            'App\Models\Ingredient' => 'Ingredient',
            'App\Models\ProductIngredient' => 'Recipe Item',
            'App\Models\Order' => 'Order',
            'App\Models\Expense' => 'Expense',
            'App\Models\IngredientPurchase' => 'Ingredient Purchase',
            'App\Models\ProductionBatch' => 'Production Batch',
            'App\Models\Customer' => 'Customer',
        ];

        $logs = AuditLog::with('user')
            ->when($request->model_type, fn ($q, $type) => $q->where('auditable_type', $type))
            ->when($request->action, fn ($q, $action) => $q->where('action', $action))
            ->latest('created_at')
            ->paginate(25)
            ->withQueryString();

            return view('audit-logs.index', compact('logs', 'modelOptions'));
    }        
}
