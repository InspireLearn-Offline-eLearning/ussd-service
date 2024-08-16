<?php


namespace App\Screens;

// require 'vendor/autoload.php';
// use GuzzleHttp\Client;
use App\Services\PhoneNumberValidator;
use TNM\USSD\Screen;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;
use TNM\USSD\Exceptions\UssdException;

use function PHPUnit\Framework\throwException;

class Welcome extends Screen
{

    /**
     * Add message to the screen
     *
     * @return string
     */
    protected function message(): string
    {
        return "Welcome to InspireLearn! Continue if you have read and accepted our terms and conditions.";
    }

    /**
     * Add options to the screen
     * @return array
     */
    protected function options(): array
    {
        return ['Confirm', 'Cancel'];
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

        $service = new PhoneNumberValidator();

        $response = $service->createUser($this->request->msisdn);

        if ($response) {
            return (new Onboarding_tcs_cancel($this->request))->render();
        }

        throw new UssdException($this->request, "Subscription failed. Please try again later");
    }


    public function goesBack(): bool
    {
        return false;
    }
}
