<?php

namespace App\Products;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    //
    protected $table = 'unit_type';
    protected $fillable = ['unit_type_id','unit_type_name'];
    public $primaryKey = 'unit_type_id';
}
