<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;
use App\Services\AsteriskDB;

class Onboarding_usertype extends Screen
{

    protected AsteriskDB $service;
    protected string $screen_message;
    protected array $screen_options;
    public function __construct($request)
    {
        parent::__construct($request);
        $this->service = new AsteriskDB();
        $this->screen_message = "Please select your role:";
        if ($this->payload("registered_user_role") === "0") $this->screen_options = ['Student', 'Teacher', 'Guest'];

        $this->screen_options = ['Student', 'Teacher'];
    }
    protected function message(): string
    {
        return $this->screen_message;
    }

    /**
     * Add options to the screen
     * @return array
     */
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


        if ($this->payload("registered_user_role") === "0") return new Onboarding_getname($this->request);
        return new Account_ClassesCourses_Manage($this->request);
    }


    protected function execute(): mixed
    {


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
                if ($this->payload("registered_user_role") === "0") $this->service->addNameRoleToUser($this->request->msisdn, $this->payload("f_name"), $this->payload("user_role"));

                return (new Onboarding_getcode($this->request))->render();

            default:
                throw new UssdException($this->request, "Something went wrong, Please try again later");
        }
    }
}
