<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class InviteController extends Controller
{

    /**
     * @OA\Get(
     * path="/invite/show/{id}",
     * summary="get invite",
     * description="get invite by id",
     * operationId="showInvite",
     * tags={"invite"},
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
        $invite = Invite::where('id',$id)->with('host','guest')->first();
        return response()->json([
            'invite'  =>$invite,
        ]);

    }


    /**
     * @OA\Get(
     * path="/invite/getAllFromUser/{id}",
     * summary="get all invites param User",
     * description="List all invites from User",
     * operationId="getAllInvitesFromUser",
     * tags={"invite"},
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
    public function getAllFromUser($id)
    {
        $invites = Invite::where('user_invited',$id)->with('host','guest')->get();
        return response()->json([
            'invites'  =>$invites,
        ]);

    }



    /**
     * @OA\Post(
     * path="/invite/store",
     * summary="store invite",
     * description="create invite",
     * operationId="inviteStore",
     * tags={"invite"},
     * security={{"bearerAuth": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="store invite fields",
     *    @OA\JsonContent(
     *       required={"name","email","user_invited"},
     *       @OA\Property(property="name", type="string", format="text", example="Fulano de tal"),
     *       @OA\Property(property="email", type="string", format="email", example="fulanodetal@gmail.com"),
     *       @OA\Property(property="user_invited", type="string", format="text", example="1"),
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
            'user_invited' => 'required',
        ]);

        $user             = new User;
        $user->name       = $request->input('name');
        $user->email      = $request->input('email');
        $user->password   = Hash::make(123456);
        $user->save();

        //$token = Auth::login($user);

        $invite                 = new Invite;
        $invite->user_id        = $user->id;
        $invite->user_invited   = $request->input('user_invited');
        $invite->name           = $request->input('name');
        $invite->email          = $request->input('email');


        $invite->save();


        return response()->json([
            'status' => 'success',
            'message' => 'created successfully',
            'invite' => $invite,
        ]);
    }




    /**
     * @OA\Put(
     * path="/invite/update/{id}",
     * summary="update invite",
     * description="edit invite",
     * operationId="inviteUpdate",
     * tags={"invite"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    example= 1,
     *    description="id registro",
     *    required=true,
     *    @OA\Schema(
     *       type="integer",
     *     ),
     *  ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Update fields",
     *    @OA\JsonContent(
     *       required={"name","user_invited","user_id"},
     *       @OA\Property(property="name", type="string", format="text", example="Fulana da Silva"),
     *       @OA\Property(property="user_invited", type="string", format="text", example="2"),
     *       @OA\Property(property="user_id", type="string", format="text", example="1"),
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
    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required|string|max:255',
            'user_invited' => 'required',
            'user_id' => 'required',
        ]);




        $invite                 = Invite::find($id);
        $invite->user_id        = $request->input('user_id');
        $invite->user_invited   = $request->input('user_invited');
        $invite->name           = $request->input('name');
        $invite->save();

        $user                   = User::find($request->input('user_id'));;
        $user->name             = $request->input('name');
        $user->save();

        //$token = Auth::login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'created successfully',
            'invite' => $invite,
        ]);
    }

}
