<?php


namespace App\Screens;


use TNM\USSD\Screen;
use App\Services\AsteriskDB;
use TNM\USSD\Exceptions\UssdException;

class Schedule_Conf_Confirm extends Screen
{
    protected AsteriskDB $service;


    protected string $screen_message;
    protected array $screen_options;

    public function __construct($request)
    {
        parent::__construct($request);

        if ($this->payload("reschedule") === "1") {

            $this->screen_message = "Reschedule conference to " . $this->payload("conf_date") . " @ " .  $this->payload("conf_time") . "?";
        } else {

            $this->screen_message = "Schedule " . $this->payload("conf_class") . " for " . $this->payload("conf_date") . " @" . $this->payload("conf_time");
        }
    }
    protected function message(): string
    {
        return  $this->screen_message;
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

            if ($this->payload("reschedule")==="1") {
                $result = $this->service->updateConference(trim(explode('>', $this->payload("selected_conference"))[0]), $formated_schedule_date);
                if ($result) {
                    throw new UssdException($this->request, "Conference rescheduled successfully!");
                } else {
                    throw new UssdException($this->request, "Unabled to reschedule, Please try again later");
                }
            }
            $result = $this->service->createConference($this->request->msisdn, $formated_schedule_date, 'course101', 'class101');


            if ($result != null) {
                return throw new UssdException($this->request, "Conference scheduled successfully!");
            }
            return throw new UssdException($this->request, "Coudn't schedule conference, Please try again later");
        } else {
            return (new Welcome($this->request))->render();
        }
    }
}
