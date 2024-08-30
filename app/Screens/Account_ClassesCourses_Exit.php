<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;


class Account_ClassesCourses_Exit extends Screen
{

    protected AsteriskDB $service;
    protected string $screen_message;
    protected array $screen_options;
    protected function message(): string
    {
        return "All your enrolled courses will be removed. Are you sure you want to exit the class?";
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

                // $this->service
                throw new UssdException($this->request, "Existed successfully!");

            case 'Cancel':

                throw new UssdException($this->request, "Failed to dropout of class");

            default:
                throw new UssdException($this->request, "Please try again later");
        }
    }
}
