<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 15/09/15
 * Time: 10:04
 */

namespace CultuurNet\SymfonySecurityOAuth\Security;

use CultuurNet\SymfonySecurityOAuth\Model\Consumer;
use CultuurNet\SymfonySecurityOAuth\Service\UserMock;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class OAuthAuthenticationProviderMock implements AuthenticationProviderInterface
{

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
        $params = $token->getRequestParameters();

        if ($params['oauth_token'] == 'nnch734d00sl2jdk') {
            $user = new UserMock('123456789', 'testUser', 'email@email.email');
            $token->setUser($user);

            return $token;
        } else {
            throw new AuthenticationException('OAuth authentication failed');
        }
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     *
     * @return bool true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof OAuthToken;
    }
}
