<?php

namespace Scanpak\Orders;

use DateTime;
use Scanpak\Exceptions\Auth\ScanpakAuthenticationException;
use Scanpak\Exceptions\Auth\ScanpakAuthorizationException;
use Scanpak\Exceptions\ScanpakEndpointNotFoundException;
use Scanpak\Exceptions\ScanpakSdkException;
use Scanpak\Exceptions\ScanpakServerErrorException;
use Scanpak\Exceptions\ScanpakValidationException;
use Scanpak\Scanpak;
use Throwable;
use Tightenco\Collect\Support\Collection as TCollection;

class FetchOrders
{
    /**
     * @var Scanpak
     */
    private $Scanpak;

    /**
     * @var TCollection
     */
    private $query;

    /**
     * FetchOrders constructor.
     * @param  Scanpak  $Scanpak
     */
    public function __construct(Scanpak $Scanpak)
    {
        $this->Scanpak = $Scanpak;
        $this->query = collect();
    }

    /**
     * Between Filter
     *
     * @param  string  $start  Format: d/m/Y H:i:s
     * @param  string  $end  Format: d/m/Y H:i:s
     * @param  string  $property created OR updated
     * @return FetchOrders
     * @throws ScanpakSdkException
     */
    public function between(string $start, string $end, string $property = 'created'): self
    {
        foreach ([$start, $end] as $k => $value) {
            try {
                DateTime::createFromFormat('d/m/Y H:i:s', $value);
            } catch (Throwable $exception) {
                throw new ScanpakSdkException("{$value} is not in correct format. Correct is: d/m/Y H:i:s");
            }
        }
        $this->query->put("filter[{$property}_between]", "{$start},{$end}");

        return $this;
    }


    /**
     * Status Filter
     *
     * Available Statuses:
     *  On Hold
     *  Ready For Print
     *  Awaiting Payment
     *  Labels Requested
     *  Merging Labels
     *  Printed/Shipped
     *
     * @param  mixed  ...$statuses
     * @return $this
     */
    public function statuses(...$statuses): self
    {

        $this->query->put('filter[statuses]', implode(',', $statuses));

        return $this;
    }

    /**
     * Carriers Filter
     *
     * @param  mixed  ...$carriers
     * @return $this
     */
    public function carriers(...$carriers): self
    {
        $this->query->put('filter[carriers]', implode(',', $carriers));

        return $this;
    }

    /**
     * Paginated Result
     *
     * @param  int  $currentPage
     * @param  int  $perPage
     * @return $this
     */
    public function paginated(int $currentPage = 1, int $perPage = 50): self
    {
        $this->query->put('perPage', $perPage);
        $this->query->put('page', $currentPage);

        return $this;
    }

    /**
     * Sort Method
     *
     * @param  string  $property
     * @param  string  $direction
     * @return FetchOrders
     * @throws ScanpakSdkException
     */
    public function sortBy(string $property = 'created_at', string $direction = 'desc'): self
    {
        $direction = strtolower($direction);
        $sort = null;
        switch ($direction) {
            case 'desc':
                $sort = "-{$property}";
                break;
            case 'asc':
                $sort = "{$property}";
                break;
            default:
                throw new ScanpakSdkException("Unknown sort direction. asc and desc are allowed");
        }

        $this->query->put('sort', $sort);

        return $this;
    }

    /**
     * Append Additional Fields To Response
     *
     * @param  mixed  ...$appends
     * @return $this
     */
    public function appends(...$appends): self
    {
        $this->query->put('appends', implode(",", $appends));

        return $this;
    }

    /**
     * Include Additional Resources
     *
     * @param  mixed  ...$includes
     * @return $this
     */
    public function include(...$includes): self
    {
        $this->query->put('include', implode(",", $includes));

        return $this;
    }

    public function getByReference(string $reference): array
    {
        return $this
            ->Scanpak
            ->connector
            ->request('get', 'orders/by-reference/' . $reference, 'v2')
            ->toArray();
    }

    /**
     * @return array
     * @throws ScanpakAuthenticationException
     * @throws ScanpakAuthorizationException
     * @throws ScanpakEndpointNotFoundException
     * @throws ScanpakServerErrorException
     * @throws ScanpakValidationException
     */
    public function fetch(): array
    {
        $requestData = collect();
        $requestData->put('query', $this->query->toArray());

        return $this
            ->Scanpak
            ->connector
            ->request('get', 'order', 'v1', $requestData)
            ->toArray();
    }
}