<?php

namespace Database\Seeders;

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
        DB::table('requests')->truncate();
        DB::table('requests')->insert([
            // Sent Requests
            [
                'id' => 2,
                'sender_id' => 1,
                'receiver_id ' => 2,
                'status ' => STATUS_REQUEST_SENT,
                'common_connections ' => 2
            ],
            // [
            //     'sender_id' => fetchUserId('webteam@aol.com'),
            //     'receiver_id ' => fetchUserId('catalog@outlook.com'),
            //     'status ' => STATUS_REQUEST_SENT,
            //     'common_connections ' => fetchCommonConnections(fetchUserId('catalog@outlook.com'), fetchUserId('webteam@aol.com'))
            // ],
            // [
            //     'sender_id' => fetchUserId('webteam@aol.com'),
            //     'receiver_id ' => fetchUserId('akoblin@verizon.net'),
            //     'status ' => STATUS_REQUEST_SENT,
            //     'common_connections ' => fetchCommonConnections(fetchUserId('akoblin@verizon.net'), fetchUserId('webteam@aol.com'))
            // ],
            // [
            //     'sender_id' => fetchUserId('webteam@aol.com'),
            //     'receiver_id ' => fetchUserId('arnold@mac.com'),
            //     'status ' => STATUS_REQUEST_SENT,
            //     'common_connections ' => fetchCommonConnections(fetchUserId('arnold@mac.com'), fetchUserId('webteam@aol.com'))
            // ],
            // [
            //     'sender_id' => fetchUserId('webteam@aol.com'),
            //     'receiver_id ' => fetchUserId('spadkins@optonline.net'),
            //     'status ' => STATUS_REQUEST_SENT,
            //     'common_connections ' => fetchCommonConnections(fetchUserId('spadkins@optonline.net'), fetchUserId('webteam@aol.com'))
            // ],

        ]);

        Schema::enableForeignKeyConstraints();
    }
}
