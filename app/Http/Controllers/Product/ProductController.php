<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Products\Product;
use Illuminate\Http\Request;
use DataTables;

class ProductController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()) {
            $product = Product::join('unit_type','unit_type.unit_type_id','=','product.unit_type_id')
            ->join('product_type','product_type.p_type_id','=','product.p_type_id')
            ->select('product.p_id','product.p_name','product.p_price','unit_type.unit_type_id','unit_type.unit_type_name','product_type.p_type_id','product_type.p_type_name')
            ->latest('product.created_at')
            ->get();
            return DataTables::of($product)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $button = '<button id="btnEdit" data-p_id="'.$row->p_id.'" data-p_name="'.$row->p_name.'" data-p_price="'.$row->p_price.'" data-unit_type_id="'.$row->unit_type_id.'" data-p_type_id="'.$row->p_type_id.'" class="btn btn-primary btn-sm m-1 font-weight-bold">แก้ไข</button>';
                $button .= '<button id="btnDelete" data-id="'.$row->p_id.'" class="btn btn-danger btn-sm m-1 font-weight-bold">ลบข้อมูล</button>';
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('pages.products.product');
    }

    public function create(ProductRequest $request) {
        if(!$request->id) {
            $request->validate([
                'p_id' => 'unique:product',
                'p_name' => 'unique:product',
            ]);  
            Product::create([
                'p_id' => $request->p_id,
                'p_name' => $request->p_name,
                'p_price' => $request->p_price,
                'unit_type_id' => $request->unit_type_id,
                'p_type_id' => $request->p_type_id
            ]);

            $success= true; $message = "บันทึกข้อมูลสำเร็จ ค่ะ"; $status=201;
        }else {
            $product = Product::find($request->id);
            if($request->p_name != $product->p_name) $request->validate(['p_name' => 'unique:product']); $product->p_name = $request->p_name;
            $product->p_price = $request->p_price;
            $product->unit_type_id = $request->unit_type_id;
            $product->p_type_id = $request->p_type_id;
            $product->update();
            $success= true; $message = "แก้ไขข้อมูลสำเร็จ ค่ะ"; $status=200;
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ],$status);
    }

    public function destroy($id) {
        $product = Product::find($id);
        $product->delete();
        return response()->json(200);
    }
}
