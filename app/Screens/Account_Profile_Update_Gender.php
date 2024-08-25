<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Account_Profile_Update_Gender extends Screen
{

    protected function message(): string
    {
        return "Select your sex";
    }

    protected function options(): array
    {
        return ['Male', 'Female'];
    }


    public function previous(): Screen
    {
        return new Welcome($this->request);
    }

    protected function execute(): mixed
    {

        $this->addPayload("dob_error", "0"); //to initialise for the data variable next screen

        if ($this->value() ===  "Male") {

            $this->addPayload('gender', 'male');
        } else $this->addPayload('gender', 'female');

        return (new Account_Profile_Update_Dob($this->request))->render();

    }
}
