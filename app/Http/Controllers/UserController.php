<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asterisk_User;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        $phone = $request->input('phone');
        $randomNumber = Str::random(4);
        $faker = Faker::create();
        $randomFirstName = $faker->firstName;
        $randomLastName = $faker->lastName;
        $randomEmail = $faker->email;
        $randomDateOfBirth = $faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d');
        $randomPassword = $faker->password;
        $randomPhonne = $faker->phoneNumber;
        try {
            // Insert a new user into the database

            // $user = Asterisk::create([
            //     'phone' => $phone,
            //     'email' => $randomEmail,
            //     'password' => $randomPassword,
            //     'user_id' => $randomNumber,
            //     'f_name' => $randomFirstName,
            //     'l_name' => $randomLastName,
            //     'sex' => 'M',
            //     'dob' => $randomDateOfBirth,
            //     'role' => 'guest',
            //     // Other fields will use default values
            // ]);
            $newUser = new Asterisk_User();
            $newUser->phone = $phone;
            $newUser->user_id = $randomNumber;
            $newUser->f_name = $randomFirstName;
            $newUser->l_name = $randomLastName;
            $newUser->email = $randomEmail;
            $newUser->dob = $randomDateOfBirth;
            $newUser->password = $randomPassword;
            $newUser->sex = 'M';
            $newUser->role = 'guest';
            
            $newUser->save();

            // return response()->json(['user' => $user], 201);
            return $newUser;
            
        } catch (\Exception $e) {
            \Log::error('Error creating user: ' . $e->getMessage());
            return response()->json(['error' => 'User creation failed,'. $e->getMessage()], 500);
        }
    }


    public function getUser(Request $request)
    {
        $phone = $request->input('phone');
        $user = Asterisk_User::where('phone', $phone)->first();
        return response()->json(['user' => $user], 200);
    }
}
