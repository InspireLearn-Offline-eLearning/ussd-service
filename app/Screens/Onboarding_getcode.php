<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Onboarding_getcode extends Screen
{

    /**
     * Add message to the screen
     *
     * @return string
     */
    protected function message(): string
    {
        return "{{message}}";
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
    protected function execute()
    {
        // TODO: Implement execute() method.
    }
}
