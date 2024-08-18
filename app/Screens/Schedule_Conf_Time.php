<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Schedule_Conf_Time extends Screen
{

    /**
     * Add message to the screen
     *
     * @return string
     */
    protected function message(): string
    {
        return "Scheduling conference, enter time (mmhh)";
    }

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
        return new Schedule_Conf_Date($this->request);
    }

    protected function execute(): mixed
    {
        $this->addPayload('conf_time', $this->value());
        return (new Schedule_Conf_Confirm($this->request))->render();
    }
}
