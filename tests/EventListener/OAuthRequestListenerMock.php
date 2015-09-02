<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 02/09/15
 * Time: 16:27
 */

namespace CultuurNet\SymfonySecurityOAuth\EventListener;

use Symfony\Component\HttpFoundation\Request;

class OAuthRequestListenerMock extends OAuthRequestListener
{
    public function parseAuthorizationHeader(Request $request)
    {
        return parent::parseAuthorizationHeader($request);
    }
    public function buildRequestUrl(Request $request)
    {
        return parent::buildRequestUrl($request);
    }
}
