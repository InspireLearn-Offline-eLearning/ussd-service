<?php


namespace App\Screens;


use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;
use App\Services\AsteriskDB;

class Account_Courses_Exit_Status extends Screen
{
    protected AsteriskDB $service;
    public function __construct($request)
    {
        parent::__construct($request);
        $this->service = new AsteriskDB();
        $result = $this->service->exitCourse($this->request->msisdn, $this->payload("selected_course_id"));

        if ($result) throw new UssdException($this->request, "Course dropped successfully!");

        throw new UssdException($this->request, "Something went wrong, please try again later");
    }
    protected function message(): string
    {
        return "";
    }

    protected function options(): array
    {
        return [];
    }

    public function previous(): Screen
    {
        return new Welcome($this->request);
    }

    protected function execute() : mixed
    {
        
    }

    public function goesBack(): bool
    {
        return false;
    }

    public function acceptsResponse(): bool
    {
        return false;
    }
}
