<?php
include_once 'vendor/autoload.php';

use Scanpak\Scanpak;
use Scanpak\Shipping\ShippingRate;
use Scanpak\Exceptions\ScanpakValidationException;
use Scanpak\Exceptions\ScanpakServerErrorException;
use Scanpak\Exceptions\Auth\ScanpakAuthorizationException;
use Scanpak\Exceptions\Auth\ScanpakAuthenticationException;

$testMode = true;
$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjRlZmY1MDU5NmFlNmIyZjA1ZDQ0NDQ0YjE1ZWJhMzBmZWVhYTc1M2IyYTVkMmE0MTQ5MDFmZTc3OWQ4MmFkY2ZmNmNjN2RhYjc0MWI3MjQ0In0.eyJhdWQiOiIyIiwianRpIjoiNGVmZjUwNTk2YWU2YjJmMDVkNDQ0NDRiMTVlYmEzMGZlZWFhNzUzYjJhNWQyYTQxNDkwMWZlNzc5ZDgyYWRjZmY2Y2M3ZGFiNzQxYjcyNDQiLCJpYXQiOjE1Njc2MDc0NTMsIm5iZiI6MTU2NzYwNzQ1MywiZXhwIjoxNTk5MjI5ODUzLCJzdWIiOiI0Iiwic2NvcGVzIjpbXX0.HY0wbw7wvWuyAEov3bJEhiNeF20VFkxQT5PihPJMRm0IQS1tMV-vkZUAu4vaGgXQhILEJHksGKuRCz6tu_O6Msebgtqrx-Y_Z6mVirPrMMRvJYtegaywbpo3Lu6f2vETb4AjbMhxJJPy3h0CHuh_DV89gSHRHjxmvpd5PIZM3wqVfwQpbC_PhpCZ_rZY28MHA5XeBV2CKsihmGdUOsv1sh3hizljA2JUPCuihnjHm-1BNQ0wEZrL9Ihex7gmm-cIb6OMC4dKby6n_Wry6REjE751q5I0ajaT4Qr4zBran_dmSZf7wWDRh9jWLPFbDg9V7QTpDNoVGxV3EZ1XaWIdxMwI0B9ts3mwMN7enI_CUpcWuypEtiqherB80QNRWhvyQDiDxGzj72kgi3htqsidNGR5h8CvWMTlLCs448nMHdlR1FDU7FH5TBgAkqPkc_eLjdrKg8YHT7OiY1bK0-IYxAuBIxjT51ROkTY5MwyegrNY1F1HvTD6RmtF8qzl4jA7exh_5fsS4uahDdRKxa-BWbLxA5WVbHgMOHdhTCZavgQ1MHwX3pkxgb21LPp9fbGXn2GmoXk1CuFggFSvxRp1GJAJPNyHFbfKhL6YUJfr1C16NmU0wds_yRSb8guNGvumRZ9HkQoS72Z-oj7Dt9F7B0WaMgyMjGTtn1lem7sy1yQ";

// $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjQ2ZDNkYzRhODVhOTViNDNmMzM1YTQzYmEzMzRkZjQwYmI5NzJiNzQ4OTE0MGNjMTMyZGQ1ZjkyZTIxZDNlMGQ3ZGVkN2M1YzQ4ZTg3MTM5In0.eyJhdWQiOiIxIiwianRpIjoiNDZkM2RjNGE4NWE5NWI0M2YzMzVhNDNiYTMzNGRmNDBiYjk3MmI3NDg5MTQwY2MxMzJkZDVmOTJlMjFkM2UwZDdkZWQ3YzVjNDhlODcxMzkiLCJpYXQiOjE1Njc1ODUzNDQsIm5iZiI6MTU2NzU4NTM0NCwiZXhwIjoxNTk5MjA3NzQ0LCJzdWIiOiI0Iiwic2NvcGVzIjpbImZ1bGwtYWNjZXNzLXRvLWNvbXBhbnktb3JkZXJzIl19.0KyIVskxw_RV39xi-wAU3TndL2X44nde7alTJxpoocGckS8FFaeBk1lK07J8kZtK0uXAwEKZPIK6WTm3DF95usn-MqMfXY6W3v6eNndG9TDE1VBB5iSNbmHT2VcZD_JwReTGje9fS1CUKA1RSRx7yYJskgXGFF0xHZDdynLc97liVVTxtHM0LPeIujOgj4s5Lrg2lacy9Y9RGZcCbJQYG8VfKF0xum00S01pmOLRA9CAn3_q_qMxWuS2cFdYLnGgcoL7LrJ3BR1Rh7_CLd8q5m06kX6quSqoFAXsiQUvmSBHDDUtwcUvTZvSPLxo8C1c6SNf2E1IBkrMXLAJcqVIGIY_fxkX4j_TeSGJg06TMDuqqd3iTMQhfEZ0FSGVcszi4JwHHTeDFlKTGIT2LJfD3eAI2e_DqLfrbYJ8ln8XwtxRNZfD5xaWcWRn5Yl_0_WCXFxNfb__GfcsYwavATM9jE0XPG0svm6lA_VJ5JyqjkBkcFYhaSg3hbCw3KrCA1XSjc3E9OhRkmAWGKFddIlzTTBwBDOVpKdDdwHCXHqZCiIyU7eKpeOnTnAq1-0LCX_QsBYsbfZdMuzCtlLYrUpi3lfumwlW7RpiM-H4fD4gXjTualDrtDZGCApcwbsQCFSRcIVx_vtfPNNgNbaJSvSgFvV_Tq0EUBE4gShclmwnL3I';

$Scanpak = new Scanpak($token, $testMode);
$shippingRate = new ShippingRate($Scanpak);

try {
    $response = $shippingRate->getShippingRates();
} catch (ScanpakValidationException $validationException) {
    print_r($validationException->getValidationErrors());
} catch (ScanpakServerErrorException $serverErrorException) {
    print_r([
        'message' => $serverErrorException->getMessage(),
        'Scanpak_event_id' => $serverErrorException->getEventId()
    ]);
} catch (ScanpakAuthorizationException $authorizationException) {
    print 'Your app does not have the need token scope';
} catch (ScanpakAuthenticationException $authenticationException) {
    print "You are not authenticated. Please check your token.";
}

dump($response);