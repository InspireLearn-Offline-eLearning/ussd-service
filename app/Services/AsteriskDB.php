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


    public function getConferences($phoneNumber)
    {
        $avail = Asterisk_Conference::query()->where('organiser_id', $phoneNumber)->limit(3)->get();

        $avail->pluck('course_id')->toArray();

        $formattedCourses = $avail->map(function ($conference) {
            $currentDate = now()->startOfDay();
            $conferenceDate = $conference->schedule->startOfDay();

            if ($conferenceDate->equalTo($currentDate)) {
                // If the conference is today
                $suffix = ' today @' . $conference->schedule->format('H:i');
            } elseif ($conferenceDate->equalTo($currentDate->copy()->addDay())) {
                // If the conference is tomorrow
                $suffix = ' tomorrow @' . $conference->schedule->format('H:i');
            } else {
                // If the conference is after tomorrow
                $suffix = ' on ' . $conference->schedule->format('D, d M'); //'l, F j': Formats the date as a readable string, e.g., "Friday, August 19".
            }

            return $conference->course_id . $suffix;
        })->toArray();

        return $formattedCourses;
    }
}
