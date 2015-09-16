<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 10/09/15
 * Time: 16:42
 */

namespace CultuurNet\SymfonySecurityOAuth\Service\Signature;

class OAuthHmacSha1SignatureMock extends OAuthHmacSha1Signature
{
    public function hashHmacSha1($baseString, $key)
    {
        return parent::hashHmacSha1($baseString, $key);
    }
}
