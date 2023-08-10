<?php

namespace Database\Seeders;

use App\Models\ConnectionRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ConnectionsInCommonSeeder extends Seeder
{
    public function run()
    {
        if(ConnectionRequest::whereStatus(STATUS_REQUEST_ACCEPTED)->count() > 0)
        {
            $reqList = ConnectionRequest::whereStatus(STATUS_REQUEST_ACCEPTED)->get();
            foreach($reqList as $req)
            {
                $req->common_connections = fetchCommonConnections($req->receiver_id, $req->sender_id);
                $req->save();
            }
        }
    }
}
