<?php

namespace App\Http\Controllers;

use App\Products\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {  
        return view('pages.home');
    }
    public function home(Request $request)
    {   
        if($request->ajax()) {
            $data = $this->getOrder($request);
            return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function($row){
                return Carbon::parse($row->created_at)->format('d M Y');
            })
            ->editColumn('status',function($row){
                if($row->status == 1) $result = 'รออนุมัติ';
                if($row->status == 2) $result = 'อนุมัติ';
                if($row->status == 3) $result = 'ไม่อนุมัติ';
                if($row->status == 4) $result = 'คืนพัสดุ';
                return $result;
            })
            ->editColumn('p_sale_unit',function($row){
                return $row->p_sale_unit." ".$row->unit_type_name;
            })
            ->editColumn('p_id',function($row){
                return $row->p_id." ".$row->p_name;
            })
            ->addColumn('action',function($row){
                if(Auth::user()->role) {
                    if($row->status == 1) $btn = '<button id="btnConfirm" data-id="'.$row->p_sale_id.'" class="btn btn-primary btn-sm font-weight-bold">อนุมัติ</button>';
                    if($row->status == 2) {
                        $btn = '<button id="unconfirm" data-id="'.$row->p_sale_id.'" class="btn btn-danger btn-sm m-1 font-weight-bold">ยกเลิก</button>';
                    } 
                    if($row->status == 3) $btn = '<button id="btnRemoveOrder" data-id="'.$row->p_sale_id.'" class="btn btn-danger btn-sm font-weight-bold">ลบ</button>';
                    if($row->status == 4) $btn = '<span class="text-danger font-weight-bold" style="text-decoration:line-through;">คืนแล้ว</span>';
                    return $btn;
                } 
                else {
                    if($row->status == 1) $btn = '<button class="btn btn-warning btn-sm" disabled><i class="fas fa-spinner"></i></button>';
                    if($row->status == 2) $btn = '<button class="btn btn-success btn-sm" disabled><i class="fas fa-check-double"></i></button>';
                    if($row->status == 3) $btn = '<button class="btn btn-danger btn-sm" disabled><i class="fas fa-user-minus"></i></button>';
                    if($row->status == 4) $btn = '<span class="text-danger" style="text-decoration:line-through;">คืนแล้ว</span>';
                    return $btn;
                } 
            })->rawColumns(['action'])->make(true);
        }
        return view('pages.home');
    }

    protected function getOrder(Request $request) {
        if(Auth::user()->role) {

            if($request->category) {

                return Sale::join('product','product.p_id','=','product_sale.p_id')->join('users','users.id','=','product_sale.user_id')->join('unit_type','unit_type.unit_type_id','product.unit_type_id')
                ->select('product_sale.p_sale_id','product.p_id','product_sale.status','product_sale.p_sale_unit','product.p_name','product.p_price','users.name','product_sale.created_at','unit_type.unit_type_name')
                ->where([['product_sale.status','=',$request->category],['product_sale.status','!=',4]])
                ->latest('product_sale.created_at')->get();

            }else {

                return Sale::join('product','product.p_id','=','product_sale.p_id')->join('users','users.id','=','product_sale.user_id')->join('unit_type','unit_type.unit_type_id','product.unit_type_id')
                ->select('product_sale.p_sale_id','product.p_id','product_sale.status','product_sale.p_sale_unit','product.p_name','product.p_price','users.name','product_sale.created_at','unit_type.unit_type_name')
                ->where('product_sale.status','!=',4)
                ->latest('product_sale.created_at')->get();
            }
            
        }else {
            if($request->category) {

                return Sale::join('product','product.p_id','=','product_sale.p_id')->join('users','users.id','=','product_sale.user_id')->join('unit_type','unit_type.unit_type_id','product.unit_type_id')
                ->select('product_sale.p_sale_id','product.p_id','product_sale.status','product_sale.p_sale_unit','product.p_name','product.p_price','users.name','product_sale.created_at','unit_type.unit_type_name')
                ->where([['product_sale.status',$request->category],['users.id',Auth::user()->id],['product_sale.status','!=',4]])
                ->latest('product_sale.created_at')->get();

            }else {

                return Sale::join('product','product.p_id','=','product_sale.p_id')->join('users','users.id','=','product_sale.user_id')->join('unit_type','unit_type.unit_type_id','product.unit_type_id')
                ->select('product_sale.p_sale_id','product.p_id','product_sale.status','product_sale.p_sale_unit','product.p_name','product.p_price','users.name','product_sale.created_at','unit_type.unit_type_name')
                ->where([['users.id',Auth::user()->id],['product_sale.status','!=',4]])
                ->latest('product_sale.created_at')->get();
            }
        }
    }

    public function showCount(Request $request) {
        $data = Sale::where('status','!=',4)->count();
        return response()->json($data);
    }
    public function showCountconfirm() {
        $data = Sale::where('status',2)->count();
        return response()->json($data);
    }
    public function showCountWaitConfirm() {
        $data = Sale::where('status',1)->count();
        return response()->json($data);
    }
    public function showCountRemove() {
        $data = Sale::where('status',3)->count();
        return response()->json($data);
    }
}
