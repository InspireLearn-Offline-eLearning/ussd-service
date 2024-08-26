<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;
use App\Services\AsteriskDB;

class Onboarding_usertype extends Screen
{

    protected AsteriskDB $service;
    public function __construct($request)
    {
        parent::__construct($request);
        $this->service = new AsteriskDB();
    }
    protected function message(): string
    {
        return "Please select your role:";
    }

    /**
     * Add options to the screen
     * @return array
     */
    protected function options(): array
    {
        return ['Student', 'Teacher', 'Guest'];
    }

    /**
     * Previous screen
     * return Screen $screen
     */
    public function previous(): Screen
    {
        return new Onboarding_getname($this->request);
    }

    /**
     * Execute the selected option/action
     *
     * @return mixed
     */
    protected function execute(): mixed
    {

        $this->addPayload("registered_user_role","0");

        switch ($this->value()) {

            case 'Guest':
                $this->addPayload('user_role', 'guest');
                return (new Onboarding_done($this->request))->render();

            case 'Teacher':
                $this->addPayload('user_role', 'teacher');
                $this->service->addNameRoleToUser($this->request->msisdn, $this->payload("f_name"), $this->payload("user_role"));
                return (new Onboarding_getcode($this->request))->render();

            case 'Student':
                $this->addPayload('user_role', 'student');
                $this->service->addNameRoleToUser($this->request->msisdn, $this->payload("f_name"), $this->payload("user_role"));
                return (new Onboarding_getcode($this->request))->render();

            default:
                throw new UssdException($this->request, "Something went wrong, Please try again later");
        }
    }
}
