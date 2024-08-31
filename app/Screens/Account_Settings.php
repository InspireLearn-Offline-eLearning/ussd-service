<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Account_Settings extends Screen
{


    protected function message(): string
    {
        return "Choose what to configure";
    }

 
    protected function options(): array
    {
        return ["Change language"];
    }

    public function previous(): Screen
    {
        return new Welcome($this->request);
    }

    /**
     * Execute the selected option/action
     *
     * @return mixed
     */
    protected function execute() : mixed
    {
        switch ($this->value()) {

            case 'Change language':
                return (new Account_Settings_Language($this->request))->render();

            default:
            return (new Welcome($this->request))->render();
        }
    }
}
