<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;

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

 
    protected function execute() : mixed
    {
        switch ($this->value()) {

            case 'Confirm':
                return (new Account_Courses_Exit_Status($this->request))->render();

            case 'Cancel':

                throw new UssdException($this->request, "Course not dropped!");

            default:
                throw new UssdException($this->request, "Please try again later");
        }
    }
}
