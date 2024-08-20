<?php


namespace App\Screens;


use TNM\USSD\Screen;
use App\Services\AsteriskDB;
use TNM\USSD\Exceptions\UssdException;
class Schedule_Conf_Confirm extends Screen
{
    protected AsteriskDB $service;

    protected function message(): string
    {
        return  sprintf("Schedule %s, for %s @ %s?", $this->payload("conf_class"), $this->payload("conf_date"), $this->payload("conf_time"));
    }


    protected function options(): array
    {
        return ['Confirm', 'Cancel'];
    }


    public function previous(): Screen
    {
        return new Schedule_Conf_Time($this->request);
    }


    protected function execute(): mixed
    {
        if ($this->value() === 'Confirm') {

            $this->service = new AsteriskDB();
            $formated_schedule_date = $this->payload('conf_date') . ' ' . $this->payload('conf_time') . ':' . '00';
            \Log::debug('formatted date: ', ['value' => $formated_schedule_date]);
            $result = $this->service->createConference($this->request->msisdn, $formated_schedule_date, 'course101', 'class101');
            if ($result != null) {
                return (new Schedule_Conf_Confirm_Status($this->request))->render();
            }
            return throw new UssdException($this->request, "Coudn't schedule conference, Please try again later");
        } else {
            return (new Welcome($this->request))->render();
        }
    }
}
