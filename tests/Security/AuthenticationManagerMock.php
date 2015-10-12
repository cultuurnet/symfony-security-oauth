<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 11/09/15
 * Time: 15:24
 */

namespace CultuurNet\SymfonySecurityOAuth\Security;

use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthenticationManagerMock implements AuthenticationManagerInterface
{

    /** @var  \CultuurNet\SymfonySecurityOAuth\Security\OAuthAuthenticationProviderMock */
    protected $authenticationProvider;

    public function __construct(OAuthAuthenticationProviderMock $authenticationProviderMock)
    {
        $this->authenticationProvider = $authenticationProviderMock;
    }

    /**
     * Attempts to authenticate a TokenInterface object.
     *
     * @param TokenInterface $token The TokenInterface instance to authenticate
     *
     * @return TokenInterface An authenticated TokenInterface instance, never null
     *
     * @throws AuthenticationException if the authentication fails
     */
    public function authenticate(TokenInterface $token)
    {
        return $this->authenticationProvider->authenticate($token);
    }
}
