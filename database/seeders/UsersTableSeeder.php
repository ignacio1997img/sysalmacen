<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'role_id' => 1,
                'name' => 'Admin',
                'funcionario_id' => 19448,
                'email' => 'admin@admin.com',
                'avatar' => 'users/default.png',
                'email_verified_at' => NULL,
                'password' => '$2y$10$piw47ZDCJq3ieZXMVap/2eo1j71yvn4g2LNCxJu7b/7JEgq/8Q4oG',
                'remember_token' => NULL,
                'settings' => '{"locale":"es"}',
                'created_at' => '2021-06-01 21:05:11',
                'updated_at' => '2022-03-02 16:18:02',
            ),
        ));
        
        
    }
}