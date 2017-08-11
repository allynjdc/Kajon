<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        // $this->call(UsersTableSeeder::class);

        factory(App\User::class, 4)->create();
        factory(App\Admin::class, 4)->create();
        factory(App\Document::class, 200)->create();
        // factory(App\Reminder::class, 4)->create();
        // factory(App\ComplianceUser::class, 8)->create();
        // factory(App\ComplianceMunicipality::class, 8)->create(); 
    }
}
