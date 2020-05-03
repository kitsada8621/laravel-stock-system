<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Products\Sale;
use App\Products\Stock;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    public function index(Request $request) {
        if($request->ajax()) {
            return DataTables::of($this->stocks())
            ->addIndexColumn()
            ->editColumn('updated_at',function($row){
                return Carbon::parse($row->updated_at)->format('d M Y');
            })
            ->editColumn('p_price', function($row){
                return $row->p_price." บาท";
            })
            ->addColumn('unit',function($row){
                return $row->unit." ".$row->unit_type_name;
            })
            ->addColumn('action',function($row){
                return '<button id="btnSale" data-id="'.$row->stock_id.'" class="btn btn-primary btn-sm font-weight-bold">เบิก</button>';
            })
            ->rawColumns(['action','unit'])->make(true);
        }
        return view('pages.products.product_sale.sale');
    }

    protected function stocks() {
        if(Auth::user()->role) {
            return Stock::join('product','product.p_id','=','stock.p_id')
            ->join('product_type','product_type.p_type_id','=','product.p_type_id')
            ->join('unit_type','unit_type.unit_type_id','=','product.unit_type_id')
            ->select('stock.stock_id','stock.unit','stock.updated_at','product.p_id','product.p_name','product.p_price','product_type.p_type_name','unit_type.unit_type_name')
            ->latest('stock.created_at')->get();
        }else {
            return Stock::join('product','product.p_id','=','stock.p_id')
            ->join('product_type','product_type.p_type_id','=','product.p_type_id')
            ->join('unit_type','unit_type.unit_type_id','=','product.unit_type_id')
            ->select('stock.stock_id','stock.unit','stock.updated_at','product.p_id','product.p_name','product.p_price','product_type.p_type_name','unit_type.unit_type_name')
            ->latest('stock.created_at')->get();
        }
    }

    public function cutStock(Request $request,$id) {
        $this->validate($request,[
            'unit' => 'required',
            'users' => Auth::user()->role ? 'required': 'nullable',
        ]);
        $stock = Stock::findOrfail($id);
        $users = User::where('name','=',$request->users)->first();

        if($request->unit > $stock->unit ) {
            return response()->json([
                'success' => false,
                'message' => 'พัสดุไม่เพียงพอ ค่ะ'
            ],404);
        }

        if(Auth::user()->role) {
            if(!$users) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบรายชื่อพนักงาน ค่ะ'
                ],404);
        }
            Sale::create([
                'p_id' => $stock->p_id,
                'p_sale_unit' => $request->unit,
                'times_in' => strtotime(Carbon::now()),
                'status' => 2,
                'user_id' => $users->id,
            ]);
            $stock->unit = $stock->unit - $request->unit;
            $stock->update();
        }else {
            Sale::create([
                'p_id' => $stock->p_id,
                'p_sale_unit' => $request->unit,
                'times_in' => strtotime(Carbon::now()),
                'status' => 1,
                'user_id' => Auth::user()->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'เบิกพัสดุสำเร็จค่ะ',
        ],200);
    }

    /** report */
    public function reports(Request $request) {
        if($request->ajax()) {
            return DataTables::of($this->sales())
            ->addIndexColumn()
            ->editColumn('p_id',function($row){
                return $row->p_id." ".$row->p_name;
            })
            ->editColumn('p_sale_unit',function($row){
                return $row->p_sale_unit." ".$row->unit_type_name;
            })
            ->addColumn('print',function($row){
                return '<button id="btnPrinted" data-id="'.$row->p_sale_id.'" class="btn btn-primary btn-sm"><i class="fas fa-print"></i></button>';
            })
            ->rawColumns(['print'])->make(true);
        }
        return view('pages.report.PrintList');
    }

    protected function sales() {
        if(Auth::user()->role){
            return Sale::join('product','product.p_id','=','product_sale.p_id')
            ->join('product_type','product_type.p_type_id','=','product.p_type_id')
            ->join('unit_type','unit_type.unit_type_id','=','product.unit_type_id')
            ->join('users','users.id','=','product_sale.user_id')
            ->select('product_sale.p_sale_id','product_sale.p_sale_unit','product_sale.times_in','product_sale.status',
            'product.p_id','product.p_name','product.p_price','unit_type.unit_type_name','product_type.p_type_name','users.name','users.id')
            ->where('product_sale.status',2)
            ->latest('product_sale.created_at')->get();
        }else {
            return Sale::join('product','product.p_id','=','product_sale.p_id')
            ->join('product_type','product_type.p_type_id','=','product.p_type_id')
            ->join('unit_type','unit_type.unit_type_id','=','product.unit_type_id')
            ->join('users','users.id','=','product_sale.user_id')
            ->select('product_sale.p_sale_id','product_sale.p_sale_unit','product_sale.times_in','product_sale.status',
            'product.p_id','product.p_name','product.p_price','unit_type.unit_type_name','product_type.p_type_name','users.name','users.id')
            ->where([['users.id','=',Auth::user()->id],['product_sale.status',2]])
            ->latest('product_sale.created_at')->get();
        }
    }


    /** request for admin manages ================================================================================ */

    public function confrimSale(Request $request,$saleId) {
        $sales = Sale::findOrfail($saleId);
        $stockBy = Stock::where('p_id',$sales->p_id)->firstOrfail();
        $stock = Stock::findOrfail($stockBy->stock_id);

        $stock->unit = $stock->unit - $sales->p_sale_unit;
        $sales->status = 2;

        $stock->update();
        $sales->update();


        return response()->json([
            'success' => true,
            'message' => 'อนุมัติการเบิกพัสดุสำเร็จ ค่ะ'
        ],200);

    }

    public function unconfirmSale(Request $request,$id) {

        $sales = Sale::findOrfail($id);
        $_stock = Stock::where('p_id',$sales->p_id)->firstOrfail();
        $stock = Stock::findOrfail($_stock->stock_id);

        $stock->unit = $stock->unit + $sales->p_sale_unit;
        $sales->status = 3;

        $stock->update();
        $sales->update();

        return response()->json([
            'success' => true,
            'message' => 'ยกเลิกการเบิกพัสดุ สำเร็จค่ะ !'
        ],200);
    }

    public function removeRequestSale($id) {

        $product_sale = Sale::findOrfail($id);
        $product_sale->delete();
        return response()->json([
            'success' => true,
            'message' => 'ลบคำร้องขอเบิกสำเร็จค่ะ !'
        ],200);


    }
}
