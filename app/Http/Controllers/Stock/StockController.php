<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Products\Stock;
use Illuminate\Http\Request;
use DataTables;

class StockController extends Controller
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
            $stock = Stock::join('product','product.p_id','=','stock.p_id')
            ->join('unit_type','unit_type.unit_type_id','=','product.unit_type_id')
            ->select('stock.stock_id','stock.unit','product.p_id','product.p_name','product.p_price','unit_type.unit_type_name')
            ->latest('stock.created_at')->get();
            return DataTables::of($stock)
            ->addIndexColumn()
            ->editColumn('p_price',function($row){
                return $row->p_price." "."บาท.";
            })
            ->addColumn('unit',function($row){
                return '<input type="number" class="form-control form-control-sm" value="'.$row->unit.'" min="0" max="'.$row->unit.'">';
            })
            ->addColumn('total',function($row){
                return $row->unit * $row->p_price ."&nbsp;บาท.";
            })
            ->rawColumns(['total','unit'])
            ->make(true);
        }
        return view('pages.stocks.stock');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }
}
