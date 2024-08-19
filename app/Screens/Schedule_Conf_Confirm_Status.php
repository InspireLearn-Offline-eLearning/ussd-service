<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Schedule_Conf_Confirm_Status extends Screen
{

    /**
     * Add message to the screen
     *
     * @return string
     */
    protected function message(): string
    {
        return " Conference scheduled successfully!";
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

    protected function execute(): mixed
    {
        // TODO: Implement execute() method.
    }

    public function goesBack(): bool
    {
        return false;
    }

    public function acceptsResponse(): bool
    {
        return false;
    }
}
