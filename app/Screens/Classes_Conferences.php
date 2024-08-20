<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;

class Classes_Conferences extends Screen
{

    protected function message(): string
    {
        return "Conferences";
    }

    /**
     * Add options to the screen
     * @return array
     */
    protected function options(): array
    {
        return ['View/Update', 'Schedule new'];
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
        // TODO: Implement execute() method.
        if ($this->value() === 'Schedule new') {
            return (new Schedule_Conf_Class($this->request))->render();
        } else {
            return (new ViewUpdate_Conf($this->request))->render();
        }
    }
}
