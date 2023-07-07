<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\LocationDeal;
use App\Models\Photo;
use App\Models\Urgency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;



class DealController extends Controller
{


    /**
     * @OA\Get(
     * path="/deal/getAll",
     * summary="deals all",
     * description="List all deals",
     * operationId="dealGetAll",
     * tags={"deal"},
     * security={{"bearerAuth": {} }},
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

    public function getAll(){

        $deals = Deal::with('photos','urgency','location','user','bids')->get();

        return response()->json([
            'deals'  =>$deals,
        ]);


    }


    /**
     * @OA\Get(
     * path="/deal/getAllByActiveStatus/{active}",
     * summary="deals by active status",
     * description="List all deals by active status",
     * operationId="dealGetAllByActiveStatus",
     * tags={"deal"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="active",
     *    in="path",
     *    example= 1,
     *    description="status da proposta ativa, inativa ou todas ",
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

    public function getAllByActiveStatus($active){

        $deals = Deal::where("active",$active)->with('photos','urgency','location','user','bids')->get();

        return response()->json([
            'deals'  =>$deals,
        ]);

    }


    /**
     * @OA\Get(
     * path="/deal/getByStatus/{status}",
     * summary="deals by status",
     * description="List all deals by status",
     * operationId="dealGetByStatus",
     * tags={"deal"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="status",
     *    in="path",
     *    example= 1,
     *    description="status da proposta aceita/recusada",
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
    public function getByStatus($status)
    {
        $deals = Deal::where("active",1)->where("accepted",$status)->with('photos','urgency','location','user','bids')->get();
        return response()->json([
            'deals'  =>$deals,
        ]);
    }

    /**
     * @OA\Get(
     * path="/deal/getFromUserByStatus/{id}/{status}",
     * summary="user deals by status",
     * description="Deals list from user status parameter",
     * operationId="dealGetFromUserByStatus",
     * tags={"deal"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    example= 1,
     *    description="id do usuário",
     *    required=true,
     *    @OA\Schema(
     *       type="integer",
     *     ),
     *  ),
     * @OA\Parameter(
     *    name="status",
     *    in="path",
     *    example= 1,
     *    description="status da proposta aceita/recusada",
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
    public function getFromUserByStatus($id,$status)
    {
        $deals = Deal::where('active', 1)->where('user_id', $id)->where('accepted', $status)
                        ->with('photos', 'urgency', 'location')
                        ->with(['bids' => function ($q) {
                            $q->with('user');
                        }])->get();

        return response()->json([
            'deals' => $deals,
        ]);

    }


    /**
     * @OA\Get(
     * path="/deal/getAllFromUser/{id}",
     * summary="user all deals",
     * description="Deals list from user",
     * operationId="dealGetAllFromUser",
     * tags={"deal"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    example= 1,
     *    description="id do usuário",
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
        $deals = Deal::where('active', 1)->where('user_id',$id)
                        ->with('photos','urgency','location')
                        ->with(['bids' => function ($q) {
                            $q->with('user');
                        }])->get();
        return response()->json([
            'deals'  =>$deals,
        ]);

    }

    /**
     * @OA\Get(
     * path="/deal/getById/{id}",
     * summary="deal by id",
     * description="Deal get deal registers by id",
     * operationId="dealGetById",
     * tags={"deal"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    example= 1,
     *    description="id do usuário",
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
        $deal = Deal::where('id',$id)
                        ->with('photos','urgency','location','bids')
                        ->with(['bids' => function ($q) {
                            $q->with('user');
                        }])
                        ->first();
        return response()->json([
            'deal'  =>$deal,
        ]);

    }


    /**
     * @OA\Post(
     * path="/deal/store/{id}",
     * summary="store deal",
     * description="create deal",
     * operationId="dealStore",
     * tags={"deal"},
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
     *       required={"type","value","description"},
     *       @OA\Property(property="type", type="string", enum={"Venda","Troca","Desejo"}, format="text", example="Venda"),
     *       @OA\Property(property="value", type="double", format="text", example="15000"),
     *       @OA\Property(property="description", type="string", example="Mackbook pro com CPU de 16 núcleos GPU de 7 núcleos Memória unificada de 256 GB SSD de 1T"),
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

    public function store(Request $request, $id)
    {
        $request->validate([
            'type'          => 'required|string|max:255',
            'value'         => 'required|max:255',
            'description'   => 'required|string|max:255',
            //'trade_for'     => 'required|string|max:255',
        ]);
        $user   = User::find($id);
        $v1     =  str_replace('.', '', $request->input('value'));
        $value  =  str_replace(',', '.', $v1);

        $deal                   = new Deal;
        $deal->user_id          = $user->id;
        $deal->type             = $request->input('type');
        $deal->value            = $value;
        $deal->description      = $request->input('description');
        $deal->trade_for        = $request->input('trade_for') !== null?$request->input('trade_for'):null;
        $deal->save();

        $urgency            = new Urgency;
        $urgency->deal_id   = $deal->id;
        $urgency->type      = "Baixa";
        $urgency->save();


        $location                   = new LocationDeal;
        $location->deal_id          = $deal->id;
        $location->zip_code         = $user->location->zip_code;
        $location->state            = $user->location->state;
        $location->city             = $user->location->city;
        $location->address          = $user->location->address;
        $location->save();

        return response()->json([
            'status'    => 'success',
            'message'   => 'Register updated successfully',
            'deal'      => $deal->load(['photos','urgency','location']),
        ]);
    }



    /**
     * @OA\Put(
     * path="/deal/update/{id}",
     * summary="update deal",
     * description="edit deal",
     * operationId="dealUpdate",
     * tags={"deal"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    example= 1,
     *    description="deal_id - id do registro",
     *    required=true,
     *    @OA\Schema(
     *       type="integer",
     *     ),
     *  ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Update fields",
     *    @OA\JsonContent(
     *       required={"type","value","description"},
     *       @OA\Property(property="type", type="string", enum={"Venda","Troca","Desejo"}, format="text", example="Troca"),
     *       @OA\Property(property="value", type="double", format="text", example="26000"),
     *       @OA\Property(property="description", type="string", example="Mackbook pro com CPU de 16 núcleos GPU de 7 núcleos Memória unificada de 256 GB SSD de 2T"),
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
            'type'          => 'required|string|max:255',
            'value'         => 'required|max:255',
            'description'   => 'required|string|max:255',
           // 'trade_for'     => 'required|string|max:255',
        ]);

        $v1 =  str_replace('.', '', $request->input('value'));
        $value =  str_replace(',', '.', $v1);

        $deal = Deal::find($id);
        $deal->type             = $request->input('type');
        $deal->value            = $value;
        $deal->description      = $request->input('description');
        $deal->trade_for        = $request->input('trade_for') !== null?$request->input('trade_for'):null;
        $deal->save();

        return response()->json([
            'status'    => 'success',
            'message'   => 'Register updated successfully',
            'deal'      => $deal->load(['photos','urgency','location']),
        ]);
    }


    /**
     * @OA\Put(
     * path="/deal/updateUrgency/{id}",
     * summary="update urgency deal",
     * description="edit urgency deal",
     * operationId="dealUrgencyUpdate",
     * tags={"deal"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    example= 1,
     *    description="deal_id - id do registro",
     *    required=true,
     *    @OA\Schema(
     *       type="integer",
     *     ),
     *  ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Update fields",
     *    @OA\JsonContent(
     *       required={"type"},
     *       @OA\Property(property="type", type="string", enum={"Baixa", "Media"}, example="Media"),
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

    public function updateUrgency(Request $request, $id)
    {
        $request->validate([
            'type'          => 'required|string|max:255',
        ]);

        $urgency         = Urgency::where('deal_id',$id)->first();
        $urgency->type   = $request->input('type');
        $urgency->save();

        $deal = Deal::find($id);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Register updated successfully',
            'deal'      => $deal->load(['photos','urgency','location']),
        ]);
    }



    /**
     * @OA\Put(
     * path="/deal/updateLocation/{id}",
     * summary="update Location deal",
     * description="edit Location deal",
     * operationId="dealLocationUpdate",
     * tags={"deal"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    example= 1,
     *    description="deal_id - id do registro",
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

        $location                   = LocationDeal::where('deal_id',$id)->first();
        $location->zip_code         = $request->input('zip_code');
        $location->state            = $request->input('state');
        $location->city             = $request->input('city');
        $location->address          = $request->input('address');
        $location->save();

        $deal = Deal::find($id);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Register updated successfully',
            'deal'      => $deal->load(['photos','urgency','location']),
        ]);
    }


    /**
     * @OA\Post(
     *   path="/deal/imageStore/{id}",
     *   tags={"deal"},
     *   summary="Store image deal",
     *   description="edit Location deal",
     *   operationId="dealImageStore",
     *   security={{"bearerAuth": {} }},
     *  @OA\Parameter(
     *    name="id",
     *    in="path",
     *    example= 1,
     *    description="deal_id - id do registro",
     *    required=true,
     *    @OA\Schema(
     *       type="integer",
     *     ),
     *  ),
     *   @OA\RequestBody(
     *     required=true,
     *     description="Bulk products Body",
     *
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *          @OA\Schema(
     *              @OA\Property(property="file", type="string", format="binary")
     *          ),
     *      )
     *   ),
     * @OA\Response(
     *    response=201,
     *    description="descrition list"
     *     ),
     * @OA\Response(
     *          response="default",
     *          description="An error has occurred."
     *      )
     * )
     *
     */


    public function imageStore(Request $request, $id)
    {
        $deal = Deal::find($id);
        $data = $request->all();
        $data['deal_id'] = $deal->id;
        $data['src'] = null;
        $photo = Photo::create($data);


        $file = $request->file('file');
        Storage::disk('s3')->put("vibbraneo/".$deal->id.'/'.$photo->id, file_get_contents($file));
        $photo->src = "https://vibbra.s3.sa-east-1.amazonaws.com/vibbraneo/".$deal->id.'/'.$photo->id;
        $photo->save();



        return response()->json([
            'status'    => 'success',
            'message'   => 'Register updated successfully',
            'deal'      => $deal->load(['photos','urgency','location']),
        ]);
    }



    /**
     * @OA\Put(
     * path="/deal/active/{id}",
     * summary="update active deal",
     * description="edit active deal",
     * operationId="dealActive",
     * tags={"deal"},
     * security={{"bearerAuth": {} }},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    example= 1,
     *    description="deal_id - id do registro",
     *    required=true,
     *    @OA\Schema(
     *       type="integer",
     *     ),
     *  ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Update fields",
     *    @OA\JsonContent(
     *       required={"active"},
     *       @OA\Property(property="active", type="string", format="text", example="1"),
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

    public function active(Request $request, $id)
    {
        $request->validate([
            'active'            => 'required|string',
        ]);

        $deal                   = Deal::find($id);
        $deal->active           = $request->input('active');
        $deal->save();


        return response()->json([
            'status'    => 'success',
            'message'   => 'Register updated successfully',
            'deal'      => $deal->load(['photos','urgency','location']),
        ]);
    }
}
