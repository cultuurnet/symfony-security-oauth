<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 08/09/15
 * Time: 11:09
 */

namespace CultuurNet\SymfonySecurityOAuth\Model;

abstract class RequestToken extends Token implements RequestTokenInterface
{
    /**
     * @var string
     */
    protected $verifier;

    /**
     * {@inheritDoc}
     */
    public function getVerifier()
    {
        return $this->verifier;
    }

    /**
     * {@inheritDoc}
     */
    public function setVerifier($verifier)
    {
        $this->verifier = $verifier;
    }
}
