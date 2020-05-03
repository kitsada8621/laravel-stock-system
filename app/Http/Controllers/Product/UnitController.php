<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitRequest;
use App\Products\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;

class UnitController extends Controller
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
            $data = Unit::latest()->get();
            return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function($row){
                return Carbon::parse($row->created_at)->format('d M Y H:i:s');
            })
            ->editColumn('updated_at', function($row){
                return Carbon::parse($row->updated_at)->format('d M Y H:i:s');
            })
            ->addColumn('action',function($row){
                $button = '<button id="btnEdit" data-id="'.$row->unit_type_id.'" data-name="'.$row->unit_type_name.'" class="btn btn-primary btn-sm shadow-sm font-weight-bold m-1">แก้ไข</button>';
                $button .= '<button id="btnDelete" data-id="'.$row->unit_type_id.'" class="btn btn-danger btn-sm shadow-sm font-weight-bold m-1">ลบข้อมูล</button>';
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('pages.products.unit_type.unit');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UnitRequest $request)
    {   
        if(!$request->id) {
            $this->create($request);
            $status = 201;
            $message = "บันทึกข้อมูลสำเร็จ ค่ะ";
        }else {
            $this->update($request,$request->id);
            $status = 200;
            $message = "แก้ไขข้อมูลสำเร็จ ค่ะ";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ],$status);
    }

    protected function create(Request $request)
    {
        $request->validate(['unit_type_name' => 'unique:unit_type']);
        Unit::create([
            'unit_type_name' => $request->unit_type_name
        ]);
    }

    protected function update(Request $request, $id)
    {
        $unitType = Unit::findOrfail($id);
        if($unitType->unit_type_name != $request->unit_type_name) {
            $request->validate(['unit_type_name' => 'unique:unit_type']);
            $unitType->unit_type_name = $request->unit_type_name;
        }
        $unitType->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Unit::find($id)->delete();
        return response()->json(200);
    }
}
