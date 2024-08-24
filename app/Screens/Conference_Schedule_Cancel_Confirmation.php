<?php


namespace App\Screens;


use TNM\USSD\Screen;
use App\Services\AsteriskDB;
use TNM\USSD\Exceptions\UssdException;

class Conference_Schedule_Cancel_Confirmation extends Screen
{

    protected AsteriskDB $service;

    public function __construct($request)
    {
        parent::__construct($request);

        $this->service = new AsteriskDB();

    }

    protected function message(): string
    {
        return "Confirm conference withdrawal?";
    }

    protected function options(): array
    {
        return ['Confirm', 'Cancel'];
    }

    public function previous(): Screen
    {
        return new Welcome($this->request);
    }


    protected function execute(): mixed
    {
        switch ($this->value()) {
            case "Confirm":
                $result = $this->service->updateConferenceStatus(trim(explode('>', $this->payload("selected_conference"))[0]), "cancelled");
                if ($result) throw new UssdException($this->request, "Conference withdrawn successfully!");
                throw new UssdException($this->request, "Something went wrong please try again later");

            case "Cancel":
                throw new UssdException($this->request, "Withdrawal cancelled!");

            default:
                throw new UssdException($this->request, "Incorrect response, Please try again later");
        }
    }
}
