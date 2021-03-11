<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = time();


        try {
        // Consistency insurance
        DB::beginTransaction();
            DB::table('roles')->insert([
           ['name' => 'admin','description' => 'Администратор', 'created_at' => $time, 'updated_at' => $time],
           ['name' => 'employee','description' => 'Служител', 'created_at' => $time, 'updated_at' => $time],
           ['name' => 'customer','description' => 'Клиент', 'created_at' => $time, 'updated_at' => $time]
        ]);

        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('#1234qaz@'),
        ]);


        DB::table('role_user')->insert([
            ['role_id' => 1, 'user_id' => 1],
        ]);

        DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
