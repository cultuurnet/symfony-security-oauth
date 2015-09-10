<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 10/09/15
 * Time: 11:04
 */

namespace CultuurNet\SymfonySecurityOAuth\Service;

use CultuurNet\SymfonySecurityOAuth\Model\Provider\ConsumerProviderInterface;

class ConsumerProviderMock implements ConsumerProviderInterface
{

    /**
     * @param $consumerKey
     * @return \CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface
     */
    public function getConsumerByKey($consumerKey)
    {
        // TODO: Implement getConsumerByKey() method.
    }
}
