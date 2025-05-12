<?php
include("RtcTokenBuilder.php");

// Need to set environment variable AGORA_APP_ID
$appId = "a86c9bb907644baea3271616b3aa1f16";
// Need to set environment variable AGORA_APP_CERTIFICATE
$appCertificate = "3c7c1262ce6647a78f7e8906ba580822";

$channelName = "agora4820";
$uid = 8965741;
$uidStr = "8965741";
$role = RtcTokenBuilder::RoleAttendee;
$expireTimeInSeconds = 36000000;
$currentTimestamp = (new DateTime("now", new DateTimeZone('UTC')))->getTimestamp();
$privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

echo "App Id: " . $appId . PHP_EOL;
echo "App Certificate: " . $appCertificate . PHP_EOL;
if ($appId == "" || $appCertificate == "") {
    echo "Need to set environment variable AGORA_APP_ID and AGORA_APP_CERTIFICATE" . PHP_EOL;
    exit;
}

$token = RtcTokenBuilder::buildTokenWithUid($appId, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTs);
echo 'Token with int uid: ' . $token . PHP_EOL;

$token = RtcTokenBuilder::buildTokenWithUserAccount($appId, $appCertificate, $channelName, $uidStr, $role, $privilegeExpiredTs);
echo 'Token with user account: ' . $token . PHP_EOL;
