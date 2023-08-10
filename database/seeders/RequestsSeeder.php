<?php

namespace Database\Seeders;

use App\Models\ConnectionRequest;
use App\Models\Request;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('connection_requests')->truncate();

        $this->sentRequests();
        $this->receivedRequests();
        $this->acceptedRequests();

        Schema::enableForeignKeyConstraints();
    }

    /*
        Sent Requests By The Webteam User
    */
    private function sentRequests()
    {
        $req = new ConnectionRequest();
        $req->sender_id = fetchUserId('webteam@aol.com');
        $req->receiver_id = fetchUserId('catalog@outlook.com');
        $req->common_connections = fetchCommonConnections(fetchUserId('catalog@outlook.com'), fetchUserId('webteam@aol.com'));
        $req->save();

        $req = new ConnectionRequest();
        $req->sender_id = fetchUserId('webteam@aol.com');
        $req->receiver_id = fetchUserId('akoblin@verizon.net');
        $req->common_connections = fetchCommonConnections(fetchUserId('akoblin@verizon.net'), fetchUserId('webteam@aol.com'));
        $req->save();

        $req = new ConnectionRequest();
        $req->sender_id = fetchUserId('webteam@aol.com');
        $req->receiver_id = fetchUserId('arnold@mac.com');
        $req->common_connections = fetchCommonConnections(fetchUserId('arnold@mac.com'), fetchUserId('webteam@aol.com'));
        $req->save();

        $req = new ConnectionRequest();
        $req->sender_id = fetchUserId('webteam@aol.com');
        $req->receiver_id = fetchUserId('spadkins@optonline.net');
        $req->common_connections = fetchCommonConnections(fetchUserId('spadkins@optonline.net'), fetchUserId('webteam@aol.com'));
        $req->save();
    }

    /*
        Received Requests By The Webteam User
    */
    private function receivedRequests()
    {
        $req = new ConnectionRequest();
        $req->sender_id = fetchUserId('kingjoshi@yahoo.ca');
        $req->receiver_id = fetchUserId('webteam@aol.com');
        $req->common_connections = fetchCommonConnections(fetchUserId('webteam@aol.com'), fetchUserId('kingjoshi@yahoo.ca'));
        $req->save();

        $req = new ConnectionRequest();
        $req->sender_id = fetchUserId('terjesa@yahoo.com');
        $req->receiver_id = fetchUserId('webteam@aol.com');
        $req->common_connections = fetchCommonConnections(fetchUserId('webteam@aol.com'), fetchUserId('terjesa@yahoo.com'));
        $req->save();

        $req = new ConnectionRequest();
        $req->sender_id = fetchUserId('torgox@yahoo.com');
        $req->receiver_id = fetchUserId('webteam@aol.com');
        $req->common_connections = fetchCommonConnections(fetchUserId('webteam@aol.com'), fetchUserId('torgox@yahoo.com'));
        $req->save();

        $req = new ConnectionRequest();
        $req->sender_id = fetchUserId('rkobes@sbcglobal.net');
        $req->receiver_id = fetchUserId('webteam@aol.com');
        $req->common_connections = fetchCommonConnections(fetchUserId('webteam@aol.com'), fetchUserId('rkobes@sbcglobal.net'));
        $req->save();
    }
    /*
        Accepted Requests By The Webteam User and Other Users that have common connections as Webteam User.

        Webteam user connections =
    */

    private function acceptedRequests()
    {
        $req = new ConnectionRequest();
        $req->sender_id = fetchUserId('nachbaur@mac.com');
        $req->receiver_id = fetchUserId('webteam@aol.com');
        $req->common_connections = fetchCommonConnections(fetchUserId('webteam@aol.com'), fetchUserId('nachbaur@mac.com'));
        $req->status = STATUS_REQUEST_ACCEPTED;
        $req->save();

        $req = new ConnectionRequest();
        $req->sender_id = fetchUserId('ducasse@hotmail.com');
        $req->receiver_id = fetchUserId('webteam@aol.com');
        $req->common_connections = fetchCommonConnections(fetchUserId('webteam@aol.com'), fetchUserId('ducasse@hotmail.com'));
        $req->status = STATUS_REQUEST_ACCEPTED;
        $req->save();

        $req = new ConnectionRequest();
        $req->sender_id = fetchUserId('nachbaur@mac.com');
        $req->receiver_id = fetchUserId('ducasse@hotmail.com');
        $req->common_connections = fetchCommonConnections(fetchUserId('ducasse@hotmail.com'), fetchUserId('nachbaur@mac.com'));
        $req->status = STATUS_REQUEST_ACCEPTED;
        $req->save();

    }
}
