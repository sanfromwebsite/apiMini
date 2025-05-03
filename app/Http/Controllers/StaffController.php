<?php

namespace App\Http\Controllers;

use App\Http\Resources\StaffResource;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function store(Request $req){
        $req->validate([
            'user_id' => ['required','integer','min:1','exists:users,id','unique:staff,user_id'],
            'gender' => ['required','integer','min:1','max:2'],
            'dob' => ['required','date'],
            'position_id' => ['required','integer','min:1','exists:positions,id'],
            'salary' => ['required','numeric','min:0'],
            'photo' => ['nullable','file','mimetypes:image/png,image/jpeg','max:1024'],
            'stopWork' => ['nullable','boolean'],
        ]);
        $photo = 'staffs/no_image.jpg';
        if($req->hasFile('photo')){
            $photo = $req->file('photo')->store('staffs',['disk'=>'public']);
        }
        $staff = new Staff();
        $staff->user_id = $req->input('user_id');
        $staff->gender = $req->input('gender');
        $staff->dob = $req->input('dob');
        $staff->position_id = $req->input('position_id');
        $staff->salary = $req->input('salary');
        $staff->stopWork = $req->input('stopWork') ? 1 : 0;
        $staff->photo = $photo;
        $staff->save();
        $staff->load('position:id,name');
        return $this->res_suu('staff created successfully',new StaffResource($staff));
    }

    public function index(Request $req){
        $req->validate([
            'search' => ['nullable','string','max:50'],
            'scol' => ['nullable','string','max:50'],
            'sort' => ['nullable','string','max:50'],
            'perpage' => ['nullable','integer','min:1']
        ]);
        $scol = $req->input('scol') ? $req->input('scol') : 'user_id';
        $sort = $req->input('sort') ? $req->input('sort') : 'desc';
        $perpage = $req->input('perpage') ? $req->input('perpage') : 10;
        $staffs = new Staff();
        if($req->filled('search')){
            $search = $req->input('search');
            $staffs = $staffs->where('name','like','%'.$search.'%');
        }
        $staffs = $staffs->with(['user:id,name,email','position:id,name'])->orderBy($scol,$sort)->paginate($perpage);
        return $this->res_paginatte($staffs, 'get all staffs successfully', StaffResource::collection($staffs));
    }

    public function destroy(Request $req,$id){
        $req->merge(['id'=>$id]);
        $req->validate([
            'id' => ['required','integer','min:1','exists:staff,user_id']
        ]);
        $staff = new Staff();
        $photo = ($staff->where('user_id',$id)->first('photo'))->photo;
        Storage::disk('public')->delete($photo);
        $staff->where('user_id',$id)->delete();
        return response()->json([
            'result' => true,
            'message' => 'delete staff successfully'
        ]);
    }

    public function update(Request $req,$id){
        $req->merge(['id' => $id]);
        $req->validate([
            'id' => ['required','integer','min:1','exists:staff,user_id'],
            'gender' => ['required','integer','min:1','max:2'],
            'dob' => ['required','date'],
            'position_id' => ['required','integer','min:1','exists:positions,id'],
            'salary' => ['required','numeric','min:0'],
            'photo' => ['nullable','file','mimetypes:image/png,image/jpeg','max:1024'],
            'stopWork' => ['nullable','boolean'],
        ]);
        $staff = new Staff();

        $data = $req->only('gender','dob','position_id','salary');
        $data['stopWork'] = $req->input('stopWork') ? 1 : 0;
        if($req->hasFile('photo')){
            $photo = $req->file('photo')->store('staffs',['disk'=>'public']);
            $data['photo'] = $photo;

            $oldPhoto = ($staff->where('user_id',$id)->first('photo'))->photo;
            Storage::disk('public')->delete($oldPhoto);
        }
        $staff->where('user_id',$id)->update($data);
        return response()->json([
            'result' => true,
            'message' => 'update staff successfully',
        ]);
    }
}