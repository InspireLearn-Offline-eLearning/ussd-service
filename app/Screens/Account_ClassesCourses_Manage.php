<?php


namespace App\Screens;


use TNM\USSD\Screen;
use App\Services\AsteriskDB;
use TNM\USSD\Exceptions\UssdException;

class Account_ClassesCourses_Manage extends Screen
{


    protected AsteriskDB $service;
    protected string $screen_message;
    protected array $screen_options;


    public function __construct($request)
    {
        parent::__construct($request);
        $this->screen_message = "Manage Classes and Courses";
        $this->screen_options = ['Join new', 'View/Exit'];
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

            case 'Join new':
                $this->addPayload("registered_user_role","1");
                return (new Onboarding_getcode($this->request))->render();

            case 'View/Exit':
                return (new Account_ClassesCourses_View($this->request))->render();

            default:
                throw new UssdException($this->request, "Thankyou for your response!");
        }
    }
}
