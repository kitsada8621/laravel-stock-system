<?php

namespace App\Products;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    //
    protected $table = 'import';
    protected $fillable = ['import_id','p_id','unit','date_in'];
    public $primaryKey = 'import_id';
}
