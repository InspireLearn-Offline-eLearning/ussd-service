<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Account_Profile_Update_Dob extends Screen
{
    protected string $screen_message;
    public function __construct($request)
    {
        parent::__construct($request);
        if ($this->payload("dob_error") === "1") {
            $this->screen_message = $this->payload("dob_error_msg");
        } else{
            $this->screen_message = "Enter your year of birth e.g 1992";
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
        return new Welcome($this->request);
    }


    protected function execute(): mixed
    {

        if ($this->validateYearOfBirth($this->value())) {

            $this->addPayload("dob", $this->value());
            return (new Account_Profile_Update_SpecialNeedsConfirmation($this->request))->render();
        } else {
            $this->addPayload("dob_error", "1");
            return (new Account_Profile_Update_Dob($this->request))->render();
        }
    }

    public function validateYearOfBirth($yearOfBirth)
    {

        if (!preg_match('/^\d{4}$/', $yearOfBirth)) {
            
            $this->addPayload("dob_error_msg","Try again, enter your year of birth as 4 digits");
            return false;
        }

        $age = date('Y') - $yearOfBirth;

        if ($age < 12 || $age > 65) {
            $this->addPayload("dob_error_msg","Try again, enter a valid age range");
            return false;
        }

        return true;
    }
}
