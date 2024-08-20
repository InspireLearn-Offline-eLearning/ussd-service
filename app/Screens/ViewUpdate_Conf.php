<?php


namespace App\Screens;


use TNM\USSD\Screen;
use App\Services\AsteriskDB;

class ViewUpdate_Conf extends Screen
{
    protected AsteriskDB $service;
    protected string $screen_message;
    protected array $screen_options;
    public function __construct($request)
    {
        parent::__construct($request);
        $this->service = new AsteriskDB();
        $getconferences = $this->service->getconferences($this->request->msisdn);
        if ($getconferences == null) {
            $this->screen_message = "You have no upcoming conferences";
            $this->screen_options = [];
        }else{
            $this->screen_message = "Upcoming conference(s)";
            $this->screen_options = $getconferences;
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
    }
}
