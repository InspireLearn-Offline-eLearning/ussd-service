<?php

namespace App\Services;

use App\Models\Asterisk_User;
use App\Models\Asterisk_Conference;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

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

    public function addNameRoleToUser($phoneNumber, $f_name, $role): int
    {
        return Asterisk_User::query()->where('phone', $phoneNumber)->update(['f_name' => $f_name, 'role' => $role]);
    }

    public function createConference($phoneNumber, $conf_schedule, $conf_course, $conf_class_id): Asterisk_Conference
    {

        do {
            $code = mt_rand(1000, 9999);

            $exists = Asterisk_Conference::where('conference_id', $code)->exists();
            
        } while ($exists);

        return Asterisk_Conference::query()->create(['conference_id' => $code, 'organiser_id' => $phoneNumber, 'schedule' => $conf_schedule, 'course_id' => $conf_course, 'class_id' => $conf_class_id]);
    }
}
