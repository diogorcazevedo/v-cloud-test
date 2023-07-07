<?php

namespace App\Http\Controllers;


use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{
    protected $user;


    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['show']]);
        $this->middleware('auth:api');
    }


    /**
     * @OA\Get(
     * path="/register/show/{id}",
     * summary="show register ",
     * description="show register",
     * operationId="showRegister",
     * tags={"register"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    example= 1,
     *    description="id param ",
     *    required=true,
     *    @OA\Schema(
     *       type="integer",
     *     ),
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="descrition list"
     *     ),
     * @OA\Response(
     *          response="default",
     *          description="An error has occurred."
     *      )
     * )
     */
    public function show($id)
    {
        $user= User::where('id',$id)->with('bids','location','deals','messages')->first();
        return response()->json([
            'user'  =>$user,
        ]);

    }


    /**
     * @OA\Post(
     * path="/register/store/{id}",
     * summary="store register",
     * description="create register",
     * operationId="registerStore",
     * tags={"register"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    example= 1,
     *    description="id do usuário requerente",
     *    required=true,
     *    @OA\Schema(
     *       type="integer",
     *     ),
     *  ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass store fields",
     *    @OA\JsonContent(
     *       required={"name","email","password"},
     *       @OA\Property(property="name", type="string", format="text", example="Novo Fulano de Tal"),
     *       @OA\Property(property="email", type="email", format="text", example="novofulano@gmail.com"),
     *       @OA\Property(property="password", type="password", example="123456"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="descrition list"
     *     ),
     * @OA\Response(
     *          response="default",
     *          description="An error has occurred."
     *      )
     * )
     */

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user->with('location'),
            'token'=>$token,
            'token_type'=>'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }



    /**
     * @OA\Put(
     * path="/register/update/{id}",
     * summary="update register",
     * description="edit register",
     * operationId="registerUpdate",
     * tags={"register"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    example= 1,
     *    description=" id param",
     *    required=true,
     *    @OA\Schema(
     *       type="integer",
     *     ),
     *  ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Update fields",
     *    @OA\JsonContent(
     *       required={"name","email"},
     *       @OA\Property(property="name", type="string", format="text", example="Mudei de Nome de Tal"),
     *       @OA\Property(property="email", type="email", format="text", example="mudeideemail@gmail.com"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="descrition list"
     *     ),
     * @OA\Response(
     *          response="default",
     *          description="An error has occurred."
     *      )
     * )
     */

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
        ]);

        $user           = User::find($id);
        $user->name     = $request->input('name');
        $user->email    = $request->input('email');
        $user->save();

        return response()->json([
            'status'    => 'success',
            'message'   => 'Register updated successfully',
            'user'      => $user->load(['location']),
        ]);
    }



    /**
     * @OA\Put(
     * path="/register/updatePassword/{id}",
     * summary="update Password register",
     * description="edit Password register",
     * operationId="registerUpdatePassword",
     * tags={"register"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    example= 1,
     *    description=" id param",
     *    required=true,
     *    @OA\Schema(
     *       type="integer",
     *     ),
     *  ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Update fields",
     *    @OA\JsonContent(
     *       required={"name","email"},
     *       @OA\Property(property="password", type="password", format="text", example="654321"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="descrition list"
     *     ),
     * @OA\Response(
     *          response="default",
     *          description="An error has occurred."
     *      )
     * )
     */
    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|max:255',
        ]);

        $user = User::find($id);
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password updated successfully',
            'user' => $user,
        ]);
    }




    /**
     * @OA\Put(
     * path="/register/updateLocation/{id}",
     * summary="update Location register",
     * description="edit Location register",
     * operationId="registerLocationUpdate",
     * tags={"register"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    example= 1,
     *    description=" id param",
     *    required=true,
     *    @OA\Schema(
     *       type="integer",
     *     ),
     *  ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Update fields",
     *    @OA\JsonContent(
     *       required={"zip_code","state","city","address"},
     *       @OA\Property(property="lat", type="double", format="text", example="-20.311738744829306"),
     *       @OA\Property(property="lng", type="double", format="text", example="-40.288277444412245"),
     *       @OA\Property(property="zip_code", type="integer", format="text", example="29055260"),
     *       @OA\Property(property="address", type="string", format="text", example="Rua Aleixo Netto 1226"),
     *       @OA\Property(property="city", type="string", example="Vitória"),
     *       @OA\Property(property="state", type="string", example="ES"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="descrition list"
     *     ),
     * @OA\Response(
     *          response="default",
     *          description="An error has occurred."
     *      )
     * )
     */
    public function updateLocation(Request $request, $id)
    {
        $request->validate([
            'zip_code'  => 'required|integer',
            'state'     => 'required|string|max:2',
            'city'      => 'required|string|max:191',
            'address'   => 'required|string|max:191',
        ]);

        $location                   = Location::where('user_id',$id)->first();
        $location->zip_code         = $request->input('zip_code');
        $location->state            = $request->input('state');
        $location->city             = $request->input('city');
        $location->address          = $request->input('address');
        $location->save();

        $user = User::find($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Address updated successfully',
            'user' => $user,
        ]);
    }
}
