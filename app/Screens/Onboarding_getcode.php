<?php


namespace App\Screens;


use TNM\USSD\Screen;
use App\Services\AsteriskDB;

class Onboarding_getcode extends Screen
{
    
    protected AsteriskDB $service;
    public function __construct($request)
    {
        parent::__construct($request);
        $this->service = new AsteriskDB();
        $this->service->addNameRoleToUser($this->request->msisdn, $this->payload("f_name"), $this->payload("user_role"));
    }
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
        return new Onboarding_usertype($this->request);
    }

    protected function execute(): mixed
    {
     
        $this->addPayload('joining_code', $this->value());
        return (new Onboarding_Join_Confirm($this->request))->render();
    }
}
