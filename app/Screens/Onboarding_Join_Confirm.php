<?php


namespace App\Screens;


use TNM\USSD\Screen;
use App\Services\AsteriskDB;

class Onboarding_Join_Confirm extends Screen
{

    protected AsteriskDB $service;
    protected string $screen_message;
    protected array $screen_options;
    public function __construct($request)
    {
        parent::__construct($request);
        $this->service = new AsteriskDB();
        $result = $this->service->joinCodeValidation($this->payload('joining_code'), $this->request->msisdn);
        if ($result['status']) {

            $this->addPayload('course_id', $result['course']->course_id);
            $this->screen_message = "Enroll in" . " " . $result['course']->name . " " . $result['course']->class->name . "@" . " " . $result['course']->class->school->name;
            $this->screen_options = ['Confirm', 'Cancel'];
        } else{
            $this->screen_message = $result['message'];
            $this->screen_options = [];
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


    public function previous(): Screen
    {
        return new Welcome($this->request);
    }


    protected function execute(): mixed
    {
      if ($this->value() === 'Confirm') {

        return (new Onboarding_Join_Status($this->request))->render();
      }
      else{
        return (new Welcome($this->request))->render();
      }
    }
}