<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;
use App\Services\AsteriskDB;

class Account extends Screen
{


    protected AsteriskDB $service;
    protected string $screen_message;
    protected array $screen_options;


    public function __construct($request)
    {
        parent::__construct($request);
        $this->service = new AsteriskDB();
        $user = $this->service->validate($this->request->msisdn);

        if ($user->l_name === null) {
            $this->screen_message = "Complete your profile registration first";
            $this->screen_options = ['Start', 'Later'];
        } else {
            $this->screen_message = "Manage your account";
            $this->screen_options = ['Classes and Courses', 'Settings', 'Deactivate'];
        }
    }
    protected function message(): string
    {
        return $this->screen_message;
    }

    protected function options(): array
    {
        return $this->screen_options;
    }

    public function previous(): Screen
    {
        return new Welcome($this->request);
    }


    protected function execute(): mixed
    {
        switch ($this->value()) {

            case 'Classes and Courses':
                return (new Account_ClassesCourses_Manage($this->request))->render();

            case 'Settings':
                $this->addPayload('reschedule', "1");
                return (new Account_Settings($this->request))->render();

            case 'Start':
                return (new Account_Profile_Update_Start($this->request))->render();
            case 'Later':
                throw new UssdException($this->request, "Thankyou for your response!");

            case 'Deactivate':
                return (new Account_Settings_Deactivate($this->request))->render();

            default:
            return (new Welcome($this->request))->render();
        }
    }
}
