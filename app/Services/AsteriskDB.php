<?php

namespace App\Services;

use App\Models\Asterisk;
use Faker\Factory as Faker;
class AsteriskDB
{
    public function validate(string $phoneNumber): mixed
    {
        // Implement your validation logic here
        // For example, checking if the number is allowed to proceed
        // return in_array($phoneNumber, ['1234567890', '0987654321', '265888800900']); // Example numbers
        return Asterisk::where('phone', $phoneNumber)->first();
    }

    public function getFirstName($phoneNumber)
    {
        $result = Asterisk::where('phone', $phoneNumber)->first();

        if ($result) {
            return $result->f_name;
        }

        return null;
    }
    public function createUser($phoneNumber): Asterisk
    {
        $randFirstName = Faker::create()->firstName();
        return Asterisk::query()->create(['phone' => $phoneNumber,'f_name'=> $randFirstName]);
    }
}
