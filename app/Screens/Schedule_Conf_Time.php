<?php


namespace App\Screens;


use TNM\USSD\Screen;
// use TNM\USSD\Http\Validates;
use TNM\USSD\Exceptions\UssdException;

class Schedule_Conf_Time extends Screen
{
    // use Validates;

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
        // $this->validate($this->request, 'time');
        $time = $this->validateTimeInput($this->value());
        if ($time) {
            $this->addPayload('conf_time', $time);
            return (new Schedule_Conf_Confirm($this->request))->render();
        }

        throw new UssdException($this->request, "Incorrect time,please try again later");
    }


    // protected function rules(): string
    // {

    //     return 'regex:/^\d{4}$/';
    // }

    function validateTimeInput($input)
    {
        date_default_timezone_set('Africa/Blantyre'); 

        $hours = substr($input, 0, 2);
        $minutes = substr($input, 2, 2);

        if ($hours >= 0 && $hours <= 23 && $minutes >= 0 && $minutes <= 59) {
           
            $inputTime =  $hours . ':' . $minutes;

            \Log::debug('Input time is:', ['value' => $inputTime]);
            $currentTime = date('H:i');
            \Log::debug('Current time is:', ['value' => $currentTime]);
            if ($inputTime < $currentTime) {
                return false; // Time is in the past
            }

            return $inputTime; 
        }

        return false; // Invalid time format
    }
}
