<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 02/09/15
 * Time: 12:36
 */

namespace CultuurNet\SymfonySecurityOAuth\Service\Signature;

interface OAuthSignatureInterface
{
    /**
     * Apply the implemented algorithm to create a signature and returns it.
     *
     * @see http://oauth.net/core/1.0a/#signing_process
     *
     * @param  string $baseString     The base string (see http://oauth.net/core/1.0a/#rfc.section.9.1)
     * @param  string $consumerSecret The shared secret token.
     * @param  string $tokenSecret    A secret token.
     * @return string The generated signature.
     */
    public function sign($baseString, $consumerSecret, $tokenSecret = '');
    /**
     * Returns the signature name.
     * @return string The signature name.
     */
    public function getName();
}
