<?php


namespace App\Screens;


use TNM\USSD\Screen;
use App\Services\AsteriskDB;

class Schedule_Conf_Class extends Screen
{

    protected AsteriskDB $service;
    protected string $screen_message;
    protected array $screen_options;
    public function __construct($request)
    {
        parent::__construct($request);
        $this->service = new AsteriskDB();
        $getclasslist = $this->service->getUserClassList($this->request->msisdn);
        if ($getclasslist == null) {
            $this->screen_message = "You dont belong to any class, in the Home menu go to Accounts and join a class";
            $this->screen_options = [];
        } else {
            $this->screen_message = "Scheduling a conference, select class";
            $this->screen_options = $getclasslist;
        }
    }
    protected function message(): string
    {
        return $this->screen_message;
    }

    /**
     * Add options to the screen
     * @return array
     */
    protected function options(): array
    {
        return $this->screen_options;
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
