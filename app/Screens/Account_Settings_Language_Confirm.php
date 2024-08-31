<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;
use App\Services\AsteriskDB;

class Account_Settings_Language_Confirm extends Screen
{


    protected function message(): string
    {
        return "Changing language to ". $this->payload("selected_language") . " will reflect in all InspireLearn communication, Continue?";

    }

    protected function options(): array
    {
        return ["Continue", "Cancel"];
    }

    public function previous(): Screen
    {
        return new Welcome($this->request);
    }

    protected function execute() : mixed
    {
        switch ($this->value()) {

            case 'Continue':

               $user = (new AsteriskDB())->changeUserLanguage($this->request->msisdn,$this->payload("selected_language"));
               
               if ($user) throw new UssdException($this->request, "Language changed successfully!");

               throw new UssdException($this->request, "Something went wrong, please try again later!");

            case 'Cancel':

                throw new UssdException($this->request, "Operation cancelled!");

            default:
            return (new Welcome($this->request))->render();
        }    }
}
