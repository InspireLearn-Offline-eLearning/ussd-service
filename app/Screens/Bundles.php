<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Bundles extends Screen
{


    protected function message(): string
    {
        return "Bundles";
    }

    protected function options(): array
    {
        return ['Buy Bundles','Check Balance'];
    }

    public function previous(): Screen
    {
        return new Welcome($this->request);
    }

    protected function execute(): mixed
    {
        // TODO: Implement execute() method.
    }
}
