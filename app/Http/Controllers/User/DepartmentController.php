<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Users\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;

class DepartmentController extends Controller
{
    public function __construct() {
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
            $data = Department::latest()->get();
            return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at',function($row){
                return Carbon::parse($row->created_at)->format('d M Y');
            })
            ->editColumn('updated_at',function($row){
                return Carbon::parse($row->updated_at)->format('d M Y');
            })
            ->addColumn('action',function($row){
                $btn = '<button id="btnEdit" data-id="'.$row->d_id.'" data-name="'.$row->d_name.'" class="btn btn-primary btn-sm font-weight-bold m-1">แก้ไข</button>';
                $btn .= '<button id="btnDelete" data-id="'.$row->d_id.'" class="btn btn-danger btn-sm font-weight-bold m-1">ลบ</button>';
                return $btn;
            })
            ->rawColumns(['action'])->make(true);
        }
        return view('pages.departments.department');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {
        if(!$request->id) {
            $request->validate(['d_name'=> 'unique:department']);
            $this->inserted($request);
            $message = 'บันทึกข้อมูลแผนกสำเร็จค่ะ';
            $status = 201;
        }else {
            $this->updated($request,$request->id);
            $message = 'แก้ไขข้อมูลแผนกสำเร็จค่ะ';
            $status = 200;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ],$status);
    }

    protected function inserted(Request $request) {
        Department::create([
            'd_name' => $request->d_name
        ]);
    }

    protected function updated(Request $request,$id) {
        $department = Department::findOrfail($id);
        if($department->d_name != $request->d_name)  $request->validate(['d_name'=> 'unique:department']); $department->d_name = $request->d_name;
        $department->update();
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Department::find($id)->delete();
        return response()->json(200);
    }
}
