<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;
use TNM\USSD\Http\Validates;


class Account_Profile_Update_Lname extends Screen
{

    use Validates;
    protected function message(): string
    {
        return "Enter your last name";
    }

    protected function options(): array
    {
        return [];
    }


    public function previous(): Screen
    {
        return new Welcome($this->request);
    }

    protected function execute(): mixed
    {
        $this->validate($this->request,'last name');
        $this->addPayload('l_name', $this->value());
        return (new Account_Profile_Update_Gender($this->request))->render();
    }
    protected function rules() : string
    {
        return 'regex:/^[a-zA-Z][a-z]{2,44}$/';
    }
    public function goesBack(): bool
    {
        return false;
    }
}
