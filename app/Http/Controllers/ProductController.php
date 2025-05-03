<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use function Laravel\Prompts\search;

class ProductController extends Controller
{
    public function store(Request $req){
        $req->validate([
            'name'=>['required','string','max:250','unique:products,name'],
            'qtys'=>['required','integer','min:1'],
            'unit_price_instock'=>['required','numeric','min:0'],
            'sale_price'=>['required','numeric','min:0'],
            'image'=>['nullable','file','mimetypes:image/png,image/jpeg','max:1024'],
        ]);

        $product = new Product();
        $product->name = $req->input('name');
        $product->qty = $req->input('qtys');
        $product->unit_price_stock = $req->input('unit_price_instock');
        $product->unit_sale_stock = $req->input('sale_price');
        $photo = 'products/no_image.jpg';
        if($req->hasFile('image')){
            $photo = $req->file('image')->store('products',['disk'=>'public']);
        }
        $product->image = $photo;
        $product->save();
        return $this->res_suu('product create succcessfully', new ProductResource($product));
    }

    public function index(Request $req){
        $req->validate([
            'search' => ['nullable','string','max:50'],
            'scol' => ['nullable','string','max:50'],
            'sort' => ['nullable','string','max:50'],
            'perpage' => ['nullable','integer','min:1']
        ]);

        $scol = $req->input('scol')? $req->input('scol'): 'id';
        $sort = $req->input('sort')? $req->input('sort'): 'desc';
        $perpage = $req->input('perpage')? $req->input('perpage'):10;
        $product = new Product();
        if($req->filled('search')){
            $search = $req->input('search');
            $product = $product->where('name','like','%'.$search.'%');
        }
        $product = $product->orderBy($scol,$sort)->paginate($perpage);
        return $this->res_paginatte($product, 'get all products successfully', ProductResource::collection($product));

    }

    public function destroy(Request $req,$id){
        $req->merge(['id'=>$id]);
        $req->validate([
            'id' => ['required','integer','min:1','exists:products,id']
        ]);
        $product = new Product();
        $image = ($product->where('id',$id)->first('image'))->image;
        Storage::disk('public')->delete($image);
        $product->where('id',$id)->delete();
        return response()->json([
            'result' => true,
            'message' => 'product deleted successfully'
        ]);
    }

    public function update(Request $req,$id){
        $req->merge(['id'=>$id]);
        $req->validate([
            'id' => ['required','integer','min:1','exists:products,id'],
            'name'=>['required','string','max:250'],
            'qtys'=>['required','integer','min:1'],
            'unit_price_instock'=>['required','numeric','min:0'],
            'sale_price'=>['required','numeric','min:0'],
            'image'=>['nullable','file','mimetypes:image/png,image/jpeg','max:1024'],
        ]);
        $product = new Product();
        $data = $req->only(['name','qty','unit_price_stock','unit_sale_stock']);
        if($req->hasFile('image')){
            $newImage = $req->file('image')->store('products',['disk'=>'public']);
            $data['image'] = $newImage;
            $oldImage = ($product->where('id',$id)->first('image'))->image;
            Storage::disk('public')->delete($oldImage);
        }
        $product->where('id',$id)->update($data);
        return response()->json([
            'result' => true,
            'message' => 'product updated successfully'
        ]);
    }

}
