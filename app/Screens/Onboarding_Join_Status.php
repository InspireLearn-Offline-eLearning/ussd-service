<?php


namespace App\Screens;


use TNM\USSD\Screen;
use App\Services\AsteriskDB;

class Onboarding_Join_Status extends Screen
{

    protected AsteriskDB $service;
    protected string $screen_message;
    protected array $screen_options;
    public function __construct($request)
    {
        parent::__construct($request);
        $this->service = new AsteriskDB();
        $result = $this->service->joinCourseRegistration($this->request->msisdn, $this->payload('course_id'), $this->payload('user_role'));
        if ($result != null) {

            if ($this->payload('user_role') == 'teacher') {
                $this->screen_message = "Successfully enrolled, waiting approval as a teacher";
            } else {
                $this->screen_message = "Successfully enrolled as a " . $result->role;
            }
            $this->screen_options = [];
        } else {
            $this->screen_message = "Unable to register at this time";
            $this->screen_options = [];
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


    protected function execute(): mixed {}

    public function goesBack(): bool
    {
        return false;
    }

    public function acceptsResponse(): bool
    {
        return false;
    }
}
