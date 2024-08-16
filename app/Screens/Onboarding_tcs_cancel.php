<?php


namespace App\Screens;

use Exception;
use TNM\USSD\Screen;

class Onboarding_tcs_cancel extends Screen
{

    /**
     * Add message to the screen
     *
     * @return string
     */
    protected function message(): string
    {
        return "You can access our terms and conditions, at inspirelearnmw.com or a nearest school.";
    }

    /**
     * Add options to the screen
     * @return array
     */
    protected function options(): array
    {
        return [];
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
        // TODO: Implement execute() method.
        //register the requester  into the DB as a stage 0 user.

    }

    public function acceptsResponse(): bool
    {
        return false;
    }
}
