<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function store(StoreUser $request){
        $data = $request->all();

        $user = User::create($data);

        return response()->json([
            'message' => 'User added successfully.',
            'data'    => $user,
        ], 200);
    }
}
