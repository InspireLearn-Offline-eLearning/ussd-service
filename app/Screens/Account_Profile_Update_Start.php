<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;

class Account_Profile_Update_Start extends Screen
{

    /**
     * Add message to the screen
     *
     * @return string
     */
    protected function message(): string
    {
        return "To serve you better, please ensure all your profile details are accurate.";
    }

    /**
     * Add options to the screen
     * @return array
     */
    protected function options(): array
    {
        return ['Continue', 'Cancel'];
    }


    public function previous(): Screen
    {
        return new Welcome($this->request);
    }

    protected function execute(): mixed
    {
        switch ($this->value()) {

            case 'Continue':
                return (new Account_Profile_Update_Lname($this->request))->render();

            case 'Cancel':
                return (new Welcome($this->request))->render();

            default:
                throw new UssdException($this->request, "Incorrect input, please try again later");
        }
    }
}
