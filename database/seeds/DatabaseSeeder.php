<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = $this->getSeedUsers();
        $size = count($users);
        for ($i = 0; $i < $size; $i++) {
            DB::table('users')->insert($users[$i]);
        }

        $court = $this->getSeedCourt();
        DB::table('courts')->insert($court);

        $reserves = $this->getSeedReserves();
        $size = count($reserves);
        for ($i = 1; $i <= $size; $i++) {
            DB::table('reserves')->insert($reserves[$i]);
        }

    }

    public function getSeedUsers()
    {
        $users[0] = [
            'name' => 'test1',
            'email' => 'aaa@aaa',
            'email_verified_at' => null,
            'password' => '1234'
        ];

        $users[1] = [
            'name' => 'test2',
            'email' => 'bbb@bbb',
            'email_verified_at' => null,
            'password' => '1234'
        ];

        $users[2] = [
            'name' => 'test3',
            'email' => 'ccc@ccc',
            'email_verified_at' => null,
            'password' => '1234'
        ];
        

        return $users;
    }

    public function getSeedCourt()
    {
        return [
            'type' => 1
        ];
    }

    public function getSeedReserves()
    {
        $userIds = [1, 2, 3];
        $courtId = 1;
        $tmpDateTime = "2020-12-07 09:00:00";
        $reserves = [];
        for ($i = 1; $i <= 20; $i++) {
            $reserves[$i]['user_id'] = $userIds[$i % 3];
            $reserves[$i]['court_id'] = $courtId;
            $tmpDateTime = date('Y-m-d H:i:s', strtotime($tmpDateTime));
            $reserves[$i]['start_time'] = $tmpDateTime;
            $tmpDateTime = date('Y-m-d H:i:s', strtotime($tmpDateTime . "+30 minute"));
            $reserves[$i]['end_time'] = $tmpDateTime;
        }

        return $reserves;
    }
}
