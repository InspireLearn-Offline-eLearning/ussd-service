<?php


namespace App\Screens;


use TNM\USSD\Screen;
use App\Services\AsteriskDB;


class Account_ClassesCourses_Enrolled extends Screen
{

    protected AsteriskDB $service;
    protected string $screen_message;
    protected array $screen_options;
    public function __construct($request)
    {
        parent::__construct($request);
        $this->service = new AsteriskDB();
        $getcouselist= $this->service->getUserCourseList($this->request->msisdn, $this->payload("selected_class_id"));
        
        if ($getcouselist == null) {
            $this->screen_message = "Enroll in a course today.";
            $this->screen_options = ["Start","Cancel"];
        } else {
            $this->screen_message = "Select a course from the list to exit.";
            $this->screen_options = $getcouselist;
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

    /**
     * Execute the selected option/action
     *
     * @return mixed
     */
    protected function execute(): mixed
    {
        // TODO: Implement execute() method.
    }
}
