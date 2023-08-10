<?php

namespace Database\Seeders;

use App\Models\Request;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ConnectionsInCommonSeeder extends Seeder
{
    public function run()
    {
        if(Request::whereStatus(STATUS_REQUEST_ACCEPTED)->count() > 0)
        {
            $reqList = Request::whereStatus(STATUS_REQUEST_ACCEPTED)->get();
            foreach($reqList as $req)
            {
                $req->common_connections = fetchCommonConnections($req->receiver_id, $req->sender_id);
                $req->save();
            }
        }
    }
}
