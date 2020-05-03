<?php

namespace App\Http\Controllers\Autocomple;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AutocompleController extends Controller
{
    public function autoUsernamecomple(Request $request) {
        $data = User::select('name')->where('name','like',"%{$request->get('query')}%")->get();
        return response()->json($data);
    }
}
