<?php

namespace App\Products;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $table = 'product';
    protected $fillable = ['p_id','p_name','p_price','unit_type_id','p_type_id'];
    public $primaryKey = 'p_id';
    public $incrementing = false;
}
