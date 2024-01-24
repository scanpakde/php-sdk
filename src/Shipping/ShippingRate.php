<?php

namespace Scanpak\Shipping;

use Scanpak\Exceptions\Auth\ScanpakAuthenticationException;
use Scanpak\Exceptions\Auth\ScanpakAuthorizationException;
use Scanpak\Exceptions\Auth\ScanpakEndpointNotFoundException;
use Scanpak\Exceptions\ScanpakServerErrorException;
use Scanpak\Exceptions\ScanpakValidationException;
use Scanpak\Scanpak;

class ShippingRate
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
     * Get All Shipping Rates
     *
     * @return array
     * @throws ScanpakAuthenticationException
     * @throws ScanpakAuthorizationException
     * @throws ScanpakEndpointNotFoundException
     * @throws ScanpakServerErrorException
     * @throws ScanpakValidationException
     */
    public function getShippingRates(): array
    {
        $response = $this->Scanpak->connector->request('GET', 'shipping-rates');
        return $response->toArray();
    }
}