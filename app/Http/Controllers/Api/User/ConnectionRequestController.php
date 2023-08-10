<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\Connection\AcceptRequest;
use App\Http\Requests\Api\User\Connection\ConnectionList;
use App\Http\Requests\Api\User\Connection\ReceivedRequestList;
use App\Http\Requests\Api\User\Connection\RemoveConnection;
use App\Http\Requests\Api\User\Connection\SendConnectionRequest;
use App\Http\Requests\Api\User\Connection\SentRequestList;
use App\Http\Requests\Api\User\Connection\SuggestionRequestList;
use App\Http\Requests\Api\User\Connection\WithdrawSentRequest;
use App\Models\ConnectionRequest;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConnectionRequestController extends Controller
{
    private $connectionRequest, $user;
    public function __construct()
    {
        $this->user = new User();
        $this->connectionRequest = new ConnectionRequest();
    }


    public function suggestionList(SuggestionRequestList $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $recIds = $this->connectionRequest->newQuery()
                ->where(function($q){
                    $q->where('sender_id', auth()->user()->id);
                })
                ->pluck('receiver_id')->toArray();
            $sendIds = $this->connectionRequest->newQuery()
                ->where(function($q){
                    $q->where('receiver_id', auth()->user()->id);
                })
                ->pluck('sender_id')->toArray();
            $sentUserIds = array_merge($recIds, $sendIds);
            $data = $this->user->newQuery()->where('id', '!=', auth()->user()->id)->whereNotIn('id', $sentUserIds)->get();

            DB::commit();
            return successWithData(GENERAL_FETCHED_MESSAGE, $data);
        } catch (QueryException $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        }
    }

    public function sentRequestList(SuggestionRequestList $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $userIds = $this->connectionRequest->newQuery()
                ->where(function($q){
                    $q->where('sender_id', auth()->user()->id);
                })
                ->whereStatus(STATUS_REQUEST_SENT)
                ->pluck('receiver_id')->toArray();
            $data = $this->user->newQuery()->whereIn('id', $userIds)->get();
            return successWithData(GENERAL_FETCHED_MESSAGE, $data);

        } catch (QueryException $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        }
    }

    public function receivedRequestList(ReceivedRequestList $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $userIds = $this->connectionRequest->newQuery()
                ->where(function($q){
                    $q->where('receiver_id', auth()->user()->id);
                })
                ->whereStatus(STATUS_REQUEST_SENT)
                ->pluck('sender_id')->toArray();
            $data = $this->user->newQuery()->whereIn('id', $userIds)->get();
            return successWithData(GENERAL_FETCHED_MESSAGE, $data);

        } catch (QueryException $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        }
    }

    public function connectionList(ConnectionList $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $recIds = $this->connectionRequest->newQuery()
                ->where(function($q){
                    $q->where('sender_id', auth()->user()->id);
                })
                ->whereStatus(STATUS_REQUEST_ACCEPTED)
                ->pluck('receiver_id')->toArray();
            $sendIds = $this->connectionRequest->newQuery()
                ->where(function($q){
                    $q->where('receiver_id', auth()->user()->id);
                })
                ->whereStatus(STATUS_REQUEST_ACCEPTED)
                ->pluck('sender_id')->toArray();
            $sentUserIds = array_merge($recIds, $sendIds);
            $data = $this->user->newQuery()->whereIn('id', $sentUserIds)->get();
            return successWithData(GENERAL_FETCHED_MESSAGE, $data);

        } catch (QueryException $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        }
    }

    public function sendConnection(SendConnectionRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $countSender = $this->connectionRequest->newQuery()->whereSenderId(auth()->user()->id)->whereReceiverId($inputs['receiver_id'])->count();
            $countReceiver = $this->connectionRequest->newQuery()->whereSenderId($inputs['receiver_id'])->whereReceiverId(auth()->user()->id)->count();
            if($countSender + $countReceiver > 0)
            {
                DB::rollBack();
                return error("Connection Request Already Sent", ERROR_400);
            }
            $connectionReq = $this->connectionRequest->newInstance();
            $connectionReq->sender_id = auth()->user()->id;
            $connectionReq->receiver_id = $inputs['receiver_id'];
            $connectionReq->save();
            DB::commit();
            return successWithData(GENERAL_SUCCESS_MESSAGE, $connectionReq);

        } catch (QueryException $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        }
    }

    public function withdrawSentRequest(WithdrawSentRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            if(!$this->connectionRequest->newQuery()->whereId($inputs['connection_request_id'])->delete())
            {
                return error(GENERAL_ERROR_MESSAGE, ERROR_400);
            }
            DB::commit();
            return success("Connection request withdrawn successfully.");

        } catch (QueryException $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        }
    }

    public function removeConnection(RemoveConnection $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            if(!$this->connectionRequest->newQuery()->whereId($inputs['connection_request_id'])->delete())
            {
                return error(GENERAL_ERROR_MESSAGE, ERROR_400);
            }
            DB::commit();
            return success("Connection removed successfully.");

        } catch (QueryException $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        }
    }

    public function acceptConnection(AcceptRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $connReq = $this->connectionRequest->newQuery()->whereId($inputs['connection_request_id'])->first();
            $connReq->status = STATUS_REQUEST_ACCEPTED;
            if(!$connReq->save()) {
                return error(GENERAL_ERROR_MESSAGE, ERROR_400);
            }
            DB::commit();
            return success("Connection request accepted successfully.");

        } catch (QueryException $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        }
    }

}
