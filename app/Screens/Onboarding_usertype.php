<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;

class Onboarding_usertype extends Screen
{

    /**
     * Add message to the screen
     *
     * @return string
     */
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
        switch ($this->value()) {
            
            case 'Guest':
                $this->addPayload('user_role', 'guest');
                return (new Onboarding_done($this->request))->render();

            case 'Teacher':
                $this->addPayload('user_role', 'teacher');
                return (new Onboarding_getcode($this->request))->render();

            case 'Student':
                $this->addPayload('user_role', 'student');
                return (new Onboarding_getcode($this->request))->render();

            default:
                throw new UssdException($this->request, "Something went wrong, Please try again later");
        }
    }
}
