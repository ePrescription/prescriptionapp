<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('roles')->truncate();

        $roles = [
            ['id' => 1, 'name' => 'admin', 'display_name' => 'admin', 'description' => 'admin' , 'created_at'=> new DateTime],
            ['id' => 2, 'name' => 'hospital', 'display_name' => 'hospital', 'description' => 'hospital','created_at'=> new DateTime ],
            ['id' => 3, 'name' => 'doctor', 'display_name' => 'doctor', 'description' => 'doctor','created_at'=> new DateTime ],
            ['id' => 4, 'name' => 'patient', 'display_name' => 'patient', 'description' => 'patient' ,'created_at'=> new DateTime ],
            ['id' => 5, 'name' => 'lab', 'display_name' => 'lab', 'description' => 'lab' ,'created_at'=> new DateTime ],
            ['id' => 6, 'name' => 'pharmacy', 'display_name' => 'pharmacy', 'description' => 'pharmacy' ,'created_at'=> new DateTime ],
        ];

        DB::table('roles')->insert($roles);
    }
}
