<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Jonne Cley de Carvalho Silva',
            'email' => 'carvalhojccs@fab.mil.br',
            'password' => bcrypt('123456'),
        ]);
        
        User::create([
            'name' => 'Laudicéia Lima Duarte',
            'email' => 'laudiceialld@fab.mil.br',
            'password' => bcrypt('123456'),
        ]);
    }
}
