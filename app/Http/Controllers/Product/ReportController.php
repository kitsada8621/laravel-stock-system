<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Products\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    public function printSale($id) {
        $data = Sale::join('product','product.p_id','=','product_sale.p_id')
        ->join('product_type','product_type.p_type_id','=','product.p_type_id')
        ->join('unit_type','unit_type.unit_type_id','=','product.unit_type_id')
        ->join('users','users.id','=','product_sale.user_id')
        ->join('department','department.d_id','=','users.d_id')
        ->select('product_sale.p_sale_id','product_sale.p_sale_unit','product_sale.times_in','product_sale.status',
        'product.p_id','product.p_name','product.p_price','unit_type.unit_type_name','product_type.p_type_name','users.name','users.id','users.role','users.email','department.d_name')
        ->where('product_sale.p_sale_id','=',$id)
        ->first();
        return view('pages.report.sale',compact('data'));
    }
}
