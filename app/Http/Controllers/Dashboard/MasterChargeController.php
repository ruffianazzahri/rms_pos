<?php


namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Models\MasterCharge;
use App\Http\Controllers\Controller;

class MasterChargeController extends Controller
{
    public function index()
    {
        $charges = MasterCharge::all();
        return view('master_charges.index', compact('charges'));
    }

    public function create()
    {
        return view('master_charges.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required|in:tax,discount,service',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        MasterCharge::create($request->all());

        return redirect()->route('master-charges.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $charge = MasterCharge::findOrFail($id);
        return view('master_charges.edit', compact('charge'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required|in:tax,discount,service',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $charge = MasterCharge::findOrFail($id);
        $charge->update($request->all());

        return redirect()->route('master-charges.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        MasterCharge::destroy($id);
        return redirect()->route('master-charges.index')->with('success', 'Data berhasil dihapus');
    }
}
