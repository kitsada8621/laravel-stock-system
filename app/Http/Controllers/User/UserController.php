<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\UsersRequest;
use App\User;
use Illuminate\Http\Request;
use DataTables;
use Image;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        if($request->ajax()) {
            $users = User::join('department','department.d_id','=','users.d_id')
            ->select('users.id','users.name','users.email','users.username','users.password','users.profile','users.created_at','users.updated_at','users.role','department.d_id','department.d_name')
            ->latest('users.created_at')
            ->get();
            return DataTables::of($users)
            ->addIndexColumn()
            ->editColumn('role',function($row){
                return $row->role ? 'แอดมิน' : 'พนักงาน';
            })
            ->addColumn('profile',function($row){
                if($row->profile) {
                if(file_exists(public_path('upload/profile/'.$row->profile))) $image = '<img src="/upload/profile/'.$row->profile.'" width="40" height="40" style="border-radius:50%;">';
                else $image = '<img src="https://rnmu.rw/wp-content/uploads/2019/10/man-300x300.png" width="40" height="40" style="border-radius:50%;">';
                }else $image = '<img src="https://rnmu.rw/wp-content/uploads/2019/10/man-300x300.png" width="40" height="40" style="border-radius:50%;">';
                return $image;
            })
            ->addColumn('action',function($row){
                $btn = '<button id="btnDetails" class="btn btn-primary btn-sm font-weight-bold m-1"
                data-profile="'.$row->profile.'" data-name="'.$row->name.'" data-email="'.$row->email.'" data-username="'.$row->username.'" data-d_name="'.$row->d_name.'" data-role_name="'.$row->role_name.'" data-created="'.date('d-m-Y H:i:s',strtotime($row->created_at)).'" data-updated="'.date('d-m-Y H:i:s',strtotime($row->updated_at)).'"
                >ข้อมูล</button>';
                $btn .= '<button id="btnEdit" class="btn btn-info btn-sm font-weight-bold m-1" 
                data-id="'.$row->id.'" data-name="'.$row->name.'" data-email="'.$row->email.'" data-username="'.$row->username.'" data-profile="'.$row->profile.'" data-d_id="'.$row->d_id.'" data-role="'.$row->role.'" data-password="'.$row->password.'">แก้ไข</button>';
                $btn .= '<button id="btnDelete" data-id="'.$row->id.'" data-name="'.$row->name.'" class="btn btn-danger btn-sm font-weight-bold m-1">ลบ</button>';
                return $btn;
            })
            ->rawColumns(['action','profile'])
            ->make(true);
        }
        return view('pages.users.user');
    }   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersRequest $request)
    {
        if(!$request->id) {
            $this->validate($request,[
                'name' => 'unique:users',
                'email' => 'unique:users',
                'username' => 'unique:users',
            ]);
            $this->insert($request);
            $message = "บันทึกข้อมูลพนักงาน สำเร็จค่ะ";
            $status = 201;
        }else {
            $this->update($request,$request->id);
            $message = "แก้ไขข้อมูลพนักงาน สำเร็จค่ะ";
            $status = 200;
        }
        return response()->json([
            'success' => true,
            'message' => $message,
        ],$status);
    }

    public function insert(Request $request) {
        $profile = null;
        if($request->profile) {

            $image = $request->file('profile');
            $profile = time().".".$image->extension();
            $image_resize = Image::make($image->getRealPath());
            $image_resize->resize(300,null,function($constraint){
                $constraint->aspectRatio();
            });
            $image_resize->save(public_path('/upload/profile/'.$profile));

        }

        User::create([
            'name' => $request->name,
            'd_id' => $request->d_id,
            'role' => $request->role,
            'email' => $request->email,
            'profile' => $profile,
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $users = User::findOrfail($id);
        if($users->name != $request->name) $request->validate(['name'=>'unique:users']); $users->name = $request->name;
        if($users->email != $request->email) $request->validate(['email'=>'unique:users']); $users->email = $request->email;
        if($users->username != $request->username) $request->validate(['username'=>'unique:users']); $users->username = $request->username;

        $users->d_id = $request->d_id;
        $users->role = $request->role;

        if($request->profile) {
            if(file_exists(public_path('upload/profile/'.$users->profile))) @unlink(public_path('upload/profile/'.$users->profile));
            $images = $request->file('profile');
            $profilename = time().'.'.$images->extension();
            $image = Image::make($images->getRealPath());
            $image->resize(300,null,function($constraint) { $constraint->aspectRatio();});
            $image->save(public_path('upload/profile/'.$profilename));
            $users->profile = $profilename;
        }
        $users->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if(file_exists(public_path('/upload/profile/'.$user->profile))) @unlink(public_path('/upload/profile/'.$user->profile));
        $user->delete();
        return response()->json(200);
    }

    /** ====================================================== Setting ======================================================  */

    public function setting() {
        return view('pages.users.setting');
    }

    public function confirmSetting(Request $request,$id) {
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'd_id' => 'required',
            'profile' => 'nullable|image',
            'role' => 'required',
            'password' => 'required',
        ]);
        $users = User::findOrfail($id);
        if(!Hash::check($request->password, $users->password)) {
            return response()->json([
                'success' => false,
                'message' => 'รหัสผ่านไม่ถูกต้องค่ะ'
            ],404);
        }
        if($request->name != $users->name) $request->validate(['name'=>'unique:users']); $users->name = $request->name;
        if($request->email != $users->email) $request->validate(['email'=>'unique:users']); $users->email = $request->email;
        if($request->username != $users->username) $request->validate(['username'=>'unique:users']); $users->username = $request->username;
        $users->role = $request->role;
        $users->d_id = $request->d_id;
        if($request->profile) {
            if(file_exists(public_path('upload/profile/'.$users->profile))) @unlink(public_path('upload/profile/'.$users->profile));
            $images = $request->file('profile');
            $names = time().".".$images->extension();
            $resize = Image::make($images->getRealPath());
            $resize->resize(300,null,function($constraint) {
                $constraint->aspectRatio();
            });
            $resize->save(public_path('/upload/profile/'.$names));
            $users->profile = $names;
        }
        $users->update();
        return response()->json([
            'success' => true,
            'message' => 'แก้ไขข้อมูลสำเร็จ ค่ะ',
        ],200);
    }

    /** ================================================== passwordd ================================================== */
    public function passwordIndex() {
        return view('pages.users.password');
    }

    public function updatePassword(PasswordRequest $request,$id) {

        $users = User::findOrfail($id);
        if(!Hash::check($request->password_old, $users->password)) {
            return response()->json([
                'success' => false,
                'message' => 'รหัสผ่านไม่ถูกต้อง ค่ะ'
            ],404);
        }

        $users->password = Hash::make($request->password);
        $users->update();

        return response()->json([
            'success' => true,
            'message' => 'แก้ไขรหัสผ่านสำเร็จค่ะ'
        ],200);
    }

    
}
