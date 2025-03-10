<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\CustomerRequest;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ordering = $request->has('order') && $request->order == 'asc' ? 'ASC' : 'DESC';
        //$customers = Customer::all();
        $customers = Customer::when($request->has('search'), function ($query) use ($request){
            $query->where('first_name', 'LIKE', "%$request->search%")
            ->orWhere('last_name', 'LIKE', "%$request->search%")
            ->orWhere('email', 'LIKE', "%$request->search%")
            ->orWhere('phone', 'LIKE', "%$request->search%");
        })->orderBy('id', $ordering )->get();

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
    public function store(CustomerRequest $request)
    {
        //dd($request->all());
        $customer = new Customer();
        if($request->hasFile('image')){
            $image = $request->file('image')->store('', 'dir_public');
            $filePath ='/uploads/'. $image;
            $customer -> image = $filePath;
        }
        $customer -> first_name = $request->first_name;
        $customer -> last_name = $request->last_name;
        $customer -> email = $request->email;
        $customer -> phone = $request->phone;
        $customer -> bank_account_number = $request->bank_account_number;
        $customer -> about = $request->about;
        $customer ->save();
        return redirect()->route('customers.index')->with("success", "customer saved successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer =Customer::findOrFail($id);
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, string $id)
    {
        //dd($request->all());
        $customer = Customer::findOrFail($id); ;
        if($request->hasFile('image')){
            File::delete(public_path($customer->image));
            $image = $request->file('image')->store('', 'dir_public');
            $filePath ='/uploads/'. $image;
            $customer -> image = $filePath;
        }
        $customer -> first_name = $request->first_name;
        $customer -> last_name = $request->last_name;
        $customer -> email = $request->email;
        $customer -> phone = $request->phone;
        $customer -> bank_account_number = $request->bank_account_number;
        $customer -> about = $request->about;
        $customer ->save();
        return redirect()->back()->with("success", "customer updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete the specified resource
        $customer = Customer::findOrFail($id);
        //File::delete(public_path($customer->image));
        $customer->delete();
        return redirect()->route('customers.index')->with("success", "customer deleted successfully");
    }

    /**
     * Show deleted resource in storage
     */
    public function trashBin(Request $request){
        $ordering = $request->has('order') && $request->order == 'asc' ? 'ASC' : 'DESC';

        $customers = Customer::when($request->has('search'), function ($query) use ($request){
            $query->where('first_name', 'LIKE', "%$request->search%")
            ->orWhee('last_name', 'LIKE', "%$request->search%")
            ->orWhere('email', 'LIKE', "%$request->search%")
            ->orWhere('phone', 'LIKE', "%$request->search%");
        })->orderBy('id', $ordering )->onlyTrashed()->get();

        return view('customers.trash', compact('customers'));
    }
    /**
     * Restore a specific deleted resource
     */
    public function restore(int $id){
        $customer = Customer::onlyTrashed()->findOrFail($id);
        $customer ->restore();
        return redirect()->back()->with('success', 'Customer restored successfully');
    }

    /**

    * Restore a specific deleted resource
     */
    public function deletePermanently(int $id){
        $customer = Customer::onlyTrashed()->findOrFail($id);
        File::delete(public_path($customer->image));
        $customer ->forceDelete();
        return redirect()->back()->with('success', 'Customer permanently deleted successfully');
    }

}