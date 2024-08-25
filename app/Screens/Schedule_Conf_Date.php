<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Schedule_Conf_Date extends Screen
{

    protected string $screen_message;
    protected array $screen_options;

    public function __construct($request)
    {
        parent::__construct($request);
        
        \Log::debug('Paylaod in Date: ', ['value' => $this->payload("reschedule")]);

        if ($this->payload("reschedule") === "1") {

            $this->screen_message="Reschedule conference to a new date:";

            $this->addPayload("conf_class", trim(explode('>', $this->payload("selected_conference"))[1]));

        }else{

            $this->screen_message="Schedule conference for?";

        }

        $this->screen_options=['Today', 'Tomorrow', 'Enter date'];

    }
    protected function message(): string
    {
        return $this->screen_message;
    }

    protected function options(): array
    {
        return  $this->screen_options;
    }

    public function previous(): Screen
    {
        return new Schedule_Conf_Class($this->request);
    }

    protected function execute(): mixed
    {
        if ($this->value() === 'Today') {
            $this->addPayload('conf_date', date('Y-m-d'));
            return (new Schedule_Conf_Time($this->request))->render();
        } elseif ($this->value() === 'Tomorrow') {

            $this->addPayload('conf_date', date('Y-m-d', strtotime('+1 day')));
            return (new Schedule_Conf_Time($this->request))->render();
        }
        return (new Schedule_Conf_SetDate($this->request))->render();
    }
}
