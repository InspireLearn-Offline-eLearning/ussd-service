<?php


namespace App\Screens;


use TNM\USSD\Screen;
use App\Services\AsteriskDB;

class Onboarding_getcode extends Screen
{

    protected function message(): string
    {
        return "Please enter joining code";
    }

    protected function options(): array
    {
        return [];
    }

    public function previous(): Screen
    {
        if ($this->payload("registered_user_role") === "0") return new Onboarding_usertype($this->request);
        return new Account_ClassesCourses_Manage($this->request);
    }

    protected function execute(): mixed
    {

        $this->addPayload('joining_code', $this->value());
        return (new Onboarding_Join_Confirm($this->request))->render();
    }
}
