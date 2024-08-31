<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;
use App\Services\AsteriskDB;


class Account_Courses_Exit_Confirm extends Screen
{


    protected function message(): string
    {
        return "Confirm you want to drop course: " . $this->payload("selected_course");
    }

    /**
     * Add options to the screen
     * @return array
     */
    protected function options(): array
    {
        return ['Confirm', 'Cancel'];
    }


    public function previous(): Screen
    {
        return new Welcome($this->request);
    }


    protected function execute(): mixed
    {
        switch ($this->value()) {

            case 'Confirm':

                $result = (new AsteriskDB())->exitCourse($this->request->msisdn, $this->payload("selected_course_id"));

                if ($result) throw new UssdException($this->request, "Course dropped successfully!");

                throw new UssdException($this->request, "Something went wrong, please try again later");

            case 'Cancel':

                throw new UssdException($this->request, "Course not dropped!");

            default:
                throw new UssdException($this->request, "Please try again later");
        }
    }
}
