<?php

namespace App\Http\Controllers;

use App\Http\Resources\PositionResource;
use App\Models\Position;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function store(Request $req){
        $req->validate([
            'name' => ['required','string','max:250','unique:positions,name']
        ]);
        $position = new Position();
        $position->name = $req->input('name');
        $position->save();
        return $this->res_suu('position created successfully',new PositionResource($position));
    }

    public function index(){
        $positions = Position::all();
        return $this->res_suu('get all position successfully',$positions);
    }

    public function update(Request $req,$id){
        $req->merge(['id'=>$id]);
        $req->validate([
            'id'=>['required','integer','min:1','exists:positions,id'],
            'name'=>['required','string','max:250','unique:positions,name']
        ]);
        
        $position = new Position();
        $data = $req->only(['name']);
        $position->where('id',$id)->update($data);
        return response()->json([
            'result' => true,
            'message' => 'update position successfully',
        ]);
    }

    public function destroy(Request $req,$id){
        $req->merge(['id'=>$id]);
        $req->validate([
            'id'=>['required','integer','min:1','exists:positions,id']
        ]);
        $position = Position::find($id);
        $position->delete();
        return response()->json([
            'result' => true,
            'message' => 'delete position successfully'
        ]);
    }
}
