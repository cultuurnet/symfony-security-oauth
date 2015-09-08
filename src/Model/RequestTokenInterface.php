<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 08/09/15
 * Time: 11:08
 */

namespace CultuurNet\SymfonySecurityOAuth\Model;

interface RequestTokenInterface extends TokenInterface
{
    /**
     * Returns the verifier string.
     * @return string
     */
    public function getVerifier();

    /**
     * Sets the verifier string.
     * @param string $verifier
     * @return self
     */
    public function setVerifier($verifier);
}
