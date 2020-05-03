<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Products\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;

class TypeController extends Controller
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
            $types = Type::latest()->get();
            return DataTables::of($types)
            ->addIndexColumn()
            ->editColumn('created_at', function($row){
                return Carbon::parse($row->created_at)->format('d M Y');
            })
            ->editColumn('updated_at', function($row){
                return Carbon::parse($row->updated_at)->format('d M Y');
            })
            ->addColumn('action',function($row){
                $button = '<button id="btnEdit" data-id="'.$row->p_type_id.'" data-name="'.$row->p_type_name.'" class="btn btn-primary btn-sm font-weight-bold m-1">แก้ไข</button>';
                $button .= '<button id="btnDelete" data-id="'.$row->p_type_id.'"  class="btn btn-danger btn-sm font-weight-bold m-1">ลบข้อมูล</button>';
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('pages.products.product_type.type');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'p_type_name' => 'required'
        ]);

        if(!$request->id) {
            $this->insert($request);
            $message = "บันทึกข้อมูลสำเร็จ ค่ะ";
            $status = 201;
        }else {
            $this->update($request,$request->id);
            $message = "แก้ไขข้อมูลสำเร็จ ค่ะ";
            $status = 200;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ],$status);
    }

    /**
     * insert a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function insert(Request $request)
    {
        $request->validate([ 'p_type_name' => 'unique:product_type']);
        Type::create([
            'p_type_name' => $request->p_type_name
        ]);
        $success = true;
        $message = "บันทึกข้อมูลสำเร็จ ค่ะ";
        $status = 201;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function update(Request $request, $id)
    {   
        $types = Type::find($id);
        if($request->p_type_name != $types->p_type_name) {
            $request->validate([ 'p_type_name' => 'unique:product_type']);
            $types->p_type_name = $request->p_type_name;
        }
        $types->update();
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Type::find($id)->delete();
        return response()->json(200);
    }
}
