<?php

namespace App\Screens;

use TNM\USSD\Screen;
use TNM\USSD\Http\Validates;
use TNM\USSD\Exceptions\UssdException;
use DateTime;

class Schedule_Conf_SetDate extends Screen
{
    use Validates;

    protected function message(): string
    {
        return "Enter date (ddmm)";
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

        $this->validate($this->request, 'date');

        $conference_date = $this->validateDateInput($this->value());

        if ($conference_date) {
            $this->addPayload('conf_date', $conference_date->format('Y-m-d'));
            return (new Schedule_Conf_Time($this->request))->render();
        }

        throw new UssdException($this->request, "Something went wrong, Please try again later $conference_date");
    }

    protected function rules(): string
    {
        // return 'regex:/^(0[1-9]|[12][0-9]|3[01])(0[1-9]|1[0-2])$/';

        return 'regex:/^\d{4}$/';

    }

    function validateDateInput($ddmm)
    {

        try {
    
            $day = substr($ddmm, 0, 2);  // First two characters are the day
            $month = substr($ddmm, 2, 2); // Last two characters are the month

            $year = date('Y');

            $dateString = "$year-$month-$day";

            if (!checkdate($month, $day, $year)) {

                return false;
            }

            $inputDate = new DateTime($dateString);
            $currentDate = new DateTime();
            $currentDate = $currentDate->format('Y-m-d');
            
            if ($inputDate < $currentDate) {

                return false;
            }

            return $inputDate;
        } catch (\Exception $e) {

            throw new UssdException($this->request, "Date validation failed: " . $e->getMessage());
        }
    }
}
