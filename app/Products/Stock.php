<?php

namespace App\Products;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    //
    protected $table = 'stock';
    protected $fillable = ['stock_id','p_id','unit'];
    public $primaryKey = 'stock_id';
}
