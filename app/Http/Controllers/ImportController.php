<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImportDetailResource;
use App\Http\Resources\ImportResource;
use App\Models\Import;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{

    public function store(Request $req)
    {
        $req->validate([
            'import_date' => ['required', 'date'],
            'staff_id' => ['required', 'integer', 'min:1', 'exists:staff,user_id'],
            'supplier_id' => ['required', 'integer', 'min:1', 'exists:suppliers,id'],
            'product_id' => ['required', 'integer', 'min:1', 'exists:products,id'],
            'qty' => ['required', 'integer', 'min:1'],
            'unit_price' => ['required', 'numeric', 'min:0']
        ]);
    
        DB::beginTransaction();
    
        try {
            $import = new Import();
            $import->import_date = $req->input('import_date');
            $import->staff_id = $req->input('staff_id');
            $import->supplier_id = $req->input('supplier_id');
            $import->import_total = 0;
            $import->save();
    
            $product = Product::findOrFail($req->input('product_id'));
            $qty = $req->input('qty');
            $price = $req->input('unit_price');
            $subtotal = $qty * $price;
    
            $product->increment('qty', $qty);
    
            $import->products()->sync([
                $product->id => [
                    'product_name' => $product->name,
                    'qty' => $qty,
                    'unit_price' => $price,
                    'total_product' => $subtotal
                ]
            ]);
            $import->import_total = $subtotal;
            $import->save();
    
            DB::commit();
    
            return $this->res_suu('Import products successfully', new ImportResource($import->load(['staff', 'supplier', 'products'])));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to import products.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    public function index(Request $req)
    {   
        $req->validate([
            'col' => ['nullable','string','in:id,import_date'],
            'sort' => ['nullable','string','in:asc,desc'],
            'perpage' => ['nullable','integer','min:1']
        ]);
        $import = new Import();
        $col = $req->input('col') ? $req->input('col') : 'id';
        $sort = $req->input('sort') ? $req->input('sort') : 'desc';
        $perage = $req->input('perpage') ? $req->input('perpage') : 10;
        $import = $import->with(['staff:user_id,gender,position_id,photo', 'supplier:id,name,company'])->orderBy($col, $sort)->paginate($perage);
        return $this->res_paginatte($import, 'Get all import successfully', ImportResource::collection($import));
    }

    public function indexDetail(Request $req, $id)
    {
        $req->merge(['id' => $id]);
        $req->validate([
            'id' => ['required', 'integer', 'min:1', 'exists:imports,id']
        ]);

        $import = Import::with(['staff:user_id,gender,position_id,photo', 'supplier:id,name,company', 'products'])->findOrFail($id);
        // $import = new Import();
        // $import = $import->where('id',$id)->with(['staff:id,name','supplier:id,name,company'])->get();
        return $this->res_suu('Get import successfully', new ImportDetailResource($import));
    }

    public function destroy(Request $req, $id)
    {
        $req->merge(['id' => $id]);
        $req->validate([
            'id' => ['required', 'integer', 'min:1', 'exists:imports,id']
        ]);
        $import = Import::findOrFail($id);
        $import->delete();
        return response()->json([
            'result' => true,
            'message' => 'Delete import successfully'
        ]);
    }

    public function update(Request $req, $id)
    {
        $req->merge(['id' => $id]);
        $req->validate([
            'id'=> ['required','integer','min:1','exists:imports,id'],
            'import_date' => ['required', 'date'],
            'staff_id' => ['required', 'integer', 'min:1', 'exists:staff,user_id'],
            'supplier_id' => ['required', 'integer', 'min:1', 'exists:suppliers,id'],
            'product_id' => ['required','integer','min:1','exists:products,id'],
            'qty' => ['required','integer','min:1'],
            'unit_price' => ['required','numeric','min:0']
        ]);

        DB::beginTransaction();
        try {
            $import = Import::findOrFail($id);
            $data = $req->only(['import_date', 'staff_id', 'supplier_id']);
            $data['import_total'] = 0;

            $product = Product::findOrFail($req->input('product_id'));
            $qty = $req->input('qty');
            $price = $req->input('unit_price');
            $subtotal = $qty * $price;

            $product->increment('qty', $qty);

            $import->products()->sync([
                $product->id=>[
                    'product_name' => $product->name,
                    'qty' => $qty,
                    'unit_price' => $price,
                    'total_product' => $subtotal
                ]
            ]);

            $data['import_total'] = $subtotal;
            $import->update($data);
            DB::commit();
            return $this->res_suu('Update import successfully', new ImportResource($import->load(['staff', 'supplier', 'products'])));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update import.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
