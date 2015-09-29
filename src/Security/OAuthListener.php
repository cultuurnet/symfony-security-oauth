<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 25/08/15
 * Time: 16:48
 */

namespace CultuurNet\SymfonySecurityOAuth\Security;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class OAuthListener implements ListenerInterface
{
    protected $tokenStorage;
    protected $authenticationManager;
    protected $baseUrl;
    protected $consumerCredentials;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
    }

    /**
     * This interface must be implemented by firewall listeners.
     *
     * @param GetResponseEvent $event
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (false === $request->attributes->get('oauth_request_parameters')) {
            return;
        }

        $oauth_token = new OAuthToken();
        $oauth_token->setRequestParameters($request->attributes->get('oauth_request_parameters'));
        $oauth_token->setRequestMethod($request->attributes->get('oauth_request_method'));
        $oauth_token->setRequestUrl($request->attributes->get('oauth_request_url'));

        try {
            $returnValue = $this->authenticationManager->authenticate($oauth_token);
            if ($returnValue instanceof TokenInterface) {
                return $this->tokenStorage->setToken($returnValue);
            } elseif ($returnValue instanceof Response) {
                $event->setResponse($returnValue);
                return;
            }

            return;
        } catch (AuthenticationException $e) {
            throw $e;
        }
    }
}
