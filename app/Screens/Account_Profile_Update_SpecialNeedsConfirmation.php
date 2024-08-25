<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Account_Profile_Update_SpecialNeedsConfirmation extends Screen
{


    protected function message(): string
    {
        return "Do you have any special need?";
    }


    protected function options(): array
    {
        return ['Yes','No'];
    }

    /**
    * Previous screen
    * return Screen $screen
    */
    public function previous(): Screen
    {
        return new Welcome($this->request);
    }

    protected function execute() : mixed
    {
        if ($this->value() === 'Yes') {

            return (new Account_Profile_Update_SpecialNeeds($this->request))->render();
          }
          else{
            $this->addPayload('special_need', 'none');
            return (new Account_Profile_Update_Confirmation($this->request))->render();
          }

    }
}
