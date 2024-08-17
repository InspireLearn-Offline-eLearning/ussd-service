<?php

namespace App\Services;

use App\Models\Asterisk;
use Faker\Factory as Faker;

class AsteriskDB
{
    public function validate(string $phoneNumber): mixed
    {
            return Asterisk::where('phone', $phoneNumber)->first();
     
    }

    public function createUser($phoneNumber): Asterisk
    {
        $randFirstName = Faker::create()->firstName();
        return Asterisk::query()->create(['phone' => $phoneNumber, 'f_name' => $randFirstName]);
    }

    public function addNameRoleToUser($phoneNumber,$f_name,$role): int
    {
        return Asterisk::query()->where('phone',$phoneNumber)->update(['f_name'=>$f_name,'role'=>$role]);
    }
}
