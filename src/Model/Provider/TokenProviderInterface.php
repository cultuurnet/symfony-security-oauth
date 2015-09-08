<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 08/09/15
 * Time: 10:47
 */

namespace CultuurNet\SymfonySecurityOAuth\Model\Provider;

use CultuurNet\SymfonySecurityOAuth\Model\AccessTokenInterface;
use CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface;
use CultuurNet\SymfonySecurityOAuth\Model\RequestTokenInterface;
use CultuurNet\SymfonySecurityOAuth\Model\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface TokenProviderInterface
{
    /**
     * Returns the request token's fully qualified class name.
     *
     * @return string
     */
    public function getRequestTokenClass();

    /**
     * Returns the access token's fully qualified class name.
     *
     * @return string
     */
    public function getAccessTokenClass();

    /**
     * Create a request token.
     *
     * @param  \CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface     $consumer An OAuth consumer.
     * @return \CultuurNet\SymfonySecurityOAuth\Model\RequestTokenInterface
     */
    public function createRequestToken(ConsumerInterface $consumer);

    /**
     * Create an access token.
     *
     * @param  \CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface    $consumer An OAuth consumer.
     * @param  \Symfony\Component\Security\Core\User\UserInterface         $user
     * @return \CultuurNet\SymfonySecurityOAuth\Model\AccessTokenInterface
     */
    public function createAccessToken(ConsumerInterface $consumer, UserInterface $user);

    /**
     * @param array $criteria
     * @return \CultuurNet\SymfonySecurityOAuth\Model\RequestTokenInterface
     */
    public function loadRequestTokenBy(array $criteria);

    /**
     * @param $oauth_token
     * @return mixed
     */
    public function loadRequestTokenByToken($oauth_token);

    /**
     * @return \Traversable
     */
    public function loadRequestTokens();

    /**
     * @param array $criteria
     * @return AccessTokenInterface
     */
    public function loadAccessTokenBy(array $criteria);

    /**
     * @param $oauth_token
     * @return mixed
     */
    public function loadAccessTokenByToken($oauth_token);

    /**
     * @return \Traversable
     */
    public function loadAccessTokens();
    /**
     * @param  \CultuurNet\SymfonySecurityOAuth\Model\RequestTokenInterface $requestToken
     * @param  \Symfony\Component\Security\Core\User\UserInterface          $user
     * @return mixed
     */
    public function setUserForRequestToken(RequestTokenInterface $requestToken, UserInterface $user);

    /**
     * @param  \CultuurNet\SymfonySecurityOAuth\Model\RequestTokenInterface $requestToken
     * @return mixed
     */
    public function deleteRequestToken(RequestTokenInterface $requestToken);

    /**
     * @param  \CultuurNet\SymfonySecurityOAuth\Model\AccessTokenInterface $accessToken
     * @return mixed
     */
    public function deleteAccessToken(AccessTokenInterface $accessToken);

    /**
     * @return int The number of tokens deleted.
     */
    public function deleteExpired();

    /**
     * Deletes a token.
     *
     * @param \CultuurNet\SymfonySecurityOAuth\Model\TokenInterface $token
     * @return void
     */
    public function deleteToken(TokenInterface $token);

    /**
     * Updates a token.
     *
     * @param \CultuurNet\SymfonySecurityOAuth\Model\TokenInterface $token
     * @return void
     */
    public function updateToken(TokenInterface $token);
}
