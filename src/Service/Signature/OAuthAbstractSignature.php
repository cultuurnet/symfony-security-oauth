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
     * {@inheritdoc}
     */
    public function urlEncode($string)
    {
        return str_replace('%7E', '~', rawurlencode($string));
    }
}
