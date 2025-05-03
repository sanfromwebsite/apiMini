<?php

namespace App\Http\Controllers;

use App\Http\Resources\customerOrderResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function store(Request $req){
        $req->validate([
            'order_date' => ['required','date'],
            'staff_id' => ['required','integer','min:1','exists:staff,user_id'],
            'customer_id' => ['required','integer','min:1','exists:customers,user_id'],
            'product_id' => ['required','integer','min:1','exists:products,id'],
            'qty' => ['required','integer','min:1'],
            'unit_price' => ['required','numeric','min:0']

        ]);
        DB::beginTransaction();
        try {
            $order = new Order();
            $order->order_date = $req->input('order_date');
            $order->staff_id = $req->input('staff_id');
            $order->customer_id = $req->input('customer_id');
            $order->total = 0;
            $order->save();

            $product = Product::findOrFail($req->input('product_id'));
            $qty = $req->input('qty');
            $price = $req->input('unit_price');
            $subtotal = $qty * $price;
            $product->decrement('qty',$qty);
            $order->products()->sync([
                $product->id => [
                    'product_name' => $product->name,
                    'qty' => $qty,
                    'unit_price' => $price,
                    'total_price' => $subtotal,
                ]
            ]);
            $order->total = $subtotal;
            $order->save();
            DB::commit();
            return $this->res_suu('Order products successfully', new OrderResource($order));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to order products',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function index(Request $req){
        $order = new Order();
        $scol = $req->input('scol') ? $req->input('scol') : 'id';
        $sort = $req->input('sort') ? $req->input('sort') : 'desc';
        $perpage = $req->input('perpage') ? $req->input('perpage') : 10;
        $order = $order->with((['staff:user_id,gender,photo,position_id','customer:user_id,contact','products']))->orderBy($scol,$sort)->paginate($perpage);
        return $this->res_paginatte($order,'Get all orders successfully', OrderResource::collection($order));
    }

    public function customerOrder(Request $request) {
        $customer = $request->user('sanctum');
        
        if (!$customer) {
            return response()->json([
                'result' => false,
                'message' => 'Unauthorized. Please log in.',
            ], 401);
        }
    
        $orders = Order::with(['staff:user_id,gender,photo,position_id','customer:user_id,contact','products'])
            ->where('customer_id', $customer->id)
            ->get();
        return $this->res_suu('Get all orders successfully',customerOrderResource::collection($orders));
        // return response()->json([
        //     'result' => true,
        //     'message' => 'Orders retrieved successfully',
        //     'data' => $orders
        // ]);
    }
}
