<?php


namespace App\Screens;


use TNM\USSD\Screen;
use DateTime;
use TNM\USSD\Exceptions\UssdException;

class Schedule_Conf_Time extends Screen
{
    protected string $screen_message;

    public function __construct($request)
    {
        parent::__construct($request);

        \Log::debug('Paylaod in conf time: ', ['value' => $this->payload("reschedule")]);
        if ($this->payload("reschedule") == "1") {

            $this->screen_message = "Reschedule conference to a new time:";

        } else {

            $this->screen_message = "Scheduling conference, enter time (HHmm)";
        }

    }
    protected function message(): string
    {
        return $this->screen_message;
    }

    protected function options(): array
    {
        return [];
    }

    public function previous(): Screen
    {
        return new Schedule_Conf_Date($this->request);
    }

    protected function execute(): mixed
    {
        // $this->validate($this->request, 'time');
        $time = $this->validateTimeInput($this->value());
        if ($time) {
            $this->addPayload('conf_time', $time);
            return (new Schedule_Conf_Confirm($this->request))->render();
        }

        throw new UssdException($this->request, "Incorrect time,please try again later");
    }


    function validateTimeInput($input)
    {
        date_default_timezone_set('Africa/Blantyre');

        $hours = substr($input, 0, 2);
        $minutes = substr($input, 2, 2);

        if ($hours >= 0 && $hours <= 23 && $minutes >= 0 && $minutes <= 59) {

            $inputTime =  $hours . ':' . $minutes;

            $currentTime = date('H:i');

            if ($inputTime < $currentTime && $this->payload('conf_date') == (new DateTime())->format('Y-m-d')) {
                return false;
            }

            return $inputTime;
        }

        return false; // Invalid time format
    }
}
