<?php

namespace Scanpak\Shipping;

use Scanpak\Scanpak;
use Tightenco\Collect\Support\Collection as TightencoCollection;

class TrackAndTrace
{
    /**
     * @var Scanpak
     */
    protected $Scanpak;

    public function __construct(Scanpak $Scanpak)
    {
        $this->Scanpak = $Scanpak;
    }

    /**
     * Get Track and trace data
     *
     * @return array
     * @throws ScanpakAuthenticationException
     * @throws ScanpakAuthorizationException
     * @throws ScanpakServerErrorException
     * @throws ScanpakValidationException
     * @throws \Scanpak\Exceptions\ScanpakEndpointNotFoundException
     */

    public function trackAndTrace($trackingId)
    {
        $requestData = new TightencoCollection();
        $requestData->put('query', [
            'external_reference' => $trackingId
        ]);
        $response = $this->Scanpak->connector->request('GET', 'shipping/track-and-trace', 'v1', $requestData);
        return $response;
    }
}