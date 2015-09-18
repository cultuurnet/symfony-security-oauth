<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 08/09/15
 * Time: 10:57
 */

namespace CultuurNet\SymfonySecurityOAuth\Model\Provider;

use CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface;

interface NonceProviderInterface
{
    /**
     * @param $nonce
     * @param $timestamp
     * @param  \CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface $consumer
     * @return boolean
     */
    public function registerNonceAndTimestamp($nonce, $timestamp, ConsumerInterface $consumer);
}
