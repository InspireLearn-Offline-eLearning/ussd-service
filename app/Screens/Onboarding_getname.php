<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Http\Validates;
class Onboarding_getname extends Screen
{

    use Validates;

    protected function message(): string
    {
        return "Enter your first name";
    }

    /**
     * Add options to the screen
     * @return array
     */
    protected function options(): array
    {
        return [];
    }

    /**
     * Previous screen
     * return Screen $screen
     */
    public function previous(): Screen
    {
        return new Welcome($this->request);
    }

    /**
     * Execute the selected option/action
     *
     * @return mixed
     */
    protected function execute(): mixed
    {
        
        $this->addPayload("registered_user_role", "0"); //initialiser
        $this->validate($this->request,'first name');
        $this->addPayload('f_name', $this->value());
        return (new Onboarding_usertype($this->request))->render();

    }

    protected function rules() : string
    {
        return 'regex:/^[a-zA-Z][a-z]{2,44}$/';
    }
    public function goesBack(): bool
    {
        return false;
    }
}
