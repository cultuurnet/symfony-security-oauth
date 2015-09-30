<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 02/09/15
 * Time: 12:38
 */

namespace CultuurNet\SymfonySecurityOAuth\Service\Signature;

class OAuthHmacSha1Signature extends OAuthAbstractSignature
{
    /**
     * {@inheritdoc}
     */
    public function sign($baseString, $consumerSecret, $tokenSecret = '')
    {
        $key = $this->urlencode($consumerSecret) . '&' . $this->urlencode($tokenSecret);
        $signature = (hash_hmac('sha1', $baseString, $key, true));

        return base64_encode($signature);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'HMAC-SHA1';
    }
}
