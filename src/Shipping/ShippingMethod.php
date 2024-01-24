<?php

namespace Scanpak\Shipping;

use Scanpak\Exceptions\Auth\ScanpakAuthenticationException;
use Scanpak\Exceptions\Auth\ScanpakAuthorizationException;
use Scanpak\Exceptions\Auth\ScanpakEndpointNotFoundException;
use Scanpak\Exceptions\ScanpakServerErrorException;
use Scanpak\Exceptions\ScanpakValidationException;
use Tightenco\Collect\Support\Collection as TightencoCollection;
use Scanpak\Scanpak;

class ShippingMethod
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
     * Transform Shipping Methods Response
     *
     * @param TightencoCollection $collection
     * @return TightencoCollection
     */
    protected function transform(TightencoCollection $collection): TightencoCollection
    {
        $data = new TightencoCollection($collection->get('data'));
        $data->transform(function ($item) {
            $rates = new TightencoCollection(data_get($item, 'shipping_rates', []));
            $rates = $rates->map(function ($rate) {
                return [
                    'id' => $rate['id'],
                    'name' => $rate['name'],
                    'for_return' => $rate['for_return'],
                    'on_pallet' => $rate['on_pallet'],
                    'shipping_fee' => $rate['shipping_fee'],
                ];
            });
           return [
               'id' => $item['id'],
               'name' => $item['name'],
               'description' => $item['description'],
               'for_return' => $item['for_return'],
               'max_weight' => $item['max_weight'],
               'max_width' => $item['max_width'],
               'max_height' => $item['max_height'],
               'max_length' => $item['max_length'],
               'on_pallet' => $item['on_pallet'],
               'rates' => $rates->toArray()
           ];
        });

        $collection->put('data', $data);

        return $collection;
    }

    /**
     * Get All Shipping Methods
     *
     * @return array
     * @throws ScanpakAuthenticationException
     * @throws ScanpakAuthorizationException
     * @throws ScanpakServerErrorException
     * @throws ScanpakValidationException
     * @throws \Scanpak\Exceptions\ScanpakEndpointNotFoundException
     */
    public function getShippingMethods(): array
    {
        $requestData = new TightencoCollection();
        $requestData->put('query', [
            'include' => 'shipping_rates'
        ]);
        $response = $this->Scanpak->connector->request('GET', 'shipping-methods', 'v1', $requestData);

        return $this->transform($response)->toArray();
    }
}