<?php

namespace App\Products;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //
    protected $table ='product_sale';
    protected $fillable =['p_sale_id','p_id','p_sale_unit','times_in','status','user_id'];
    public $primaryKey = 'p_sale_id';
}
