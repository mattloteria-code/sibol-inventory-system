<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customers = Customer::withCount('orders')
        ->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        })
        ->orderBy('name')->paginate(10)->withQueryString();

        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        Customer::create($request->validated());

        return redirect()->route('customers.index')
        ->with('success', 'Customer added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $orders = $customer->orders()->latest()->paginate(10);

        return view('customers.show', compact('customer', 'orders'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return redirect()->route('customers.index')

        ->with('success', 'Customer updated successully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        if ($customer->orders()->exists()){
            return redirect()->route('customers.index')

            ->with('error', 'Cannot delete a customer with existing orders.');
        }

        $customer->delete();

        return redirect()->route('customers.index')
        
        ->with('success', 'Customer Deleted successfully.');
    }
}
