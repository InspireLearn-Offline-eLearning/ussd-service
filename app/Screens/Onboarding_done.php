<?php


namespace App\Screens;


use TNM\USSD\Screen;
use App\Services\AsteriskDB;

class Onboarding_done extends Screen
{
    protected AsteriskDB $service;
    protected string $screen_message;
    protected array $screen_options = ['Bundles','Classes/Conferences','Account'];
    protected $screen_previousScreen;
    public function __construct($request)
    {
        parent::__construct($request);
        $this->service = new AsteriskDB();
        $created_user = $this->service->addNameRoleToUser($this->request->msisdn,$this->payload("f_name"),$this->payload("user_role"));
        if ($created_user > 0){
            $this->screen_message ="All set! Use the Account option to update and add more info.";
        }
        else{
            $this->screen_message = "Name and role not updated, Use the Account option to update and add more info. ";
        }
    }
    
    protected function message(): string
    {
        return $this->screen_message;
    }

    protected function options(): array
    {
        return $this->screen_options;
    }

    /**
    * Previous screen
    * return Screen $screen
    */
    public function previous(): Screen
    {
        return new Onboarding_done($this->request);
    }

    protected function execute(): mixed
    {

        if ($this->value() === 'Bundles') {
            return (new Bundles($this->request))->render();
        }
        elseif ($this->value() === 'Classes/Conferences') {
            return (new Classes_conferences($this->request))->render();
        }
        else return (new Account($this->request))->render();
    }

    public function goesBack(): bool
    {
        return false;
    }
}
