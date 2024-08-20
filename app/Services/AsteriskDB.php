<?php

namespace App\Services;

use App\Models\Asterisk_User;
use Faker\Factory as Faker;

class AsteriskDB
{
    public function validate(string $phoneNumber): mixed
    {
            return Asterisk_User::where('phone', $phoneNumber)->first();
     
    }

    public function createUser($phoneNumber): Asterisk_User
    {
        $randFirstName = Faker::create()->firstName();
        return Asterisk_User::query()->create(['phone' => $phoneNumber, 'f_name' => $randFirstName]);
    }

    public function addNameRoleToUser($phoneNumber,$f_name,$role): int
    {
        return Asterisk_User::query()->where('phone',$phoneNumber)->update(['f_name'=>$f_name,'role'=>$role]);
    }
}
