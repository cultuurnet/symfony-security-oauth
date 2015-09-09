<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 08/09/15
 * Time: 10:53
 */

namespace CultuurNet\SymfonySecurityOAuth\Model\Provider;

interface ConsumerProviderInterface
{
    /**
     * @param $consumerKey
     * @return \CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface
     */
    public function getConsumerByKey($consumerKey);
}
