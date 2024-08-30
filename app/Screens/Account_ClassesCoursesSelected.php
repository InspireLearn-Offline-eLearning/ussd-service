<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;


class Account_ClassesCoursesSelected extends Screen
{


    protected function message(): string
    {
        return "You selected class: " . trim(explode(':', $this->payload("selected_class"))[1]);
    }


    protected function options(): array
    {
        return ["View Courses Enrolled","Exit Class"];
    }


    public function previous(): Screen
    {
        return new Welcome($this->request);
    }


    protected function execute(): mixed {

        switch ($this->value()) {

            case 'View Courses Enrolled':

                $this->addPayload("selected_class_id",trim(explode(':', $this->payload("selected_class"))[0]));

                return (new Account_ClassesCourses_Enrolled($this->request))->render();

                case 'Exit Class':

                    return (new Account_ClassesCourses_Exit($this->request))->render();

                default: 
                throw new UssdException($this->request, "Unabled to exit class now Please try again later");
            }
    }
}
