<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Classes_conferences extends Screen
{

    /**
     * Add message to the screen
     *
     * @return string
     */
    protected function message(): string
    {
        return "Classes and Conferences";
    }

    /**
     * Add options to the screen
     * @return array
     */
    protected function options(): array
    {
        return ['View/Update','Schedule New'];
    }

    /**
    * Previous screen
    * return Screen $screen
    */
    public function previous(): Screen
    {
        return new Home($this->request);
    }

    /**
     * Execute the selected option/action
     *
     * @return mixed
     */
    protected function execute():mixed
    {
        // TODO: Implement execute() method.
    }
}
