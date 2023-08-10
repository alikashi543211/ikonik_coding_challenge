<?php

use Illuminate\Support\Facades\DB;

function fetchUserId($email)
{
    return DB::table('users')->where('email', $email)->first()->id;
}

function fetchCommonConnections($recId, $sendId)
{
    $receiverConnectionIds = [];
    $senderConnectionIds = [];

    // For Sender Connection Ids

    // agar mene send kyey hen to receiver comparable wala  na ho
    $senderConnectionIdsAsSent = DB::table('connection_requests')
        // ->where('id', '!=', $skipConId)
        ->where('sender_id', $sendId)
        ->where('receiver_id', "!=", $recId)
        ->whereStatus(STATUS_REQUEST_ACCEPTED)
        ->pluck('receiver_id')->toArray();
    // agar mene receive kye hen to sender comparable wala na ho.
    $senderConnectionIdsAsReceived = DB::table('connection_requests')
        // ->where('id', '!=', $skipConId)
        ->where('receiver_id', $sendId)
        ->where('sender_id', "!=", $recId)
        ->whereStatus(STATUS_REQUEST_ACCEPTED)
        ->pluck('sender_id')->toArray();
    $senderConnectionIds = array_merge($senderConnectionIdsAsSent,$senderConnectionIdsAsReceived);

    // For Receiver Connection Ids

    // agar mene send kyey hen to receiver comparable wala  na ho
    $senderConnectionIdsAsSent = DB::table('connection_requests')
        // ->where('id', '!=', $skipConId)
        ->where('sender_id', $recId)
        ->where('receiver_id', "!=", $sendId)
        ->whereStatus(STATUS_REQUEST_ACCEPTED)
        ->pluck('receiver_id')->toArray();

    // agar mene receive kye hen to sender comparable wala na ho.
    $senderConnectionIdsAsReceived = DB::table('connection_requests')
        // ->where('id', '!=', $skipConId)
        ->where('receiver_id', $recId)
        ->where('sender_id', "!=", $sendId)
        ->whereStatus(STATUS_REQUEST_ACCEPTED)
        ->pluck('sender_id')->toArray();

    $receiverConnectionIds = array_merge($senderConnectionIdsAsSent,$senderConnectionIdsAsReceived);

    // Calculate Common Connections
    return (integer) count(array_intersect($senderConnectionIds, $receiverConnectionIds));
}

function fetchCommonConnectionsOld($recId, $sendId)
{
    $connections = DB::table('connection_requests')->select(['id', 'sender_id', 'receiver_id'])->get()->toArray();
    $skipConId = 0;
    foreach($connections as $con)
    {
        if( ($con['sender_id'] + $con['receiver_id']) == ($recId + $sendId))
        {
            $skipConId = $con['id'];
            break;
        }
    }
    $receiverConnectionIds = [];
    $senderConnectionIds = [];

    // For Sender Connection Ids

    // agar mene send kyey hen to receiver comparable wala  na ho
    $senderConnectionIdsAsSent = DB::table('connection_requests')
        // ->where('id', '!=', $skipConId)
        ->where('sender_id', $sendId)
        ->where('receiver_id', "!=", $recId)
        ->whereStatus(STATUS_REQUEST_ACCEPTED)
        ->pluck('receiver_ids')->toArray();


    // agar mene receive kye hen to sender comparable wala na ho.
    $senderConnectionIdsAsReceived = DB::table('connection_requests')
        // ->where('id', '!=', $skipConId)
        ->where('receiver_id', $sendId)
        ->where('sender_id', "!=", $recId)
        ->whereStatus(STATUS_REQUEST_ACCEPTED)
        ->pluck('receiver_ids')->toArray();
    $senderConnectionIds = array_merge($senderConnectionIdsAsSent,$senderConnectionIdsAsReceived);

    // For Receiver Connection Ids

    // agar mene send kyey hen to receiver comparable wala  na ho
    $senderConnectionIdsAsSent = DB::table('connection_requests')
        // ->where('id', '!=', $skipConId)
        ->where('sender_id', $recId)
        ->where('receiver_id', "!=", $sendId)
        ->whereStatus(STATUS_REQUEST_ACCEPTED)
        ->pluck('receiver_ids')->toArray();

    // agar mene receive kye hen to sender comparable wala na ho.
    $senderConnectionIdsAsReceived = DB::table('connection_requests')
        // ->where('id', '!=', $skipConId)
        ->where('receiver_id', $recId)
        ->where('sender_id', "!=", $sendId)
        ->whereStatus(STATUS_REQUEST_ACCEPTED)
        ->pluck('receiver_ids')->toArray();

    $receiverConnectionIds = array_merge($senderConnectionIdsAsSent,$senderConnectionIdsAsReceived);

    return count(array_intersect($senderConnectionIds, $receiverConnectionIds));
}
