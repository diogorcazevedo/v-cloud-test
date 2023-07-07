<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Deal;
use Illuminate\Http\Request;


class BidController extends Controller
{

    /**
     * @OA\Get(
     * path="/bid/getAllFromDeal/{id}",
     * summary="get all bids param Deal",
     * description="List all bids from Deal",
     * operationId="getAllBidsFromDeal",
     * tags={"bid"},
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

    public function getAllFromDeal($id)
    {
        $bids = Bid::where('deal_id',$id)->with('user')->with('deal')->get();
        return response()->json([
            'bids'  =>$bids,
        ]);

    }

    /**
     * @OA\Get(
     * path="/bid/getAllFromUser/{id}",
     * summary="get all bids param User",
     * description="List all bids from User",
     * operationId="getAllBidsFromUser",
     * tags={"bid"},
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
        $bids = Bid::where('user_id',$id)
                    ->with('user')
                    ->with(['deal' => function ($q) {
                        $q->with('user');
                    }])
                    ->get();
        return response()->json([
            'bids'  =>$bids,
        ]);

    }

    /**
     * @OA\Get(
     * path="/bid/getById/{id}",
     * summary="get bid param id",
     * description="get bid by User",
     * operationId="getBidById",
     * tags={"bid"},
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
    public function getById($id)
    {
        $bid = Bid::where('id',$id)->with('user')->with('deal')->first();
        return response()->json([
            'bid'  =>$bid,
        ]);

    }


    /**
     * @OA\Post(
     * path="/bid/store/{deal_id}/{user_id}",
     * summary="store bid",
     * description="create bid",
     * operationId="bidStore",
     * tags={"bid"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="deal_id",
     *    in="path",
     *    example= 1,
     *    description="deal_id request",
     *    required=true,
     *    @OA\Schema(
     *       type="integer",
     *     ),
     *  ),
     * @OA\Parameter(
     *    name="user_id",
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
     *    description="store bid fields",
     *    @OA\JsonContent(
     *       required={"type","value"},
     *       @OA\Property(property="accepted", type="string", enum={"Venda","Troca","Desejo"}, format="text", example="Venda"),
     *       @OA\Property(property="value", type="double", format="text", example="15000"),
     *       @OA\Property(property="description", type="string", format="text", example="Proponho troca por iphone"),
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

    public function store(Request $request, $deal_id,$user_id)
    {
        $request->validate([
            'accepted'      => 'required|string',
            'value'         => 'required|max:255',
           // 'description'   => 'required|string|max:255',
        ]);

        $v1 =  str_replace('.', '', $request->input('value'));
        $value =  str_replace(',', '.', $v1);

        $bid                    = new Bid;
        $bid->deal_id           = $deal_id;
        $bid->user_id           = $user_id;
        $bid->accepted          = $request->input('accepted');
        $bid->value             = $value;
        $bid->description       = $request->input('description');
        $bid->save();

        return response()->json([
            'status'    => 'success',
            'message'   => 'Register updated successfully',
            'bid'       => $bid->load(['user','deal']),
        ]);
    }


    /**
     * @OA\Put(
     * path="/bid/accepted/{bid_id}",
     * summary="accepted bid update",
     * description="accepted bid update",
     * operationId="bidAccepted",
     * tags={"bid"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="bid_id",
     *    in="path",
     *    example= 1,
     *    description="bid_id request",
     *    required=true,
     *    @OA\Schema(
     *       type="integer",
     *     ),
     *  ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass accepted bid update",
     *    @OA\JsonContent(
     *       required={"accepted"},
     *       @OA\Property(property="accepted", type="string", format="text", example="1"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="accepted bid update"
     *     ),
     * @OA\Response(
     *          response="default",
     *          description="An error has occurred."
     *      )
     * )
     */
    public function accepted(Request $request, $bid_id)
    {
        $request->validate([
            'accepted'      => 'required|string',
        ]);

        $bid                    = Bid::find($bid_id);
        $bid->accepted          = $request->input('accepted');
        $bid->save();

        $deal                   = Deal::find($bid->deal_id);
        $deal->accepted         = $request->input('accepted');
        $deal->save();


        return response()->json([
            'status'    => 'success',
            'message'   => 'Register updated successfully',
            'bid'       => $bid->load(['user','deal']),
        ]);
    }
}
