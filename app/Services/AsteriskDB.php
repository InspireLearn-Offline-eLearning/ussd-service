<?php

namespace App\Services;

use App\Models\Asterisk_ClassRegistration;
use App\Models\Asterisk_CourseRegistration;
use App\Models\Asterisk_User;
use App\Models\Asterisk_Conference;
use App\Models\Asterisk_Courses;
use App\Models\Asterisk_InvalidCodeAttempts;
use App\Models\Asterisk_UserRequests;
use Carbon\Carbon;
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
    public function updateUser($user_id, $l_name, $gender, $dob, $special_need)
    {

        $user = Asterisk_User::where('user_id', $user_id)->first();

        if ($user) {

            $user->l_name = $l_name;
            $user->sex = $gender;
            $user->dob = $dob;
            if ($special_need != "none") {
                $user->special_needs = $special_need;
            }
            $user->save();

            return true;
        } else {

            return false;
        }
    }

    public function addNameRoleToUser($phoneNumber, $f_name, $role): int
    {
        return Asterisk_User::query()->where('phone', $phoneNumber)->update(['f_name' => $f_name, 'role' => $role]);
    }


    public function exitCourses($user_id, $class_id, $course_id = null)
    {

        if ($course_id === null) {

            $deletedrows = Asterisk_CourseRegistration::where('user_id', $user_id)
                ->whereHas('course', function ($query) use ($class_id) {
                    $query->where('class_id', $class_id);
                })
                ->delete();

            if ($deletedrows > 0) {
                Asterisk_ClassRegistration::where('user_id', $user_id)
                    ->where('class_id', $class_id)
                    ->delete();

                return true;
            }

            return false;
        }

        // implement when a course_id has been provided
    }
    public function createConference($phoneNumber, $conf_schedule, $conf_course, $conf_class_id): Asterisk_Conference
    {

        do {
            $code = mt_rand(1000, 9999);

            $exists = Asterisk_Conference::where('conference_id', $code)->exists();
        } while ($exists);

        return Asterisk_Conference::query()->create(['conference_id' => $code, 'organiser_id' => $phoneNumber, 'schedule' => $conf_schedule, 'course_id' => $conf_course, 'class_id' => $conf_class_id]);
    }


    public function updateConference($conference_id, $conf_schedule)
    {

        $conference = Asterisk_Conference::where('conference_id', $conference_id)->first();

        if ($conference) {

            $conference->schedule = $conf_schedule;
            $conference->status = 'rescheduled';

            $conference->save();

            return true;
        } else {

            return false;
        }
    }
    public function getConferences($phoneNumber)
    {
        // $avail = Asterisk_Conference::query()->where('organiser_id', $phoneNumber)->limit(3)->get();
        $avail = Asterisk_Conference::query()
            ->join('course_registration', 'conference.course_id', '=', 'course_registration.course_id')
            ->join('course', 'course.course_id', '=', 'conference.course_id')
            ->where('course_registration.user_id', '=', $phoneNumber)
            ->where('conference.schedule', '>=', now()->startOfDay())
            ->select('conference.conference_id', 'course.name', 'conference.schedule')
            ->orderBy('conference.schedule', 'asc')
            ->limit(3)
            ->get();

        $formattedCourses = $avail->map(function ($conference) {
            $currentDate = now()->startOfDay();
            $conferenceDate = Carbon::parse($conference['schedule'])->startOfDay();

            if ($conferenceDate->equalTo($currentDate)) {
                $suffix = ' today @' . Carbon::parse($conference['schedule'])->format('H:i');
            } elseif ($conferenceDate->equalTo($currentDate->copy()->addDay())) {
                $suffix = ' tomorrow @' . Carbon::parse($conference['schedule'])->format('H:i');
            } else {
                $suffix = ' on ' . Carbon::parse($conference['schedule'])->format('D, d M');
            }

            return $conference->conference_id . "> " . $conference->name . $suffix;
        })->toArray();

        return $formattedCourses;
    }

    public function getConferenceOrganiser($conference_id)
    {
        return Asterisk_Conference::where("conference_id", $conference_id)->first();
    }

    public function getUserClassList($user_id)
    {
        $avail = Asterisk_ClassRegistration::query()
            ->join('class', 'class_registration.class_id', '=', 'class.class_id')
            ->join('school', 'class.school_id', '=', 'school.school_id')
            ->where('class_registration.user_id', $user_id)
            ->select('class.class_id as class_id', 'class.name as class_name', 'school.name as school_name')
            ->get();

        $classlist = $avail->map(function ($class_registration) {

            return $class_registration->class_id . ": " . $class_registration->class_name . " @ " . $class_registration->school_name;
        })->toArray();

        return $classlist;
    }
    public function getUserCourseList($user_id, $class_id)
    {
        $avail = Asterisk_CourseRegistration::query()
            ->join('course', 'course_registration.course_id', '=', 'course.course_id')
            ->where('course_registration.user_id', $user_id)
            ->where('course.class_id', $class_id)
            ->select('course_registration.course_id as course_id', 'course.name as name') // Select the columns you need from the course table
            ->get();

        $courselist = $avail->map(function ($course_registration) {

            return $course_registration->course_id . ":  " . $course_registration->name;
        })->toArray();

        return $courselist;
    }
    public function updateConferenceStatus($conference_id, $newStatus)
    {

        $conference = Asterisk_Conference::where('conference_id', $conference_id)->first();

        if ($conference) {
            $conference->status = $newStatus;
            $conference->save();
            return true;
        } else {
            return false;
        }
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
