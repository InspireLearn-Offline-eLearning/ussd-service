<?php


namespace App\Screens;


use TNM\USSD\Screen;
use App\Services\AsteriskDB;
use TNM\USSD\Exceptions\UssdException;



class Account_Courses_Enrolled extends Screen
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
            $this->screen_message = "No courses found, request for access code from class leader!";
            // $this->screen_options = ["Continue","Cancel"];
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
        $this->addPayload("selected_course_id",trim(explode(':',$this->value())[0]));
        $this->addPayload("selected_course",trim(explode(':',$this->value())[1]));

        return (new Account_Courses_Exit_Confirm($this->request))->render();
        
    }
}
