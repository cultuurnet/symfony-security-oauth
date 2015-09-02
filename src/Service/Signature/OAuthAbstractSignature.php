<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 02/09/15
 * Time: 12:39
 */

namespace CultuurNet\SymfonySecurityOAuth\Service\Signature;

abstract class OAuthAbstractSignature implements OAuthSignatureInterface
{
    /**
     * Returns an encoded string according to the RFC3986.
     *
     * @return string
     */
    public function urlencode($string)
    {
        return str_replace('%7E', '~', rawurlencode($string));
    }
}
