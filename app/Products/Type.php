<?php

namespace App\Products;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    //
    protected $table = 'product_type';
    protected $fillable = ['p_type-id','p_type_name'];
    public $primaryKey = 'p_type_id';
}
