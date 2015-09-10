<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 10/09/15
 * Time: 11:06
 */

namespace CultuurNet\SymfonySecurityOAuth\Service;

use CultuurNet\SymfonySecurityOAuth\Model\Provider\TokenProviderInterface;

class TokenProviderMock implements TokenProviderInterface
{

    /**
     * @param string $oauth_token
     * @return \CultuurNet\SymfonySecurityOAuth\Model\TokenInterface
     */
    public function getAccessTokenByToken($oauth_token)
    {
        // TODO: Implement getAccessTokenByToken() method.
    }

    /**
     * @param \CultuurNet\SymfonySecurityOAuth\Model\TokenInterface $token
     * @return mixed
     */
    public function deleteAccessToken($token)
    {
        // TODO: Implement deleteAccessToken() method.
    }
}
