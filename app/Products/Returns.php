<?php

namespace App\Products;

use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    //
    protected $table ='product_return';
    protected $fillable =['p_return_id','p_sale_id','p_return_unit','times_out'];
    public $primaryKey = 'p_return_id';
}
