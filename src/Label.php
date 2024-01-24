<?php

namespace Scanpak;

use Tightenco\Collect\Support\Collection as TightencoCollection;

/**
 * Class Label
 * @package Scanpak
 */
class Label
{
    private $Scanpak;

    /**
     * Label constructor.
     * @param Scanpak $Scanpak
     */
    public function __construct(Scanpak $Scanpak)
    {
        $this->Scanpak = $Scanpak;
    }

    /**
     * @param string $yourReference
     * @param bool $print
     * @param string|null $printerId
     * @param string $labelFormat
     * @return array
     * @throws Exceptions\Auth\ScanpakAuthenticationException
     * @throws Exceptions\Auth\ScanpakAuthorizationException
     * @throws Exceptions\ScanpakEndpointNotFoundException
     * @throws Exceptions\ScanpakServerErrorException
     * @throws Exceptions\ScanpakValidationException
     */
    public function getLabelForSingleOrder(
        string $yourReference,
        bool $print = false,
        string $printerId = null,
        string $labelFormat = 'PDF'
    ): array {
        $endPoint = 'label/get/single';

        $requestData = new TightencoCollection();
        $requestData->put('query', [
            'reference' => (string) $yourReference,
            'print' => (int)$print,
            'printer_id' => $printerId,
            'label_format' => $labelFormat
        ]);

        return $this->Scanpak->connector->request('GET', $endPoint, 'v1', $requestData)->toArray();
    }


    /**
     * Get Labels for selected Order
     *
     * @param array $yourReferences
     * @param bool $print
     * @param null $printerId
     * @param string $labelFormat
     * @param int $dispatchNow
     * @return array
     * @throws Exceptions\Auth\ScanpakAuthenticationException
     * @throws Exceptions\Auth\ScanpakAuthorizationException
     * @throws Exceptions\ScanpakEndpointNotFoundException
     * @throws Exceptions\ScanpakServerErrorException
     * @throws Exceptions\ScanpakValidationException
     */
    public function getLabelsForSelectedOrders(
        array $yourReferences,
        bool $print = false,
        $printerId = null,
        string $labelFormat = 'pdf',
        int $dispatchNow = 0
    ): array {
        $endPoint = 'label/get/selected-orders';
        $requestData = new TightencoCollection();
        $requestData->put('query', [
            'print' => (int)$print,
            'printer_id' => $printerId,
            'label_format' => $labelFormat,
            'dispatch-now' => $dispatchNow
        ]);
        $requestData->put('json', [
            'references' => $yourReferences
        ]);

        return $this->Scanpak->connector->request('post', $endPoint, 'v1', $requestData)->toArray();
    }
}