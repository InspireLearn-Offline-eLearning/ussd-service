<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Onboarding_usertype extends Screen
{

    /**
     * Add message to the screen
     *
     * @return string
     */
    protected function message(): string
    {
        return "You are joining as a ...";
    }

    /**
     * Add options to the screen
     * @return array
     */
    protected function options(): array
    {
        return ['Student', 'Teacher', 'Other'];
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
        // TODO: Implement execute() method.
        $this->addPayload('usertype', $this->value());
        
        if ($this->value() === 'Other')  return (new Onboarding_done($this->request))->render();

        else return (new Onboarding_getcode($this->request))->render();
    }
}
