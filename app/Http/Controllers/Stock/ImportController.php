<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Products\Import;
use App\Products\Product;
use App\Products\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;

class ImportController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    public function index(Request $request) {
        if($request->ajax()) {
            $product = Product::latest()->get();
            return DataTables::of($product)
            ->addIndexColumn()
            ->addColumn('unit', function($row){
                return '<input type="number" class="form-control form-control-sm" min="0" id="unit" name="'.$row->p_id.'">';
            })
            ->addColumn('action',function($row){
                return '<button id="import" data-id="'.$row->p_id.'" class="btn btn-primary btn-sm font-weight-bold">นำเข้า</button>';
            })
            ->rawColumns(['action','unit'])->make(true);
        }
        return view('pages.imports.import');
    }

    public function importProduct(Request $request,$id) {
        $this->validate($request,[
            'id' => 'required',
            'unit' => 'required|numeric'
        ]);
        $product = Product::find($id);

        Import::create([
            'p_id' => $product->p_id,
            'unit' => $request->unit,
            'date_in' => strtotime(Carbon::now())
        ]);

        $stock = Stock::where('p_id',$product->p_id)->first();
        if($stock) {
            Stock::where('stock_id',$stock->stock_id)->update([
                'unit' => abs($stock->unit + $request->unit)
            ]);
        }else {
            Stock::create([
                'p_id' => $product->p_id,
                'unit' => $request->unit,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'นำเข้าพัสดุสำเร็จ ค่ะ'
        ],200);
    }

    public function importHistory(Request $request) {
        if($request->ajax()) {
            $data = Import::join('product','product.p_id','=','import.p_id')
            ->select('import.import_id','import.unit','import.date_in','product.p_id','product.p_name')
            ->latest('import.created_at')->get();
            return DataTables::of($data) 
            ->addIndexColumn()
            ->editColumn('date_in',function($row){
                return Carbon::createFromTimestamp($row->date_in)->format('d M Y H:i:s');
            })
            ->addColumn('unit',function($row){
                return '<input type="number" id="unit" name="'.$row->import_id.'" data-id="'.$row->import_id.'" class="form-control form-control-sm" min="0" value="'.$row->unit.'">';
            })
            ->addColumn('action',function($row){
                return '<button id="btnDelete" data-id="'.$row->import_id.'" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>';
            })
            ->rawColumns(['action','unit'])->make(true);
        }
        return view('pages.import.import-history');
    }

    public function updateImport(Request $request,$id) {
        $import = Import::find($id);
        $stocks = Stock::where('p_id',$import->p_id)->first();
        if($request->unit > $import->unit) {    
            $amount = $request->unit - $import->unit;
            $stock_in = $stocks->unit + $amount;
            $import->unit = $request->unit;
            Stock::where('stock_id',$stocks->stock_id)->update([
                'unit' => $stock_in
            ]);
        }elseif($request->unit < $import->unit){    
            $amount = $import->unit - $request->unit;
            $stock_out = $stocks->unit - $amount;
            $import->unit = $request->unit;
            Stock::where('stock_id',$stocks->stock_id)->update([
                'unit' => $stock_out
            ]);
        } else {}

        $import->update();
        return response()->json([
            'success' => true,
            'message' => 'แก้ไขข้อมูลสำเร็จ ค่ะ'
        ],200);
    }

    public function destroy($id) {
        $import = Import::find($id);
        $stock = Stock::where('p_id',$import->p_id)->first();
        Stock::where('stock_id',$stock->stock_id)->update([
            'unit' => $stock->unit - $import->unit
        ]);
        $import->delete();
        return response()->json(200);
        
    }

    public function historyImport(Request $request) {
        if($request->ajax()) {
            $data = Import::join('product','product.p_id','=','import.p_id')
            ->select('import.import_id','import.unit','import.date_in','product.p_id','product.p_name')
            ->latest('import.created_at')->get();
            return DataTables::of($data) 
            ->addIndexColumn()
            ->editColumn('date_in',function($row){
                return Carbon::createFromTimestamp($row->date_in)->format('d M Y H:i:s');
            })
            ->addColumn('unit',function($row){
                return '<input type="number" id="unit" name="'.$row->import_id.'" data-id="'.$row->import_id.'" class="form-control form-control-sm" min="0" value="'.$row->unit.'">';
            })
            ->addColumn('action',function($row){
                return '<button id="btnDelete" data-id="'.$row->import_id.'" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>';
            })
            ->rawColumns(['action','unit'])->make(true);
        }
        return view('pages.imports.history');
    }
}
