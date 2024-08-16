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

    public function getFirstName($phoneNumber)
    {
        $result = Asterisk::where('phone', $phoneNumber)->first();

        if ($result) {
            return $result;
        }

        return null;
    }
    public function createUser($phoneNumber): Asterisk
    {
        $randFirstName = Faker::create()->firstName();
        return Asterisk::query()->create(['phone' => $phoneNumber, 'f_name' => $randFirstName]);
    }
}
