<?php

namespace Scanpak;

use Scanpak\Invoices\Invoice;
use Scanpak\Orders\FetchOrders;
use Scanpak\Orders\Order;
use Scanpak\Orders\UpdateStoreOrder;
use Scanpak\Shipping\Control;
use Scanpak\Shipping\ShippingMethod;
use Tightenco\Collect\Support\Collection as TightencoCollection;

/**
 * Class Scanpak
 * @package Scanpak
 */
class Scanpak
{
    /**
     * @var bool
     */
    protected $testMode = true;

    /**
     * @var string $token
     */
    protected $token;

    /**
     * @var Order $order
     */
    protected $order;

    /**
     * @var Order $order
     */
    protected $orders;

    /**
     * @var ShippingMethod $shippingMethod
     */
    protected $shippingMethod;

    /**
     * @var Connector $connector
     */
    public $connector;

    const APP_URLS = [
        'live_env' => 'https://api.Scanpak.com/',
        'dev_env'  => 'https://test-api.Scanpak.com/',
        'stage_env' => 'https://stage-api.Scanpak.com/',
    ];

    public function __construct(string $token, bool $testMode, string $baseUrl = null)
    {
        $this->connector = new Connector($token, $testMode, $baseUrl);
        $this->testMode = $testMode;
        $this->token = $token;
    }

    /**
     * Get The authorization Token
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Set The authorization Token
     * @param string $token
     * @return Scanpak
     */
    public function setToken(string $token): Scanpak
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Set the order object
     *
     * @param Order $order
     * @return Scanpak
     */
    public function setOrder(Order $order): Scanpak
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Set the orders array
     *
     * @param Order $order
     * @return Scanpak
     */
    public function setOrders(array $orders): Scanpak
    {
        $this->orders = $orders;
        return $this;
    }

    /**
     * Prepare The Order
     * @return TightencoCollection
     */
    protected function prepareOrder(): TightencoCollection
    {
        $result = $this->order->getReceiver()->toArray();
        $result += $this->order->getOrderOptions()->toArray();
        $result['items'] = $this->order->getOrderItems()->toArray();
        return new TightencoCollection(['json' => $result]);
    }

    /**
     * Prepare The Order
     * @return TightencoCollection
     */
    protected function prepareBulkOrders(): TightencoCollection
    {
        foreach ($this->orders as $k => $order) {
            $result['orders'][$k] = $order->getReceiver()->toArray();
            $result['orders'][$k] += $order->getOrderOptions()->toArray();
            $result['orders'][$k]['items'] = $order->getOrderItems()->toArray();
        }

        return new TightencoCollection(['json' => $result]);
    }

    /**
     * Send the order
     *
     * @return array
     * @throws Exceptions\Auth\ScanpakAuthenticationException
     * @throws Exceptions\Auth\ScanpakAuthorizationException
     * @throws Exceptions\ScanpakEndpointNotFoundException
     * @throws Exceptions\ScanpakServerErrorException
     * @throws Exceptions\ScanpakValidationException
     */

    public function sendOrder(): array
    {
        $orderData = $this->prepareOrder();
        $response = $this->connector->request('post', 'order', 'v1', $orderData);
        return $response->toArray();
    }

    /**
     * Send the order bulk
     *
     * @return array
     * @throws Exceptions\Auth\ScanpakAuthenticationException
     * @throws Exceptions\Auth\ScanpakAuthorizationException
     * @throws Exceptions\ScanpakEndpointNotFoundException
     * @throws Exceptions\ScanpakServerErrorException
     * @throws Exceptions\ScanpakValidationException
     */

    public function sendBulkOrders($ordersForProcess): array
    {
        $ordersBulkData = $this->prepareBulkOrders();
        $connection = $this->connector;
        $response = $connection->request('post', 'order/store-bulk', 'v1', $ordersBulkData);
        return $response->toArray();
    }

    /**
     * Get Orders Object
     *
     * @return FetchOrders
     */
    public function getOrders(): FetchOrders
    {
        return new FetchOrders($this);
    }

    /**
     * Get Labels Object
     *
     * @return Label
     */
    public function labels(): Label
    {
        return new Label($this);
    }

    /**
     * Get Control Object
     * 
     * @return Control
     */
    public function control(): Control
    {
        return new Control($this);
    }

    /**
     * Update order from Store
     * @return UpdateStoreOrder
     */

    public function updateOrder(): UpdateStoreOrder
    {
        return new UpdateStoreOrder($this);
    }

    /**
     * Get Invoice
     * @return Invoice
     */
    public function invoice(): Invoice
    {
        return new Invoice($this);
    }

    /**
     * Change external status to Order
     * @param string $externalStatus
     * @param string $yourReference
     * @return Control
     */
    public function externalStatus(string $externalStatus, string $yourReference): array
    {
        $control = new Control($this);
        return $control->updateExternalOrderStatus($externalStatus, $yourReference)->toArray();
    }
}