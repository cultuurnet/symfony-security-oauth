<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 08/09/15
 * Time: 12:10
 */

namespace CultuurNet\SymfonySecurityOAuth\Model\Provider;

use CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface;

class NonceProvider implements NonceProviderInterface
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
