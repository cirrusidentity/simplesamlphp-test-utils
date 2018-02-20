<?php

namespace CirrusIdentity\SSP\Test\Capture;

class RedirectException extends ArgumentCaptureException
{

    /**
     * Get the URL to redirect to
     * @return string
     */
    public function getUrl() {
        return $this->getArguments()[0];
    }

    /**
     * Get the paramaters used with the redirect.
     * @return array
     */
    public function getParams() {
        return $this->getArguments()[1];
    }

}