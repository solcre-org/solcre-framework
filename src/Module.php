<?php

namespace Solcre\SolcreFramework2;

use Laminas\Http\Request;
use Laminas\Mvc\MvcEvent;
use Laminas\Uri\Uri;
use Laminas\Uri\UriFactory;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap($e)
    {
        $this->checkOriginHeader($e);
    }

    private function checkOriginHeader($e): void
    {
        UriFactory::registerScheme('chrome-extension', Uri::class);
        UriFactory::registerScheme('file', Uri::class);
        UriFactory::registerScheme('ionic', Uri::class);
        if ($e instanceof MvcEvent) {
            $request = $e->getRequest();
            if ($request instanceof Request) {
                $headers = $e->getRequest()->getHeaders();
                if ($headers->has('Origin')) {
                    $headersArray = $headers->toArray();
                    $origin = $headersArray['Origin'];

                    if ($origin === 'file://') {
                        unset($headersArray['Origin']);
                        $headers->clearHeaders();
                        $headers->addHeaders($headersArray);
                        //this is a valid uri
                        $headers->addHeaderLine('Origin', 'file://mobile');
                    }
                }
            }
        }
    }
}
