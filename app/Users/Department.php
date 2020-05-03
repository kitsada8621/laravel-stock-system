<?php

namespace App\Users;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'department';
    protected $fillable = ['d_id','d_name'];
    public $primaryKey = 'd_id';
}
