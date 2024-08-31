<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;
use App\Services\AsteriskDB;

class Account_Settings_Language extends Screen
{

    protected AsteriskDB $service;
    protected string $screen_message;
    protected array $screen_options;


    public function __construct($request)
    {
        parent::__construct($request);
        $this->service = new AsteriskDB();

        $avail_languages= $this->service->getLanguages($this->request->msisdn);

        if ($avail_languages) {
        $this->screen_message = "Select your preferred language";
        $this->screen_options = $avail_languages;
        } else {
            $this->screen_message = "No other languages available for now, try again later";
            $this->screen_options = [];
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
       
       if ($this->value()) {
        $this->addPayload("selected_language", $this->value());
        return (new Account_Settings_Language_Confirm($this->request))->render();

    } else {
            return null;
        }
    }
}
