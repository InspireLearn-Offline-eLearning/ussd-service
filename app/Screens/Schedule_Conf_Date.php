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
        return ['Today', 'Tomorrow', 'Enter date'];
    }

    public function previous(): Screen
    {
        return new Schedule_Conf_Class($this->request);
    }

    protected function execute(): mixed
    {
        if ($this->value() === 'Today') {
            $this->addPayload('conf_date', date('Y-m-d'));
            return (new Schedule_Conf_Time($this->request))->render();
        } elseif ($this->value() === 'Tomorrow') {

            $this->addPayload('conf_date', date('Y-m-d', strtotime('+1 day')));
            return (new Schedule_Conf_Time($this->request))->render();
        }
        return (new Schedule_Conf_SetDate($this->request))->render();
    }
}
