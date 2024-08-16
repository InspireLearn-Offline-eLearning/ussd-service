<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Home extends Screen
{

    /**
     * Add message to the screen
     *
     * @return string
     */
    protected function message(): string
    {
        // return "Hello InspireLearn";
        return sprintf("Dear %s, Welcome to InspireLearn", $this->payload('user'));
    }

    /**
     * Add options to the screen
     * @return array
     */
    protected function options(): array
    {
        return ['Bundles','Classes/Conferences','Account'];
    }

    /**
    * Previous screen
    * return Screen $screen
    */
    public function previous(): Screen
    {
        return new Home($this->request);
    }

    /**
     * Execute the selected option/action
     *
     * @return mixed
     */
    protected function execute() : mixed
    {
        // TODO: Implement execute() method.
        if ($this->value() === 'Bundles') {
            return (new Bundles($this->request))->render();
        }
        elseif ($this->value() === 'Classes/Conferences') {
            return (new Classes_conferences($this->request))->render();
        }
        else return (new Account($this->request))->render();
    }
    
    public function goesBack(): bool
    {
        return false;
    }
}
