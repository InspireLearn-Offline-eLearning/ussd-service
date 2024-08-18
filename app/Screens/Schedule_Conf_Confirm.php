<?php


namespace App\Screens;


use TNM\USSD\Screen;
class Schedule_Conf_Confirm extends Screen
{

    protected function message(): string
    {
        return  sprintf("Schedule %s, for %s @ %s?", $this->payload("conf_class"), $this->payload("conf_date"), $this->payload("conf_time"));
    }


    protected function options(): array
    {
        return ['Confirm','Cancel'];
    }


    public function previous(): Screen
    {
        return new Schedule_Conf_Time($this->request);
    }


    protected function execute() : mixed
    {
        if ($this->value() === 'Confirm') {
            return (new Schedule_Conf_Confirm_Status($this->request))->render();
        } else {
           return (new Welcome($this->request))->render();
        }
    }
}
