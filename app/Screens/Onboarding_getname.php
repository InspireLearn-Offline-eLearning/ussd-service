<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Onboarding_getname extends Screen
{

    /**
     * Add message to the screen
     *
     * @return string
     */
    protected function message(): string
    {
        return "Enter your first name";
    }

    /**
     * Add options to the screen
     * @return array
     */
    protected function options(): array
    {
        return [];
    }

    /**
     * Previous screen
     * return Screen $screen
     */
    public function previous(): Screen
    {
        return new Welcome($this->request);
    }

    /**
     * Execute the selected option/action
     *
     * @return mixed
     */
    protected function execute(): mixed
    {
        // TODO: Implement execute() method.
        $this->addPayload('f_name', $this->value());
        return (new Onboarding_usertype($this->request))->render();

    }
    public function goesBack(): bool
    {
        return false;
    }
}
