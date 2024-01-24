<?php

namespace Scanpak\Shipping;

use Scanpak\Scanpak;
use Scanpak\Exceptions\ScanpakValidationException;
use Scanpak\Exceptions\ScanpakServerErrorException;
use Scanpak\Exceptions\ScanpakEndpointNotFoundException;
use Scanpak\Exceptions\Auth\ScanpakAuthorizationException;
use Scanpak\Exceptions\Auth\ScanpakAuthenticationException;
use Tightenco\Collect\Support\Collection as TightencoCollection;

/**
 * Class Control
 * @package Scanpak\Shipping
 */
class Control
{
    /**
     * @var Scanpak
     */
    private $Scanpak;

    /**
     * Control constructor.
     * @param Scanpak $Scanpak
     */
    public function __construct(Scanpak $Scanpak)
    {
        $this->Scanpak = $Scanpak;
    }

    public function updateExternalOrderStatus(string $externalStatus, string $yourReference)
    {
        $requestData = new TightencoCollection();
        $requestData->put('query', [
            'external_reference' => $yourReference,
            'external_status' => $externalStatus
        ]);

        return $this->Scanpak->connector->request('get', 'public/order/external-status', 'v1', $requestData);
    }

    /**
     * @param string $yourReference
     * @return array
     * @throws ScanpakAuthenticationException
     * @throws ScanpakAuthorizationException
     * @throws ScanpakServerErrorException
     * @throws ScanpakValidationException
     * @throws ScanpakEndpointNotFoundException
     */
    public function cancelShipment(string $yourReference): array
    {
        $requestData = new TightencoCollection();
        $requestData->put('query', [
            'external_reference' => $yourReference
        ]);

        return $this->Scanpak->connector->request('get', 'shipping/cancel', 'v1', $requestData)->toArray();
    }

    /**
     * Close Shipment
     * 
     *
     * @param  string  $yourReference
     * @return array
     * @throws ScanpakAuthenticationException
     * @throws ScanpakAuthorizationException
     * @throws ScanpakEndpointNotFoundException
     * @throws ScanpakServerErrorException
     * @throws ScanpakValidationException
     */
    public function closeShipment(string $yourReference): array
    {
        $requestData = new TightencoCollection();
        $requestData->put('query', [
            'external_reference' => $yourReference
        ]);
        return $this->Scanpak->connector->request('get', 'shipping/close', 'v1', $requestData)
            ->toArray();
    }

    /**
     * Test connection with Scanpak
     * @return array
     */
    public function testScanpakConnection(): array
    {
        return $this->Scanpak->connector->request('get', 'applications/get-details', 'v1', null)->toArray();
    }
}