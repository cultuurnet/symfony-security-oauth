<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 01/09/15
 * Time: 10:56
 */

namespace CultuurNet\SymfonySecurityOAuth;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class OAuthAuthenticationProvider implements AuthenticationProviderInterface
{
    public function authenticate(TokenInterface $token)
    {

    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof OAuthToken;
    }
}
