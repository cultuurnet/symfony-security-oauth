<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 01/09/15
 * Time: 10:56
 */

namespace CultuurNet\SymfonySecurityOAuth\Security;

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
     * @param UitidCredentialsFetcher     $fetcher.
     */
    public function __construct(
        UserProviderInterface $userProvider,
        UitidCredentialsFetcher $fetcher
    ) {
        $this->userProvider  = $userProvider;
        $this->fetcher = $fetcher;
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

        $oauth_request_parameters = $token->getRequestParameters;

        /** @var Token $uitid_token */
        $uitid_token = $this->fetcher->getAccessToken($oauth_request_parameters['oauth_token']);

        // Things to do here:
        // @TODO calculate signature & validate signature with signature in request.
        // @TODO validate timestamp.


        if ($this->validateRequest($token->getRequestParameters(), $token->getRequestMethod(), $token->getRequestUrl(), $uitid_token->getConsumer())) {
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

    public function validateRequest($requestParameters, $requestMethod, $requestUrl, $consumer)
    {
        $token    = $this->tokenProvider->loadAccessTokenByToken($requestParameters['oauth_token']);
        if (false === $this->nonceProvider->checkNonceAndTimestampUnicity($requestParameters['oauth_nonce'], $requestParameters['oauth_timestamp'], $consumer)) {
            throw new HttpException(400, self::ERROR_NONCE_USED);
        } else {
            $this->nonceProvider->registerNonceAndTimestamp($requestParameters['oauth_nonce'], $requestParameters['oauth_timestamp'], $consumer);
        }
        if (! $token instanceof AccessTokenInterface) {
            throw new HttpException(401, self::ERROR_TOKEN_REJECTED);
        }


        if (true !== $this->approveSignature($consumer, $token, $requestParameters, $requestMethod, $requestUrl)) {
            throw new HttpException(401, self::ERROR_SIGNATURE_INVALID);
        }
        if ($token->hasExpired()) {
            $this->tokenProvider->deleteAccessToken($token);
            throw new HttpException(401, self::ERROR_TOKEN_EXPIRED);
        }
        return true;
    }

    /**
     * Calculate the signature and compare it to the given signature.
     *
     * @param  Consumer          $consumer          A consumer.
     * @param  TokenInterface    $token             A token.
     * @param  array             $requestParameters An array of request parameters.
     * @param  string            $requestMethod     The request method.
     * @param  string            $requestUrl        The request UI
     * @return boolean           <code>true</code> if the provided signature is correct,
     *                   <code>false</code> otherwise.
     */
    protected function approveSignature(Consumer $consumer, TokenInterface $token = null, $requestParameters, $requestMethod, $requestUrl)
    {
        $signatureService = $this->getSignatureService($requestParameters['oauth_signature_method']);
        $baseString = $this->getSignatureBaseString($signatureService, $requestMethod, $requestUrl, $this->normalizeRequestParameters($requestParameters));
        $secretToken = (null !== $token) ? $token->getSecret() : '';
        $consumer = $token->
        $calculatedSignature = $signatureService->sign($baseString, $consumer->getSecret(), $secretToken);
        return ($calculatedSignature === $requestParameters['oauth_signature']);
    }
}
