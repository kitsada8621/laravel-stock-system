<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Products\Returns;
use App\Products\Sale;
use App\Products\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    public function index(Request $request) {
        if($request->ajax()) {
            return DataTables::of($this->sales())
            ->addIndexColumn()
            ->editColumn('times_in',function($row){
                return Carbon::createFromTimestamp($row->times_in)->format('d M Y');
            })
            ->addColumn('unit',function($row){
                return '<input type="number" id="inputUnit" name="'.$row->p_sale_id.'" class="form-control form-control-sm" min="1" max="'.$row->p_sale_unit.'" value="'.$row->p_sale_unit.'">';
            })
            ->addColumn('action',function($row){
                return '<button id="btnReturn" data-id="'.$row->p_sale_id.'" class="btn btn-primary btn-sm font-weight-bold">คืน</button>';
            })->rawColumns(['action','unit'])->make(true);
        }
        return view('pages.products.product_return.returns');
    }

    protected function sales() {
        if(Auth::user()->role){
            return Sale::join('product','product.p_id','=','product_sale.p_id')->join('users','users.id','=','product_sale.user_id')
            ->join('product_type','product_type.p_type_id','=','product.p_type_id')->join('unit_type','unit_type.unit_type_id','=','product.unit_type_id')
            ->select('product.p_id','product.p_name','product.p_price','product_sale.p_sale_id','product_sale.p_sale_unit','product_sale.times_in','product_sale.status','users.id','users.name','product_type.p_type_name','unit_type.unit_type_name')
            ->where('product_sale.status','=',2)
            ->where('product_sale.p_sale_unit','>',0)
            ->latest('product_sale.created_at')->get();
        }else {
            return Sale::join('product','product.p_id','=','product_sale.p_id')->join('users','users.id','=','product_sale.user_id')
            ->join('product_type','product_type.p_type_id','=','product.p_type_id')->join('unit_type','unit_type.unit_type_id','=','product.unit_type_id')
            ->select('product.p_id','product.p_name','product.p_price','product_sale.p_sale_id','product_sale.p_sale_unit','product_sale.times_in','product_sale.status','users.id','users.name','product_type.p_type_name','unit_type.unit_type_name')
            ->where('product_sale.status','=',2)
            ->where('product_sale.p_sale_unit','>',0)
            ->latest('product_sale.created_at')->get();
        }
    }

    public function returnProduct($id,Request $request) {
        /** form database  */
        $product_sale = Sale::find($id);
        $stock = Stock::where('p_id',$product_sale->p_id)->first();

        /** stock update */
        Stock::where('stock_id',$stock->stock_id)->update([
            'unit' => $stock->unit + $request->unit
        ]);

        /** make return data to database */
        Returns::create([
            'p_sale_id' => $product_sale->p_sale_id,
            'p_return_unit' => $request->unit,
            'times_out' => strtotime(Carbon::now()),
        ]);

        /** product sale update */
        if($product_sale->p_sale_unit - $request->unit < 1) $product_sale->status = 4;
        $product_sale->p_sale_unit = $product_sale->p_sale_unit - $request->unit;
        $product_sale->update();

        /** response */
        return response()->json([
            'success' => true,
            'message' => 'คืนพัสดุสำเร็จ ค่ะ'
        ],200);
    }

    /** ========================================================= report ====================================================================== */
    public function reportIndex(Request $request) {
        if($request->ajax()) {
            $data = Returns::join('product_sale','product_sale.p_sale_id','product_return.p_sale_id')
            ->join('product','product.p_id','product_sale.p_id')
            ->join('users','users.id','product_sale.user_id')
            ->join('department','department.d_id','users.d_id')
            ->join('unit_type','unit_type.unit_type_id','product.unit_type_id')
            ->select('product.p_id','product.p_name','product.p_price','product_sale.p_sale_id','product_sale.p_sale_unit','product_sale.times_in','product_sale.status','users.name','users.role','product_return.p_return_id','product_return.p_return_unit','product_return.times_out','product_return.created_at','department.d_name','unit_type.unit_type_name')
            ->latest('product_return.created_at')->get();
            return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('times_in',function($row){
                return formatDateThai(Carbon::createFromTimestamp($row->times_in));
            })
            ->editColumn('times_out',function($row){
                return formatDateThai(Carbon::createFromTimestamp($row->times_out));
            })
            ->addColumn('select',function($row){
                return '<input type="checkbox" id="'.$row->p_return_id.'" name="selected" data-id="'.$row->p_return_id.'">';
            })
            ->addColumn('action',function($row){
                return '<button id="btnDetails" class="btn btn-primary btn-sm"  data-name="'.$row->name.'" data-d_name="'.$row->d_name.'" data-role="'.$row->role.'" data-email="'.$row->email.'" data-tel="0958785478" data-times_in="'.formatDateThai(date('Y-m-d H:i:s',$row->times_in)).'" data-times_out="'.formatDateThai(date('Y-m-d H:i:s',$row->times_out)).'" data-p_id="'.$row->p_id.'" data-p_name="'.$row->p_name.'" data-p_price="'.$row->p_price.'" data-unit="'.$row->p_return_unit.'" data-unit_type="'.$row->unit_type_name.'" data-p_type="'.$row->p_type_name.'" data-total="'.$row->p_price * $row->p_return_unit.'"><i class="fas fa-eye"></i></button>';
            })
            ->rawColumns(['action','select'])
            ->make(true);
        }
        return view('pages.report.printReturnList');
    } 

    public function reportPrint(Request $request) {
        $data = Returns::join('product_sale','product_sale.p_sale_id','=','product_return.p_sale_id')
        ->join('product','product.p_id','=','product_sale.p_id')
        ->join('users','users.id','=','product_sale.user_id')
        ->join('unit_type','unit_type.unit_type_id','=','product.unit_type_id')
        ->join('product_type','product_type.p_type_id','=','product.p_type_id')
        ->select('product_return.p_return_id','product_return.p_return_unit','product_return.times_out','product_sale.times_in','product.p_id','product.p_name','product.p_price','unit_type.unit_type_name','product.p_type_id','users.name')
        ->whereIn('product_return.p_return_id',$request->id)->get();
        return response()->json($data,200);
    }


}
