<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCoords;
use App\Http\Requests\StoreFood;
use App\Food;
use App\Comment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $foods = Food::orderby('expiry', 'desc')->get();

        //INEFFICIENT DONT DO THIS
        foreach($foods as $food){
            $latestcomment = Comment::where('food_id',$food->id)->orderBy('created_at', 'desc')->first();

            if(!empty($latestcomment) || isset($latestcomment)) {

                $food['status'] = $latestcomment['status'];
            }else{
                $food['status'] = 4;
            }
            switch ($food['status']){
                case 0:
                    $food['status'] = "No more";
                        break;
                case 1:
                    $food['status'] = "Very little";
                        break;
                case 2:
                    $food['status'] = "Some";
                        break;
                case 3:
                    $food['status'] = "Plenty";
                        break;
                case 4:
                    $food['status'] = "A lot";
                        break;
            }

            $food['expiry'] = Carbon::now()->createFromFormat('Y-m-d H:i:s', $food['expiry'])->diffForHumans();
        }
        return response()->json([
            'data' => $foods,
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFood $request)
    {
        $data               = $request->all();
        $data['user_id']    = Auth::user()->id;

        $food = Food::create($data);

        $now = Carbon::now();
        $expiry = Carbon::createFromFormat('Y-m-d H:i:s', $request->expiry);
        if($now >= $expiry){
            return response()->json([
                'error'   => 'invalid_request',
                'message' => 'Unable to create an offer at that date/time.',
            ], 422);

        }

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

        $latestcomment = Comment::where('food_id',$id)->orderBy('created_at', 'desc')->first();

        if(!empty($latestcomment) || isset($latestcomment)) {
            $food['status'] = $latestcomment['status'];
        }else{
            $food['status'] = 4;
        }
        switch ($food['status']) {
            case 0:
                $food['status'] = "No more";
                break;
            case 1:
                $food['status'] = "Very little";
                break;
            case 2:
                $food['status'] = "Some";
                break;
            case 3:
                $food['status'] = "Plenty";
                break;
            case 4:
                $food['status'] = "A lot";
                break;
        }
        return response()->json([
            'data' => $food,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreFood $request, $id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json([
                'error'   => 'resource_not_found',
                'message' => 'Food offer does not exist.',
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

    public function getComments($id){
        $food = Food::with('comments', 'comments.user')->where('id', $id)->first();

        if(!$food){
            return response()->json([
                'error'   => 'resource_not_found',
                'message' => 'Food does not exist.',
            ], 404);
        }
        $latestcomment = Comment::where('food_id',$id)->orderBy('created_at', 'desc')->first();

        if(!empty($latestcomment) || isset($latestcomment)) {
            $food['status'] = $latestcomment['status'];
        }else{
            $food['status'] = 4;
        }
        switch ($food['status']) {
            case 0:
                $food['status'] = "No more";
                break;
            case 1:
                $food['status'] = "Very little";
                break;
            case 2:
                $food['status'] = "Some";
                break;
            case 3:
                $food['status'] = "Plenty";
                break;
            case 4:
                $food['status'] = "A lot";
                break;
        }
        foreach($food->comments as $comment){

            switch ($comment['status']) {
                case 0:
                    $comment['status'] = "No more";
                    break;
                case 1:
                    $comment['status'] = "Very little";
                    break;
                case 2:
                    $comment['status'] = "Some";
                    break;
                case 3:
                    $comment['status'] = "Plenty";
                    break;
                case 4:
                    $comment['status'] = "A lot";
                    break;
            }
        }

        return response()->json([
            'data' => $food,
        ], 200);
    }

//    public function getNearby(GetCoords $request){
//
//        if (!isset($request->range) || empty($request->range)) {
//            $range = 25; //Range in kilometers
//        } else {
//            $range = $request->range;
//        }
//
//        $lat = $request->lat;
//        $lng = $request->lng;
//
//        $foods = Food::with('user')->select('foods.*')
//            ->selectRaw('( 3959 * acos( cos( radians(?) ) *
//                           cos( radians( start_lat ) )
//                           * cos( radians( start_lng ) - radians(?)
//                           ) + sin( radians(?) ) *
//                           sin( radians( start_lat ) ) )
//                         ) AS distance', [$lat, $lng, $lat])
//            ->havingRaw("distance < ?", [$range])
//            ->get();
//        return response()->json([
//            'data' => $foods,
//        ], 200);
//    }
}
