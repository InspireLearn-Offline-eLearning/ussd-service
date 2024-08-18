<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Schedule_Conf_Date extends Screen
{

    protected function message(): string
    {
        return "Schedule conference for?";
    }

    protected function options(): array
    {
        return ['Today','Tomorrow','Enter date'];
    }

    public function previous(): Screen
    {
        return new Schedule_Conf_Class($this->request);
    }

    protected function execute():mixed
    {
        $this->addPayload('conf_date', $this->value());
        return (new Schedule_Conf_Time($this->request))->render();
    }
}
