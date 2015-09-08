<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 01/09/15
 * Time: 10:56
 */

namespace CultuurNet\SymfonySecurityOAuth\Security;

use CultuurNet\SymfonySecurityOAuth\Model\AccessTokenInterface;
use CultuurNet\SymfonySecurityOAuth\Service\OAuthServerServiceInterface;
use CultuurNet\UitidCredentials\Entities\Consumer;
use CultuurNet\UitidCredentials\Entities\Token;
use CultuurNet\UitidCredentials\UitidCredentialsFetcher;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class OAuthAuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var UitidCredentialsFetcher
     */
    protected $fetcher;

    /**
     * @param UserProviderInterface       $userProvider  The user provider.
     * @param OAuthServerServiceInterface $serverService
     * @param UitidCredentialsFetcher     $fetcher
     */
    public function __construct(
        UserProviderInterface $userProvider,
        OAuthServerServiceInterface $serverService,
        UitidCredentialsFetcher $fetcher
    ) {
        $this->userProvider  = $userProvider;
        $this->serverService = $serverService;
        $this->tokenProvider = $serverService->getTokenProvider();
        $this->fetcher       = $fetcher;
    }

    /**
     * @param TokenInterface $token
     * @return null|TokenInterface
     */
    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            return null;
        }

//        $oauth_request_parameters = $token->getRequestParameters;
//
//        /** @var Token $uitid_token */
//        $uitid_token = $this->fetcher->getAccessToken($oauth_request_parameters['oauth_token']);


        if ($this->serverService->validateRequest(
            $token->getRequestParameters(),
            $token->getRequestMethod(),
            $token->getRequestUrl(),
            $this->fetcher
        )) {
            $params      = $token->getRequestParameters();
            $accessToken = $this->tokenProvider->loadAccessTokenByToken($params['oauth_token']);
            $user        = $accessToken->getUser();
            if (null !== $user) {
                $token->setUser($user);
                return $token;
            }
        }

        throw new AuthenticationException('OAuth authentication failed');
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof OAuthToken;
    }
}
