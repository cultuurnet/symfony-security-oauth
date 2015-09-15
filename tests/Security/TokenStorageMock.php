<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 11/09/15
 * Time: 15:23
 */

namespace CultuurNet\SymfonySecurityOAuth\Security;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TokenStorageMock implements TokenStorageInterface
{

    /** @var  \CultuurNet\SymfonySecurityOAuth\Model\Token */
    protected $token;
    /**
     * Returns the current security token.
     *
     * @return TokenInterface|null A TokenInterface instance or null if no authentication information is available
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Sets the authentication token.
     *
     * @param TokenInterface $token A TokenInterface token, or null if no further authentication information should be stored
     */
    public function setToken(TokenInterface $token = null)
    {
        $this->token = $token;

        return $this->token;
    }
}
