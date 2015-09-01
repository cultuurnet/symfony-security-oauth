<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 25/08/15
 * Time: 16:48
 */

namespace CultuurNet\SymfonySecurityOAuth;

use CultuurNet\Auth\ConsumerCredentials;
use CultuurNet\UitidCredentials\UitidCredentialsFetcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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
        AuthenticationManagerInterface $authenticationManager,
        UitidCredentialsFetcher $fetcher
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->fetcher = $fetcher;
    }

    /**
     * This interface must be implemented by firewall listeners.
     *
     * @param GetResponseEvent $event
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $token = $this->fetcher->getAccessToken($tokenKey);

        $oauth_token = new OAuthToken();
        $oauth_token->consumer = $token->getConsumer();
        $oauth_token->token = $token->getToken();
        $oauth_token->tokenSecret = $token->getTokenSecret();
        $oauth_token->user = $token->getUser();

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($authToken);

            return;
        } catch (AuthenticationException $e) {
            throw $e;
        }

        // By default deny authorization
        $response = new Response();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
    }
}
