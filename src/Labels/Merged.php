<?php

namespace Scanpak\Labels;

use Scanpak\Exceptions\Auth\ScanpakAuthenticationException;
use Scanpak\Exceptions\Auth\ScanpakAuthorizationException;
use Scanpak\Exceptions\ScanpakEndpointNotFoundException;
use Scanpak\Exceptions\ScanpakServerErrorException;
use Scanpak\Exceptions\ScanpakValidationException;
use Scanpak\Scanpak;
use Tightenco\Collect\Support\Collection as TightencoCollection;

/**
 * Class Merged
 * @package Scanpak\Labels
 */
class Merged
{
    /**
     * @var Scanpak
     */
    private $Scanpak;

    /**
     * Merged constructor.
     * @param Scanpak $Scanpak
     */
    public function __construct(Scanpak $Scanpak)
    {
        $this->Scanpak = $Scanpak;
    }

    /**
     * Prepare the request
     *
     * @param array $references
     * @param array $options
     * @return TightencoCollection
     */
    protected function prepareRequest(array $references, array $options = []): TightencoCollection
    {
        $request = new TightencoCollection([
            'json' => $references
        ]);
        $request->put('query', $options);

        return $request;
    }

    /**
     * Get Labels for specific orders
     *
     * @param array $references
     * @param array $options
     * @return array
     * @throws ScanpakAuthenticationException
     * @throws ScanpakAuthorizationException
     * @throws ScanpakEndpointNotFoundException
     * @throws ScanpakServerErrorException
     * @throws ScanpakValidationException
     */
    public function bySelectedOrders(array $references, array $options = []): array
    {
        $options = $this->prepareRequest($references, $options);
        $response = $this->Scanpak->connector->request(
            'POST',
            'label/get/selected-orders',
            "v1",
            $options
        );
        return $response->toArray();
    }
}