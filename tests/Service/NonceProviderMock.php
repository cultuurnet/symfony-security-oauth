<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 10/09/15
 * Time: 11:07
 */

namespace CultuurNet\SymfonySecurityOAuth\Service;

use CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface;
use CultuurNet\SymfonySecurityOAuth\Model\Provider\NonceProviderInterface;

class NonceProviderMock implements NonceProviderInterface
{

    /**
     * @param $nonce
     * @param $timestamp
     * @param  \CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface $consumer
     * @return boolean
     */
    public function checkNonceAndTimestampUnicity($nonce, $timestamp, ConsumerInterface $consumer)
    {
        // TODO: Implement checkNonceAndTimestampUnicity() method.
    }

    /**
     * @param $nonce
     * @param $timestamp
     * @param  \CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface $consumer
     * @return boolean
     */
    public function registerNonceAndTimestamp($nonce, $timestamp, ConsumerInterface $consumer)
    {
        // TODO: Implement registerNonceAndTimestamp() method.
    }
}
