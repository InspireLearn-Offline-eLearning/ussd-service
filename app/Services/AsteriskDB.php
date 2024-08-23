<?php

namespace App\Services;

use App\Models\Asterisk_ClassRegistration;
use App\Models\Asterisk_CourseRegistration;
use App\Models\Asterisk_User;
use App\Models\Asterisk_Conference;
use App\Models\Asterisk_Courses;
use App\Models\Asterisk_InvalidCodeAttempts;
use App\Models\Asterisk_UserRequests;
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

        return Asterisk_User::query()->create(['phone' => $phoneNumber, 'user_id' => $phoneNumber]);
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


    public function joinCodeValidation($code, $phone)
    {
        $timeLimit = now()->subDays(3);
        $maxfailedAttempts = 2;
        $maxCapacity = 2; // Example threshold

        $recentFailedAttempts = Asterisk_InvalidCodeAttempts::where('phone', $phone)
            ->where('attempted_at', '>=', now()->subMinutes(30))
            ->count();

        if ($recentFailedAttempts >= $maxfailedAttempts) {
            return [
                'status' => false,
                'message' => "Too many failed attempts. Please try again later."
            ];
        }

        $course = Asterisk_Courses::with(['class.school'])->where('joining_key', $code)->first();

        if ($course) {
            if ($course->created_at < $timeLimit) {
                return [
                    'status' => false,
                    'message' => "Registration expired!"
                ];
            }



            $currentRegistrations = Asterisk_CourseRegistration::where('course_id', $course->course_id)->count();

            // Check if the course has reached the maximum capacity
            if ($currentRegistrations >= $maxCapacity) {
                return [
                    'status' => false,
                    'message' => "Capacity reached, contact your teacher or school."
                ];
            }

            $classId = $course->class_id;

            // Check if the student is already registered in the class
            $classRegistration = Asterisk_ClassRegistration::where('user_id', $phone)->first();

            if ($classRegistration) {
                if ($classRegistration->class_id != $classId) {
                    return [
                        'status' => false,
                        'message' => "You can't register to another class"
                    ];
                }
            } else {
                // Automatically register the student to the class

                Asterisk_ClassRegistration::create([
                    'class_id' => $classId,
                    'user_id' => $phone,
                    'class_reg_id' => $classId . '_' . $phone
                ]);
            }
            // Register the student to the course
            $existingRegistration = Asterisk_CourseRegistration::where('course_id', $course->id)
                ->where('user_id', $phone)
                ->first();

            if ($existingRegistration) {
                return  [
                    'status' => false,
                    'message' => "You are already registered for this course"
                ];
            }


            return [
                'status' => true,
                'message' => "Registration successful",
                'course' => $course
            ];
        } else {

            Asterisk_InvalidCodeAttempts::create([
                'phone' => $phone,
                'code' => $code,
                'attempt_id' => time(),
                'attempted_at' => now(),
            ]);

            return  [
                'status' => false,
                'message' => "Invalid code"
            ];
        }
    }

    public function joinCourseRegistration($phone, $course_id, $role)
    {

        if ($role == 'teacher') {

            Asterisk_UserRequests::create([
                'request_id' => time(),
                'user_id' => $phone,
                'request_type' => 'role_change',
                'request_data' => '{ "requested_role": "teacher" }',
                'context' => 'course_registration'
            ]);
        }

        return Asterisk_CourseRegistration::create([
            'course_id' => $course_id,
            'user_id' => $phone,
            'role' => 'student',
            'course_reg_id' => $course_id . '_' . $phone
        ]);
    }
}
