<?php


namespace App\Screens;

// require 'vendor/autoload.php';
// use GuzzleHttp\Client;
use App\Services\AsteriskDB;
use TNM\USSD\Screen;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;
use TNM\USSD\Exceptions\UssdException;

use function PHPUnit\Framework\throwException;

class Welcome extends Screen
{

    protected AsteriskDB $service;
    protected string $screen_message;
    protected array $screen_options;
    protected $screen_previousScreen;

    public function __construct($request)
    {
        parent::__construct($request); // Call the parent constructor if needed
        $this->service = new AsteriskDB();
        $validated_user = $this->service->validate($this->request->msisdn);
        if ($validated_user == null) {
            $this->screen_message = "Welcome to InspireLearn! Continue if you have read and accepted our terms and conditions.";
            $this->screen_options = ['Confirm', 'Cancel'];
        } else {
            $this->screen_message =  sprintf("Dear %s, Welcome to InspireLearn", $validated_user->f_name);
            $this->screen_options = ['Bundles', 'Classes/Conferences', 'Account'];
        }
    }
    /**
     * Add message to the screen
     *
     * @return string
     */
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

    /**
     * Execute the selected option/action
     *
     * @return mixed
     */
    protected function execute(): mixed
    {
        switch ($this->value()) {
            case 'Confirm':
                $this->service->createUser($this->request->msisdn);
                return (new Onboarding_getname($this->request))->render();

            case 'Cancel':
                $this->service->createUser($this->request->msisdn);
                return (new Onboarding_tcs_cancel($this->request))->render();

            case 'Bundles':
                return (new Bundles($this->request))->render();

            case 'Classes/Conferences':
                return (new Classes_conferences($this->request))->render();

            case 'Account':
                return (new Account($this->request))->render();

            default:
                throw new UssdException($this->request, "Something went wrong, Please try again later");
        }
    }


    public function goesBack(): bool
    {
        return false;
    }
}
