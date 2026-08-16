<?php

return [

    // Fields to hide entirely from the audit log display, per model.
    'hidden_fields' => [
        'default' => ['id', 'created_at', 'updated_at'],

        \App\Models\Product::class => ['is_active'],
        \App\Models\Order::class => ['order_number'],
    ],

    // Friendly labels for technical field names, per model.
    // Falls back to a humanized version of the field name if not listed.
    'field_labels' => [
        \App\Models\Product::class => [
            'category_id' => 'Category',
            'selling_price' => 'Selling Price',
            'stock_quantity' => 'Stock Quantity',
            'low_stock_threshold' => 'Low Stock Alert Level',
        ],
        \App\Models\Ingredient::class => [
            'current_stock' => 'Current Stock',
            'current_price_per_base_unit' => 'Price per Unit',
            'low_stock_threshold' => 'Low Stock Alert Level',
            'preferred_unit' => 'Preferred Unit',
            'unit_type' => 'Unit Type',
        ],
        \App\Models\Order::class => [
            'customer_id' => 'Customer',
            'total_amount' => 'Total Amount',
        ],
        \App\Models\Expense::class => [
            'expense_category_id' => 'Category',
            'expense_date' => 'Date',
        ],
        \App\Models\Customer::class => [
            'phone' => 'Phone Number',
        ],
        \App\Models\IngredientPurchase::class => [
            'ingredient_id' => 'Ingredient',
            'purchase_unit_quantity' => 'Quantity',
            'purchase_unit' => 'Unit',
            'total_cost' => 'Total Price Paid',
            'purchase_date' => 'Date',
        ],
        \App\Models\ProductionBatch::class => [
            'product_id' => 'Product',
            'quantity_produced' => 'Quantity Produced',
            'total_cost' => 'Total Cost',
            'cost_per_unit' => 'Cost per Unit',
            'produced_at' => 'Date Produced',
        ],
        \App\Models\ProductIngredient::class => [
            'product_id' => 'Product',
            'ingredient_id' => 'Ingredient',
            'quantity_required' => 'Quantity Required',
        ],
    ],

    // Which field represents this model's "name" for created/deleted summaries.
    'display_field' => [
        'default' => 'name',

        \App\Models\Order::class => 'order_number',
        \App\Models\Expense::class => 'title',
        \App\Models\ProductionBatch::class => 'product_id',
        \App\Models\IngredientPurchase::class => 'ingredient_id',
        \App\Models\ProductIngredient::class => 'ingredient_id',
    ],

    // Foreign key fields that should be resolved to a related record's name,
    // instead of showing the raw ID. Format: field => [RelatedModel::class, 'display_attribute']
    'resolvable_fields' => [
        \App\Models\Product::class => [
            'category_id' => [\App\Models\Category::class, 'name'],
        ],
        \App\Models\Order::class => [
            'customer_id' => [\App\Models\Customer::class, 'name'],
        ],
        \App\Models\Expense::class => [
            'expense_category_id' => [\App\Models\ExpenseCategory::class, 'name'],
        ],
        \App\Models\IngredientPurchase::class => [
            'ingredient_id' => [\App\Models\Ingredient::class, 'name'],
        ],
        \App\Models\ProductionBatch::class => [
            'product_id' => [\App\Models\Product::class, 'name'],
        ],
        \App\Models\ProductIngredient::class => [
            'product_id' => [\App\Models\Product::class, 'name'],
            'ingredient_id' => [\App\Models\Ingredient::class, 'name'],
        ],
    ],

];