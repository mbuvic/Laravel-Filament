<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();
        $users = array(
            array('id' => 1,'name' => 'Charles Mbuvi' ,'email' => "charlesmbuvi08@gmail.com",'password' => '$2y$12$n5uN7ZtInBCAYfBihYwfquLgno0zpTPay2efmqIy3XqrME8b95NNa')
            );
        DB::table('users')->insert($users);
    }
}
