<?php


namespace App\Screens;


use TNM\USSD\Screen;
use App\Services\AsteriskDB;

class Account_Classes_View extends Screen
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
            $this->screen_message = "You dont belong to any class.";
            $this->screen_options = [];
        } else {
            $this->screen_message = "Select a class from the list to exit or view courses.";
            $this->screen_options = $getclasslist;
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

    protected function execute() : mixed
    {
        $this->addPayload("selected_class", $this->value());
        return (new Account_Classes_Selected($this->request))->render();
    }
}
