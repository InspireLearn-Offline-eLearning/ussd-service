<?php


namespace App\Screens;


use TNM\USSD\Screen;
use App\Services\AsteriskDB;
use TNM\USSD\Exceptions\UssdException;

class Conference_Schedule_Options extends Screen
{

    protected AsteriskDB $service;
    protected string $screen_message;
    protected array $screen_options;
    protected $scheduledConference;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->service = new AsteriskDB();
        $conf_id = explode('>', $this->payload("selected_conference"));
        $this->scheduledConference = $this->service->getConferenceOrganiser(trim($conf_id[0]));

        if ($this->scheduledConference->organiser_id === $this->request->msisdn) {
            $this->screen_message = "Conference: " . trim($conf_id[1]) . " Confirmed: ";
            $this->screen_options = ['Reschedule', 'Withdraw'];
        } else {
            $this->screen_message = "Will you be joining " . trim($conf_id[1]) . "?";
            $this->screen_options = ['Yes', 'Maybe', 'No'];
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
        return new Welcome($this->request);
    }

    /**
     * Execute the selected option/action
     *
     * @return mixed
     */
    protected function execute(): mixed
    {
        switch ($this->value()) {

            case 'Withdraw':
                return (new Conference_Schedule_Cancel_Confirmation($this->request))->render();

            case 'Reschedule':
                return (new Schedule_Conf_Date($this->request))->render();

            case 'Yes':
                throw new UssdException($this->request, "Thankyou for your response!");

            case 'No':
                throw new UssdException($this->request, "Thankyou for your response!");

            case 'Maybe':
                throw new UssdException($this->request, "Thankyou for your response!");

            default:
                throw new UssdException($this->request, "Something went wrong, Please try again later");
        }
    }
}
