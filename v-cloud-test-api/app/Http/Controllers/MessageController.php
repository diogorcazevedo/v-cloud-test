<?php

namespace App\Http\Controllers;


use App\Models\Message;
use Illuminate\Http\Request;


class MessageController extends Controller
{


    /**
     * @OA\Get(
     * path="/message/getAllFromDeal/{id}",
     * summary="get all messages param Deal",
     * description="List all messages from Deal",
     * operationId="getAllMessagesFromDeal",
     * tags={"message"},
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
        $messages = Message::where('deal_id',$id)->with('user','deal')->get();
        return response()->json([
            'messages'  =>$messages,
        ]);

    }

    /**
     * @OA\Get(
     * path="/message/getAllFromDealAndUser/{deal_id}/{user_id}",
     * summary="get all messages param Deal and user",
     * description="List all messages from Deal and user",
     * operationId="getAllMessagesFromDealAndUser",
     * tags={"message"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="deal",
     *    in="path",
     *    example= 1,
     *    description="deal param ",
     *    required=true,
     *    @OA\Schema(
     *       type="integer",
     *     ),
     *  ),
     * @OA\Parameter(
     *    name="user",
     *    in="path",
     *    example= 1,
     *    description="user id param ",
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
    public function getAllFromDealAndUser($deal,$user)
    {
        $messages = Message::where('deal_id',$deal)->where('user_id',$user)->with('user','deal')->get();
        return response()->json([
            'messages'  =>$messages,
        ]);

    }


    /**
     * @OA\Get(
     * path="/message/getById/{id}",
     * summary="get all message by id",
     * description="List all message by id",
     * operationId="getMessageById",
     * tags={"message"},
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
        $message = Message::where('id',$id)->with('user','deal')->first();
        return response()->json([
            'message'  =>$message,
        ]);

    }



    /**
     * @OA\Post(
     * path="/message/store/{deal_id}/{user_id}",
     * summary="Mensagem do cliente",
     * description="Mensagem do cliente interessado",
     * operationId="messageStore",
     * tags={"message"},
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
     *       @OA\Property(property="deal_id", type="string", format="text", example="1"),
     *       @OA\Property(property="user_id", type="string", format="text", example="2"),
     *       @OA\Property(property="title", type="string", format="text", example="Proponho troca por iphone"),
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
            'deal_id'      => 'required',
            'user_id'      => 'required',
            'title'        => 'required|max:255',
        ]);


        $message                = new Message;
        $message->deal_id       = $deal_id;
        $message->user_id       = $user_id;
        $message->title         = $request->input('title');
        $message->message       = null;
        $message->save();

        return response()->json([
            'status'    => 'success',
            'message'   => 'Register updated successfully',
            'msn'       => $message->load(['user','deal']),
        ]);
    }






    /**
     * @OA\Put(
     * path="/message/update/{id}",
     * summary="Resposta do vendedor",
     * description="Resposta do dono do negócio (vendedor)",
     * operationId="messageUpdate",
     * tags={"message"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    example= 1,
     *    description="id do param",
     *    required=true,
     *    @OA\Schema(
     *       type="integer",
     *     ),
     *  ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Update fields",
     *    @OA\JsonContent(
     *       required={"message_id","message"},
     *       @OA\Property(property="message_id", type="string", format="text", example="1"),
     *       @OA\Property(property="message", type="string", example="Obrigado pelo interesse"),
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
            'message_id'      => 'required',
            'message'        => 'required|max:255',
        ]);


        $msn                  = Message::find($id);
        $msn->message          = $request->input('message');
        $msn->save();

        return response()->json([
            'status'    => 'success',
            'message'   => 'Register updated successfully',
            'msn'       => $msn->load(['user','deal']),
        ]);
    }
}
