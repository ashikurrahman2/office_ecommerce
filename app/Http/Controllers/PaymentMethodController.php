<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    // Display paginated list ---------------------------------------------------
    public function index()
    {
        $paymentMethods = PaymentMethod::latest()->paginate(10);
        return view('backend.payment-methods.index', compact('paymentMethods'));
    }

    // Show create form ---------------------------------------------------------
    public function create()
    {
        return view('backend.payment-methods.create');
    }

    // Store new method ---------------------------------------------------------
    public function store(Request $request)
    {
        $data = $request->validate($this->rules($request->type));

        
        PaymentMethod::create($data);

        return redirect()->route('payment-methods.index')->with('success', 'Payment method saved');
    }

    // Edit form ---------------------------------------------------------------
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('backend.payment-methods.edit', compact('paymentMethod'));
    }

    // Update existing ----------------------------------------------------------
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $data = $request->validate($this->rules($request->type));

       

        $paymentMethod->update($data);
        return redirect()->route('payment-methods.index')->with('success', 'Payment method updated');
    }

    // Delete -------------------------------------------------------------------
    public function destroy(PaymentMethod $paymentMethod)
    {
        optional($paymentMethod->image_path, function ($path) { \Storage::disk('public')->delete($path); });
        $paymentMethod->delete();
        return redirect()->route('payment-methods.index')->with('success', 'Payment method deleted');
    }

    // -------------------------------------------------------------------------
    private function rules(string $type): array
    {
        return [
            'type'           => 'required|in:bank,mobile',
            'bank_name'      => ($type === 'bank')   ? 'required|max:191' : 'nullable',
            'account_name'   => ($type === 'bank')   ? 'required|max:191' : 'nullable',
            'account_number' => 'required|max:191',
            'branch'         => 'nullable|max:191',
            'routing_no'     => 'nullable|max:50',
            'image_path'          =>  'required|max:191',
        ];
    }
}