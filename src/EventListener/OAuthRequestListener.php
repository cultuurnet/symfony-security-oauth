<?php
namespace CultuurNet\SymfonySecurityOAuth\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Called early, this listener will add some oauth attributes to the Request if
 * the current Request is an OAuth request (by checking parameters).
 */
class OAuthRequestListener
{
    /**
     * {@inheritdoc}
     */
    public function onEarlyKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }
        $request    = $event->getRequest();
        $parameters = $this->filterRequestParameters($request);
        // check if it's an oauth request or not
        if (false === array_key_exists('oauth_token', $parameters)&&
            false === array_key_exists('oauth_consumer_key', $parameters)
        ) {
            return;
        }
        $request->attributes->set('oauth_request_parameters', $parameters);
        $request->attributes->set('oauth_request_method', $request->getMethod());
        $request->attributes->set('oauth_request_url', $this->buildRequestUrl($request));
    }

    /**
     * Merge all acceptable parameters.
     *
     * @param  Request $request The request.
     * @return array
     */
    protected function filterRequestParameters(Request $request)
    {
        return array_merge(
            $this->parseAuthorizationHeader($request),
            $request->query->all(),
            $request->request->all()
        );
    }

    /**
     * Parse the Authorization header if available.
     *
     * @param  Request $request The request.
     * @return array
     */
    protected function parseAuthorizationHeader(Request $request)
    {
        $authorization = $request->headers->get('authorization');

        $params = array();
        if (!$authorization) {
            return $params;
        }
        // Remove 'OAuth' string
        $authorization = substr($authorization, 6);
        foreach (preg_split('#,#', $authorization) as $parameter) {
            $split = preg_split('#=#', $parameter);
            if (2 !== count($split)) {
                continue;
            }
            $key   = trim($split[0]);
            $value = str_replace('"', '', trim($split[1]));
            $params[$key] = rawurldecode($value);
        }
        return $params;
    }

    /**
     * Build a valid Request URL based on the Request.
     * @see http://oauth.net/core/1.0/#sig_url
     *
     * @param  Request $request The request.
     * @return string
     */
    protected function buildRequestUrl(Request $request)
    {
        return sprintf(
            '%s://%s%s%s',
            $request->getScheme(),
            $request->getHttpHost(),
            $request->getBaseUrl(),
            $request->getPathInfo()
        );
    }
}
