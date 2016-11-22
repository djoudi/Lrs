<?php

namespace Lrs\Tracker\Listeners;

use Lrs\Tracker\Events\UserDomainCheck;
use Lrs\Tracker\Events\UserLogin;
use Lrs\Tracker\Models\Site;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserDomainCheckFired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserDomainCheck  $event
     * @return void
     */
    public function handle(UserDomainCheck $event)
    {
        $site = \Lrs\Tracker\Models\Site::first();

        //has a domain been set?
        if( $site ){
            $domain = $site->domain;
            if( $site->domain != '' ){
                $allowed_domain = array($domain);
                // Make sure the address is valid
                if ( filter_var($event->emailID, FILTER_VALIDATE_EMAIL) ){

                    //get submitted email domain
                    $email = explode('@', $event->emailID);
                    $email = array_pop( $email );

                    if ( !in_array($email, $allowed_domain) ){
                        return false;
                    }

                }
            }
        }

        return true;
    }
}
