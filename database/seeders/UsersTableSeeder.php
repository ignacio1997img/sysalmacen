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
                'funcionario_id' => 0,
                'email' => 'admin@admin.com',
                'avatar' => 'users/default.png',
                'email_verified_at' => NULL,
                'password' => '$2y$10$piw47ZDCJq3ieZXMVap/2eo1j71yvn4g2LNCxJu7b/7JEgq/8Q4oG',
                'remember_token' => NULL,
                'settings' => NULL,
                'created_at' => '2021-06-01 21:05:11',
                'updated_at' => '2021-06-01 21:05:11',
            ),
            1 => 
            array (
                'id' => 2,
                'role_id' => 4,
                'name' => 'darient',
                'funcionario_id' => 19448,
                'email' => 'darient@gmail.com',
                'avatar' => 'users/default.png',
                'email_verified_at' => NULL,
                'password' => '$2y$10$Hv5PnLocoq2vRW053iXvLeoZNk6KcZt9EXqEyPqllJQJfza4qIobG',
                'remember_token' => NULL,
                'settings' => '{"locale":"es"}',
                'created_at' => '2022-02-13 22:45:27',
                'updated_at' => '2022-02-13 22:45:27',
            ),
            2 => 
            array (
                'id' => 3,
                'role_id' => 3,
                'name' => 'marcelo',
                'funcionario_id' => 21379,
                'email' => 'marcelo@gmail.com',
                'avatar' => 'users/default.png',
                'email_verified_at' => NULL,
                'password' => '$2y$10$bYy8Ny1haCj2u4e0QKNzQujL5zEcYTU7SWSugc0JAzmS37ZoRMY8G',
                'remember_token' => NULL,
                'settings' => '{"locale":"es"}',
                'created_at' => '2022-02-13 22:46:29',
                'updated_at' => '2022-02-14 12:42:49',
            ),
            3 => 
            array (
                'id' => 4,
                'role_id' => 5,
                'name' => 'max',
                'funcionario_id' => 17784,
                'email' => 'max@gmail.com',
                'avatar' => 'users/default.png',
                'email_verified_at' => NULL,
                'password' => '$2y$10$480GN6kz8HdbVjPSe.BGQOcaPyKVuBEWeYajz4HXlNSEtgCZ7ChU2',
                'remember_token' => NULL,
                'settings' => '{"locale":"es"}',
                'created_at' => '2022-02-13 22:47:18',
                'updated_at' => '2022-02-14 13:36:47',
            ),
            4 => 
            array (
                'id' => 5,
                'role_id' => 3,
                'name' => 'mario',
                'funcionario_id' => 21067,
                'email' => 'mario@gmail.com',
                'avatar' => 'users/default.png',
                'email_verified_at' => NULL,
                'password' => '$2y$10$s.kif0Ebxzdooj0EUqxakuyjBatqsNHKTuRDU9Dz.rGdnsFMduypi',
                'remember_token' => NULL,
                'settings' => '{"locale":"es"}',
                'created_at' => '2022-02-13 22:49:20',
                'updated_at' => '2022-02-13 22:49:20',
            ),
        ));
        
        
    }
}