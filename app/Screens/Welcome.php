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
    protected $userValidationResult;

    public function __construct($request)
    {
        parent::__construct($request); // Call the parent constructor if needed
        $this->service = new AsteriskDB();
        $this->userValidationResult = $this->service->validate($this->request->msisdn);
    }
    /**
     * Add message to the screen
     *
     * @return string
     */
    protected function message(): string
    {
     
        if (!$this->userValidationResult) return "Welcome to InspireLearn! Continue if you have read and accepted our terms and conditions.";
        return sprintf("Dear %s, Welcome to InspireLearn", $this->userValidationResult->f_name);
    }

    /**
     * Add options to the screen
     * @return array
     */
    protected function options(): array
    {
    
        if (!$this->userValidationResult) return ['Confirm', 'Cancel'];
        return['Bundles','Classes/Conferences','Account'];
    }


    public function previous(): Screen
    {
        return new Onboarding_getname($this->request);
    }

    /**
     * Execute the selected option/action
     *
     * @return mixed
     */
    protected function execute(): mixed
    {
        if ($this->value() === 'Confirm')
            return (new Onboarding_getname($this->request))->render();

        $service = new AsteriskDB();

        $result = $service->createUser($this->request->msisdn);

        if ($result) {
            return (new Onboarding_tcs_cancel($this->request))->render();
        }

        throw new UssdException($this->request, "Registration done!");
    }


    public function goesBack(): bool
    {
        return false;
    }
}
