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
            'phone' => 'required|string|max:15|unique:customers_vip,phone',
            'city' => 'required|string|max:50',
            'address' => 'required|string|max:100',
            'uid' => 'required|string|max:100|unique:customers_vip,uid',
            'balance' => 'required|numeric|min:0', // added
        ];

        $validatedData = $request->validate($rules);

        CustomerVip::create($validatedData);

        return Redirect::route('customers_vip.index')->with('success', 'Customer has been created!');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $customer = CustomerVip::find($id);
        if (!$customer) {
            abort(404);
        }
        return view('customers_vip.show', compact('customer'));
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
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, CustomerVip $customers_vip)
    {
        $rules = [
            'name' => 'required|string|max:50',
            'phone' => 'required|string|max:20|unique:customers_vip,phone,' . $customers_vip->id,
            'city' => 'required|string|max:50',
            'address' => 'required|string|max:100',
            'balance' => 'required|numeric|min:0',
        ];

        // Tambahkan validasi uid_new jika diisi
        if ($request->filled('uid_new')) {
            $rules['uid_new'] = 'string|max:100|unique:customers_vip,uid,' . $customers_vip->id;
        }

        $validated = $request->validate($rules);

        $customers_vip->name = $validated['name'];
        $customers_vip->phone = $validated['phone'];
        $customers_vip->city = $validated['city'];
        $customers_vip->address = $validated['address'];
        $customers_vip->balance = $validated['balance'];

        // Ganti UID jika user mengisi uid baru
        if ($request->filled('uid_new')) {
            $customers_vip->uid = $validated['uid_new'];
        }

        $customers_vip->save();

        return redirect()->route('customers_vip.index')->with('success', 'Customer updated successfully!');
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

    // app/Http/Controllers/CustomerVipController.php

    public function scan()
    {
        $uid = request()->get('uid'); // ambil dari query string

        $customer = CustomerVip::where('uid', $uid)->first();

        if ($customer) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $customer->id,
                    'nama' => $customer->name,
                    'saldo' => $customer->balance,
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'UID tidak ditemukan.'
            ]);
        }
    }

}
