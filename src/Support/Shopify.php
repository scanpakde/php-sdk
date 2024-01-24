<?php
namespace Scanpak\Support;

use Scanpak\Scanpak;
use Tightenco\Collect\Support\Collection as TightencoCollection;

/**
 * Class Shopify
 * @package Scanpak\Support
 */
class Shopify
{
    /**
     * @var Scanpak
     */
    private $Scanpak;

    /**
     * Shopify constructor.
     * @param  Scanpak  $Scanpak
     */
    public function __construct(Scanpak $Scanpak)
    {
        $this->Scanpak = $Scanpak;
    }

    /**
     * @param  int  $shopifyOrderId
     * @return array
     * @throws \Scanpak\Exceptions\Auth\ScanpakAuthenticationException
     * @throws \Scanpak\Exceptions\Auth\ScanpakAuthorizationException
     * @throws \Scanpak\Exceptions\ScanpakEndpointNotFoundException
     * @throws \Scanpak\Exceptions\ScanpakServerErrorException
     * @throws \Scanpak\Exceptions\ScanpakValidationException
     * @deprecated
     */
    public function notifyShopifyForFailedImport(int $shopifyOrderId): array
    {
        $requestData = new TightencoCollection();
        $requestData->put('json', [
            'shopify_order_id' => $shopifyOrderId
        ]);

        $response = $this->Scanpak->connector->request(
            'post',
            'temporary/handle-magento-shopify-failed',
            'v2',
            $requestData
        );

        return $response->toArray();
    }
}