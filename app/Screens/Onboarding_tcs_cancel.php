<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Onboarding_tcs_cancel extends Screen
{


    protected function message(): string
    {
        return "You can access the Terms of Use from our website or any nearby registered school";
    }


    protected function options(): array
    {
        return [];
    }


    public function previous(): Screen
    {
        return new Welcome($this->request);
    }


    protected function execute(): mixed
    {
        // TODO: Implement execute() method.
    }

    public function goesBack(): bool
    {
        return false;
    }

    public function acceptsResponse(): bool
    {
        return false;
    }
}
