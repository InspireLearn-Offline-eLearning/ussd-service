<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;

class Account_Profile_Update_SpecialNeeds extends Screen
{


    protected function message(): string
    {
        return "Select your special need";
    }


    protected function options(): array
    {
        return ['Mobility Impairment', 'Speech Impairment', 'Hearing Impairment', 'Visual Impairment'];
    }


    public function previous(): Screen
    {
        return new Welcome($this->request);
    }

    protected function execute(): mixed
    {
        switch ($this->value()) {
            case 'Mobility Impairment':
                $this->addPayload('special_need', 'mobility');
                return (new Account_Profile_Update_Confirmation($this->request))->render();

            case 'Speech Impairment':
                $this->addPayload('special_need', 'speech');
                return (new Account_Profile_Update_Confirmation($this->request))->render();

            case 'Hearing Impairment':
                $this->addPayload('special_need', 'hearing');
                return (new Account_Profile_Update_Confirmation($this->request))->render();

            case 'Visual Impairment':
                $this->addPayload('special_need', 'visual');
                return (new Account_Profile_Update_Confirmation($this->request))->render();

            default:
                
                throw new UssdException($this->request, "Something went wrong, Please try again later");
        }
    }
}
