<?php


namespace App\Screens;


use TNM\USSD\Screen;
use App\Services\AsteriskDB;
use TNM\USSD\Exceptions\UssdException;

class Account_Profile_Update_Confirmation extends Screen
{


    protected function message(): string
    {
        if ($this->payload("special_need") === "none") return "Updating last name:" . $this->payload("l_name") . ", Gender:" . $this->payload("gender") . ", Year of birth:" . $this->payload("dob");

        return "Updating last name:" . $this->payload("l_name") . ", Gender:" . $this->payload("gender") . ", Year of birth:" . $this->payload("dob") . ", Special need:" . $this->payload("special_need");
    }


    protected function options(): array
    {
        return ['Update', 'Restart', 'Cancel'];
    }

    public function previous(): Screen
    {
        return new Welcome($this->request);
    }

    protected function execute(): mixed
    {
        switch ($this->value()) {

            case 'Update':
                $service = new AsteriskDB();
                $result = $service->updateUser($this->request->msisdn, $this->payload("l_name"), $this->payload("gender"), $this->payload("dob"), $this->payload("special_need"));
                if ($result === true) throw new UssdException($this->request, "Profile updated!");

                throw new UssdException($this->request, "Something went wrong, please try again later");

            case 'Restart':
                return (new Account_Profile_Update_Lname($this->request))->render();

            case 'Cancel':
                throw new UssdException($this->request, "Profile update cancelled, Please try again later");

            default:
                throw new UssdException($this->request, "Incorrect input, please try again later");
        }
    }
}
