<?php

namespace App\Http\Controllers;

use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function store(Request $req)
    {
        $req->validate([
            'name' => ['required', 'string', 'max:250', 'unique:suppliers,name'],
            'address' => ['required', 'string', 'max:250'],
            'company' => ['nullable', 'string', 'max:250', 'unique:suppliers,company']
        ]);
        $supplier = new Supplier();
        $supplier->name = $req->input('name');
        $supplier->address = $req->input('address');
        $supplier->company = $req->input('company');
        $supplier->save();
        return $this->res_suu('supplier created successfully', new SupplierResource($supplier));
    }

    public function index(Request $req)
    {
        $req->validate([
            'search' => ['nullable', 'string', 'max:250'],
            'scol' => ['nullable', 'string', 'max:50'],
            'sort' => ['nullable', 'string', 'max:50'],
        ]);
        $suppliers = new Supplier();
        $scol = $req->filled('scol') ?  $req->input('scol') : 'id';
        $sort = $req->filled('sort') ? $req->input('sort') : 'desc';
        if ($req->filled('search')) {
            $search = $req->input('search');
            $suppliers = $suppliers->where('name', 'like', '%' . $search . '%');
        }
        $suppliers = $suppliers->orderBy($scol, $sort)->get();
        return $this->res_suu('get all suppliers successfully', SupplierResource::collection($suppliers));
    }

    public function update(Request $req, $id)
    {
        $req->merge(['id' => $id]);
        $req->validate([
            'id' => ['required', 'integer', 'min:1', 'exists:suppliers,id'],
            'name' => ['required', 'string', 'max:250', 'unique:suppliers,name,' . $id],
            'address' => ['required', 'string', 'max:250'],
            'company' => ['nullable', 'string', 'max:250', 'unique:suppliers,company,' . $id]
        ]);
        $supplier = new Supplier();
        $data = $req->only(['name', 'address', 'company']);
        $supplier->where('id', $id)->update($data);
        return response()->json([
            'result' => true,
            'message' => 'update supplier successfully',
        ]);
    }

    public function destroy(Request $req, $id) {
        $req->merge(['id' => $id]);
        $req->validate([
            'id' => ['required', 'integer', 'min:1', 'exists:suppliers,id'],
        ]);
        $supplier = Supplier::find($id);
        $supplier->delete();
        return response()->json([
            'result' => true,
            'message' => 'delete supplier successfully'
        ]);
    }
}
