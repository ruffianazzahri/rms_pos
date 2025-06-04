<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\CustomerVip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class CustomerVipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $row = (int) request('row', 10);
        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        return view('customers_vip.index', [
            'customers' => CustomerVip::filter(request(['search']))->sortable()->paginate($row)->appends(request()->query()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers_vip.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:50',
            'phone' => 'required|string|max:15|unique:customers,phone',
            'city' => 'required|string|max:50',
            'address' => 'required|string|max:100',
            'uid' => 'required|string|max:100|unique:customers_vip,uid',
        ];

        $validatedData = $request->validate($rules);

        CustomerVip::create($validatedData);

        return Redirect::route('customers_vip.index')->with('success', 'Customer has been created!');
    }


    /**
     * Display the specified resource.
     */
    public function show(CustomerVip $customer)
    {
        return view('customers_vip.show', [
            'customer' => $customer,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerVip $customers_vip)
    {
        return view('customers_vip.edit', [
            'customer' => $customers_vip,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerVip $customer)
    {

        //dd($customer);

        $rules = [
            'photo' => 'nullable|image|file|max:1024',
            'name' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:50',
            'address' => 'required|string|max:100',
            'uid' => 'required|string|max:100|unique:customers_vip,uid,' . $customer->id,
        ];

        $validatedData = $request->validate($rules);

        // Debug isi validated data
        //dd($validatedData);

        $customer->update($validatedData);

        return redirect()->route('customers_vip.index')->with('success', 'Customer berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customer = CustomerVip::find($id);

        if (!$customer) {
            return Redirect::route('customers_vip.index')->with('error', 'Customer tidak ditemukan!');
        }

        $deleted = $customer->delete();

        if ($deleted) {
            return Redirect::route('customers_vip.index')->with('success', 'Customer berhasil dihapus!');
        } else {
            return Redirect::route('customers_vip.index')->with('error', 'Gagal menghapus customer!');
        }
    }
}
