<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 08/09/15
 * Time: 10:47
 */

namespace CultuurNet\SymfonySecurityOAuth\Model\Provider;

interface TokenProviderInterface
{
    /**
     * @param string $oauth_token
     * @return \CultuurNet\SymfonySecurityOAuth\Model\TokenInterface
     */
    public function getAccessTokenByToken($oauth_token);

    /**
     * @param \CultuurNet\SymfonySecurityOAuth\Model\TokenInterface $token
     * @return mixed
     */
    public function deleteAccessToken($token);
}
