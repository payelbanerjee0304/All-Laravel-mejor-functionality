<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AssignedUser;

class AssignedUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        
        $genders = ['male', 'female', 'other'];
        $states = ['West Bengal', 'Maharastra', 'Delhi'];
        $districts = ['District 1', 'District 2', 'District 3'];
        $cities = ['City A', 'City B', 'City C'];

        for ($i = 0; $i < 30000; $i++) {
            AssignedUser::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'password' => md5('123456'), // Default password
                'gender' => $faker->randomElement($genders),
                'state' => $faker->randomElement($states),
                'district' => $faker->randomElement($districts),
                'city' => $faker->randomElement($cities),
            ]);
        }
    }
}
