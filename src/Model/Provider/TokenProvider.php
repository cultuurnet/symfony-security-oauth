<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 08/09/15
 * Time: 10:50
 */

namespace CultuurNet\SymfonySecurityOAuth\Model\Provider;

use CultuurNet\SymfonySecurityOAuth\Model\AccessTokenInterface;
use CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface;
use CultuurNet\SymfonySecurityOAuth\Model\RequestTokenInterface;
use CultuurNet\SymfonySecurityOAuth\Model\TokenInterface;
use CultuurNet\SymfonySecurityOAuth\Util\Random;

class TokenProvider implements TokenProviderInterface
{
    /**
     * @var string
     */
    private $requestTokenClass;

    /**
     * @var string
     */
    private $accessTokenClass;

    /**
     * Constructor
     *
     * @param string        $accessTokenClass
     * @param string        $requestTokenClass
     */
    public function __construct($requestTokenClass, $accessTokenClass)
    {
        $this->requestTokenClass = $requestTokenClass;
        $this->accessTokenClass = $accessTokenClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestTokenClass()
    {
        return $this->requestTokenClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getAccessTokenClass()
    {
        return $this->accessTokenClass;
    }

    /**
     * {@inheritDoc}
     */
    public function createRequestToken(ConsumerInterface $consumer)
    {
        $class = $this->getRequestTokenClass();

        /** @var \CultuurNet\SymfonySecurityOAuth\Model\RequestTokenInterface $requestToken */
        $requestToken = new $class;
        $requestToken->setToken(Random::generateToken());
        $requestToken->setSecret(Random::generateToken());
        $requestToken->setExpiresAt(time() + 3600);
        $requestToken->setVerifier(Random::generateToken());
        $requestToken->setConsumer($consumer);

        $this->updateToken($requestToken);

        return $requestToken;
    }

    /**
     * {@inheritDoc}
     */
    public function createAccessToken(ConsumerInterface $consumer, UserInterface $user)
    {
        $class = $this->getAccessTokenClass();

        /** @var \CultuurNet\SymfonySecurityOAuth\Model\AccessTokenInterface $accessToken */
        $accessToken = new $class;
        $accessToken->setToken(Random::generateToken());
        $accessToken->setSecret(Random::generateToken());
        $accessToken->setConsumer($consumer);
        $accessToken->setUser($user);

        $this->updateToken($accessToken);

        return $accessToken;
    }

    /**
     * @param array $criteria
     * @return \CultuurNet\SymfonySecurityOAuth\Model\RequestTokenInterface
     */
    public function loadRequestTokenBy(array $criteria)
    {
        // TODO: Implement loadRequestTokenBy() method.
    }

    /**
     * {@inheritDoc}
     */
    public function loadRequestTokenByToken($oauth_token)
    {
        return $this->loadRequestTokenBy(array('token' => $oauth_token));
    }

    /**
     * @return \Traversable
     */
    public function loadRequestTokens()
    {
        // TODO: Implement loadRequestTokens() method.
    }

    /**
     * @param array $criteria
     * @return AccessTokenInterface
     */
    public function loadAccessTokenBy(array $criteria)
    {
        // TODO: Implement loadAccessTokenBy() method.
    }

    /**
     * {@inheritDoc}
     */
    public function loadAccessTokenByToken($oauth_token)
    {
        return $this->loadAccessTokenBy(array('token' => $oauth_token));
    }

    /**
     * @return \Traversable
     */
    public function loadAccessTokens()
    {
        // TODO: Implement loadAccessTokens() method.
    }

    /**
     * {@inheritDoc}
     */
    public function setUserForRequestToken(RequestTokenInterface $requestToken, UserInterface $user)
    {
        $requestToken->setUser($user);

        $this->updateToken($requestToken);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteRequestToken(RequestTokenInterface $requestToken)
    {
        $this->deleteToken($requestToken);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteAccessToken(AccessTokenInterface $accessToken)
    {
        $this->deleteToken($accessToken);
    }

    /**
     * @return int The number of tokens deleted.
     */
    public function deleteExpired()
    {
        $tokens = array_merge($this->loadRequestTokens(), $this->loadAccessTokens());

        foreach ($tokens as $token) {
            if ($token->hasExpired()) {
                $this->deleteToken($token);
            }
        }
    }

    /**
     * Deletes a token.
     *
     * @param \CultuurNet\SymfonySecurityOAuth\Model\TokenInterface $token
     * @return void
     */
    public function deleteToken(TokenInterface $token)
    {
        // TODO: Implement deleteToken() method.
    }

    /**
     * Updates a token.
     *
     * @param \CultuurNet\SymfonySecurityOAuth\Model\TokenInterface $token
     * @return void
     */
    public function updateToken(TokenInterface $token)
    {
        // TODO: Implement updateToken() method.
    }
}
