<?php

include_once 'vendor/autoload.php';

use Scanpak\Scanpak;
use Scanpak\Exceptions\ScanpakValidationException;
use Scanpak\Exceptions\ScanpakServerErrorException;
use Scanpak\Exceptions\Auth\ScanpakAuthorizationException;
use Scanpak\Exceptions\Auth\ScanpakAuthenticationException;

$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjlhOTY4M2M3OWUyMWRkNTBhZGNhNzg1OTMzZjE0Y2FkN2M0OTc5NzFkYzc4MGRjYzAwYjEwYjk4MWJlNmVkZGVmMGQ5MjBhYjJiNzBiNmRiIn0.eyJhdWQiOiIxIiwianRpIjoiOWE5NjgzYzc5ZTIxZGQ1MGFkY2E3ODU5MzNmMTRjYWQ3YzQ5Nzk3MWRjNzgwZGNjMDBiMTBiOTgxYmU2ZWRkZWYwZDkyMGFiMmI3MGI2ZGIiLCJpYXQiOjE2MDUyNzg4MjksIm5iZiI6MTYwNTI3ODgyOSwiZXhwIjoxNjM2ODE0ODI5LCJzdWIiOiIxIiwic2NvcGVzIjpbImZ1bGwtYWNjZXNzLXRvLWNvbXBhbnktb3JkZXJzIl19.PhVZF1VJ0wleoYHKQJf1NQLHQ9Triaa0Np-iV_Eg3ATjNEsHfoOsWklmzgr0BKyMGX6Fv9uNO8gzCbz7SgYo_cwstJucGbFfeX6w9FhgN-pVnPiXWyOl1mU96YahLa4N4NB-Vztj-zB1MKui-33f4kNoDyOixssadRBs674PouOHrw74lRPw7RgPF6r1T7GY2jZuHJUlpO8r8qv2LlPFsMwHi_YGzXgCPkzfldKML7NNLLwNNvKrb0id55nqkSoQGS73zYFnjIgVULEB9XfKJDmLaEgDSga4jJsP5u4EIAxJYUhh9C1YMa0yxUHp_H1N6l-Qq0yl-J-qGqbZzL5KNqNiGmJOp-iIfmLHVmKRbMV4ps9foTqGFu4oBdj44pehnXUCOLjircMEWY2Fr7I_hrYuALeygBcc0sYKdi55DUpXaW-ViVo18B6YHXYycnCCXpzo6dEneW8eIZzQh9h5ZjeEGHbG9DgVV_XNx5zmd5GHenqej_2dakYlBoxXwl-X_lxrtOXtHqJ9airp7RFfEA33qJO8RA10wsteAI_2J1VLRXtRO4sQGow4Jz_uI-eTfavLAjSmb1E2T5HSFBpxXzS3BpIaMnpM6tMFNQ9Vhl-fIswr7Vo3VAJwa0VzEwddbVlMUyLgUrkC4CPjefYtssTpus9EnS4m-I6LT8TFg0s";

$testMode = true;
$url = "https://test-api.Scanpak.com";
$Scanpak = new Scanpak($token, $testMode, $url);
$externalReference = "351977";
$externalStatus = "new test status";

try {
    $response = $Scanpak->externalStatus($externalStatus, $externalReference);
} catch (TypeError $error) {
    print $error->getMessage();
} catch (ScanpakValidationException $ScanpakValidationException) {
    print_r($ScanpakValidationException->getValidationErrors());
} catch (ScanpakServerErrorException $ScanpakServerErrorException) {
    print_r([
        'message' => $ScanpakServerErrorException->getMessage(),
        'Scanpak_event_id' => $ScanpakServerErrorException->getEventId()
    ]);
} catch (ScanpakAuthorizationException $authorizationException) {
    print "You're app does not have the needed token scope";
} catch (ScanpakAuthenticationException $ScanpakAuthenticationException) {
    print "You are not authenticated. Please check your token";
} catch (\Scanpak\Exceptions\ScanpakEndpointNotFoundException $e) {
    print $e->getMessage();
}