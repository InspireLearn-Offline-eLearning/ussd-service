<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Schedule_Conf_Class extends Screen
{

    /**
     * Add message to the screen
     *
     * @return string
     */
    protected function message(): string
    {
        return "Scheduling a conference, select class";
    }

    /**
     * Add options to the screen
     * @return array
     */
    protected function options(): array
    {
        return ['Form4-Biology','Form3-Biology'];
    }

    /**
    * Previous screen
    * return Screen $screen
    */
    public function previous(): Screen
    {
        return new Classes_Conferences($this->request);
    }

    /**
     * Execute the selected option/action
     *
     * @return mixed
     */
    protected function execute(): mixed
    {
        // TODO: Implement execute() method.
        $this->addPayload('conf_class', $this->value());
        return (new Schedule_Conf_Date($this->request))->render();
    }
}
