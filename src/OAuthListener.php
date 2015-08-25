<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 25/08/15
 * Time: 16:48
 */

namespace CultuurNet\SymfonySecurityOAuth;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class OAuthListener implements ListenerInterface
{

    /**
     * This interface must be implemented by firewall listeners.
     *
     * @param GetResponseEvent $event
     */
    public function handle(GetResponseEvent $event)
    {
        // TODO: Implement handle() method.
    }
}
