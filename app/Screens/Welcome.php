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

    // protected  $user_object;

    public function __construct($request)
    {
        parent::__construct($request); // Call the parent constructor if needed
        $this->service = new AsteriskDB();
        $validated_user = $this->service->validate($this->request->msisdn);

        if ($validated_user == null) {
            $this->screen_message = "Welcome to InspireLearn! Continue if you have read and accepted our terms and conditions.";
            $this->screen_options = ['Continue', 'Abort'];
        } elseif ($validated_user->status == 'active') {
            $this->screen_message =  sprintf("Dear %s, Welcome to InspireLearn", $validated_user->f_name);
            $this->screen_options = ['Bundles', 'Conferences', 'Account'];
     
            $this->addPayload('user_role',$validated_user->role);

        } else {
            $this->screen_message =  sprintf("Welcome back to InspireLearn %s , confirm re-activating your account", $validated_user->f_name);
            $this->screen_options = ['Confirm', 'Cancel'];
            $this->addPayload('user_role',$validated_user->role);

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

    /**
     * Execute the selected option/action
     *
     * @return mixed
     */
    protected function execute(): mixed
    {

        switch ($this->value()) {
            case 'Continue':
                $this->service->createUser($this->request->msisdn);
                return (new Onboarding_getname($this->request))->render();

            case 'Abort':
                $this->service->createUser($this->request->msisdn);
                return (new Onboarding_tcs_cancel($this->request))->render();

            case 'Bundles':
                return (new Bundles($this->request))->render();

            case 'Conferences':
                $this->addPayload('reschedule', "0");
                return (new Classes_Conferences($this->request))->render();

            case 'Account':
                return (new Account($this->request))->render();

            case 'Cancel':
                throw new UssdException($this->request, "Account stil inactive. We hope to see you back soon.");

            case 'Confirm':

                $user = (new AsteriskDB())->reactivateUser($this->request->msisdn);

                if ($user) throw new UssdException($this->request, "Account re-activated! Please restart session.");

                throw new UssdException($this->request, "Something went wrong, please try again later!");


            default:
                throw new UssdException($this->request, "Something went wrong, Please try again later");
        }
    }


    public function goesBack(): bool
    {
        return false;
    }
}
