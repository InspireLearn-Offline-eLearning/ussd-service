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
        \Log::debug('Validating date with regex:', ['value' => $this->value()]);
        $this->validate($this->request, 'date');
        // Log the value being validated
        

        $conference_date = $this->validateDate($this->value());

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

    function validateDate($ddmm)
    {
        // Debugging: Log the input to check if it's received correctly
        \Log::debug('Validating date with input:', ['ddmm' => $ddmm]);

        try {
            // Extract the day and month from the input string
            $day = substr($ddmm, 0, 2);  // First two characters are the day
            $month = substr($ddmm, 2, 2); // Last two characters are the month

            // Debugging: Log the extracted day and month
            \Log::debug('Extracted day and month:', ['day' => $day, 'month' => $month]);

            // Get the current year
            $year = date('Y');

            // Construct the date string in 'yyyy-mm-dd' format
            $dateString = "$year-$month-$day";

            // Debugging: Log the constructed date string
            \Log::debug('Constructed date string:', ['dateString' => $dateString]);

            // Validate if the date is a valid calendar date
            if (!checkdate($month, $day, $year)) {
                \Log::error('Invalid date detected:', ['dateString' => $dateString]);
                return false;
            }

            // Create DateTime objects for the input date and the current date
            $inputDate = new DateTime($dateString);
            $currentDate = new DateTime();
            $currentDate = $currentDate->format('Y-m-d');

            // Debugging: Log the input and current dates
            \Log::debug('Comparing dates:', ['inputDate' => $inputDate, 'currentDate' => $currentDate]);

            // Check if the input date is in the past
            if ($inputDate < $currentDate) {
                \Log::error('The date is in the past:', ['inputDate' => $inputDate]);
                return false;
            }

            return $inputDate;
        } catch (\Exception $e) {
            // Log the exception or handle it accordingly
            \Log::error('Date validation failed:', ['exception' => $e->getMessage()]);
            throw new UssdException($this->request, "Date validation failed: " . $e->getMessage());
        }
    }
}
