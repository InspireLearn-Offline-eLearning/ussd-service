<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Http\Validates;
use TNM\USSD\Exceptions\UssdException;

class Schedule_Conf_Time extends Screen
{
    use Validates;

    protected function message(): string
    {
        return "Scheduling conference, enter time (HHmm)";
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
        $this->validate($this->request, 'time');
        $time = $this->validateTimeInput($this->value());
        if ($time) {
            $this->addPayload('conf_time', $time);
            return (new Schedule_Conf_Confirm($this->request))->render();
        }

        throw new UssdException($this->request, "Something went wrong, Please try again later");
    }


    protected function rules(): string
    {

        return 'regex:/^\d{4}$/';
    }

    function validateTimeInput($input)
    {


        $hours = substr($input, 0, 2);
        $minutes = substr($input, 2, 2);

        if ($hours >= 0 && $hours <= 23 && $minutes >= 0 && $minutes <= 59) {
            return $hours . ':' . $minutes;
        }

        return false; // Invalid time format
    }
}
