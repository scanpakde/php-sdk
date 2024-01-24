<?php

include_once 'vendor/autoload.php';

use Scanpak\Scanpak;
use Scanpak\Shipping\Control;
use Scanpak\Exceptions\ScanpakValidationException;
use Scanpak\Exceptions\ScanpakServerErrorException;
use Scanpak\Exceptions\Auth\ScanpakAuthorizationException;
use Scanpak\Exceptions\Auth\ScanpakAuthenticationException;
use Scanpak\Shipping\EnableShipment;


$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiYTQyZjE1YTcwMWEwN2Y1ODFiY2MxMzY2YjMxNzhlOGM2YWE2NTRkNzg4NmE0YmJjYjY5Y2RlYmJmNTNiYjA2ZmFlZTRhYmZlMWE3YWMzNTMiLCJpYXQiOiIxNjEzNDgzNTIyLjEzMjA2OCIsIm5iZiI6IjE2MTM0ODM1MjIuMTMyMDcxIiwiZXhwIjoiMTY0NTAxOTUyMi4xMjA3NjgiLCJzdWIiOiI0Iiwic2NvcGVzIjpbXX0.mkinyo7P7OboVTi2_ERjEV-eBW-cuog1DIpwRyzEX1tyTyn8xPs1eCNa28KBKs1z1jcr2tJMXXUEqy4QNZftDis-OSQozBBtSjMsI-fOHICxoiVQo8kT_olNCuC1s1k-K_F1SFsEF13_0t5o_8MlXxllw8pj1xMx5Bddy-K9dg08qSRImga-AQsL9zpEVvwIR_26LOQk1mk1FDAhSue6taCLFpj-k02k_Jhx81H2ey1c2AoqCl9tcVgQLbAR3crpcfV4kI21wWheqcyWOIy6Zih6kN2HEF2qeh2SLLJihVrZX2nLreHJuJ2LL40thXYCj2qRdmeq5bvNFYOTuOzb2N2hA_f8eyA3OR_vlONfkPrlWu85gco0tx60N7pRGQTdOU3jz5uD37hk4TaX408Y5hkDSzJGECG9heZie6DmHrkeTMNLbSli0-IWWTruHY3zM32uYnWiufdMTl0NKAEE26jkVjW8CfmriAuueMJnbCAwC9L3UslIcKorYpHnOAjf5BkIt9vDSOW-vyqpKz02Un-4Aovcm4pXtkHA3J9YGRUjQq5jIBt4kpz5vItFZt0p2cPcJG2stFRZ-Vzz5rXIPv-8zJkTyDlwA3KMy1ykPQubSGPF7bTynQ1S29lkw1NzvdWwqPjFe-5eLIvu-aJHRpe1_QsF9SGJ13BUUNcKUcM";

$testMode = true;
$url = "http://Scanpak-core.test";

$Scanpak = new Scanpak($token, $testMode, $url);

$order = new EnableShipment($Scanpak);
$reference = "pancho2-R";
$order->setExternalReference($reference);
// All other fields are not mandatory
$order->setReceiverFirstName('TestFirstName');
$order->setReceiverLastName('TestLastName');
$order->setReceiverEmail('email@gmail.com');
$order->setReceiverMobile('+4512345678');
$order->setReceiverAddress('Address 123');
$order->setReceiverCity('city');
$order->setReceiverZipCode('8540');

try {
    $response = $order->getEnableShipment();
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