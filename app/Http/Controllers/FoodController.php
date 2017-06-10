<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $foods = Food::all();

        return response()->json([
            'data' => $foods,
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\\Http\Request\StoreFood  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFood $request)
    {
        $data               = $request->all();
        $data['user_id']    = Auth::user()->id;

        $food = Food::create($data);

        return response()->json([
            'message' => 'Food offer added successfully.',
            'data'    => $food,
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json([
                'error'   => 'resource_not_found',
                'message' => 'Food does not exist.',
            ], 404);
        }
        return response()->json([
            'data' => $food,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreFood $request, $id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json([
                'error'   => 'resource_not_found',
                'message' => 'Food does not exist.',
            ], 404);
        }

        if ($food->user_id != Auth::user()->id) {
            return response()->json([
                'error'   => 'forbidden_request',
                'message' => 'User does not have permission to edit this food offer.',
            ], 403);
        }
        $food->fill($request->all());
        $food->save();
        return response()->json([
            'message' => 'Food offer successfully updated.',
            'data' => $food,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
