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
     * @param $oauth_token
     * @return mixed
     */
    public function getAccessTokenByToken($oauth_token);
}
