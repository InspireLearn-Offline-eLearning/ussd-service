<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Schedule_Conf_Confirm_Status extends Screen
{


    protected function message(): string
    {
        return " Conference scheduled successfully!";
    }

    protected function options(): array
    {
        return [];
    }

 
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
