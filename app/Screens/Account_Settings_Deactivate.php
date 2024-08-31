<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;
use App\Services\AsteriskDB;

class Account_Settings_Deactivate extends Screen
{


    protected function message(): string
    {
        return "Confirm account deactivation";
    }


    protected function options(): array
    {
        return ['Confirm', 'Cancel'];
    }


    public function previous(): Screen
    {
        return new Welcome($this->request);
    }


    protected function execute(): mixed
    {
        switch ($this->value()) {

            case 'Confirm':

                $user = (new AsteriskDB())->deactivateUser($this->request->msisdn);

                if ($user) throw new UssdException($this->request, "Account deactivated! we hope to have you back soon.");

                throw new UssdException($this->request, "Something went wrong, please try again later!");

            case 'Cancel':

                throw new UssdException($this->request, "Operation cancelled!");

            default:
                return (new Welcome($this->request))->render();
        }
    }
}
