<?php
session_start();

$client_id = "1478658732853428325";
$redirect_uri = "https://fruitinteractive-fruits-web.mgw6ep.easypanel.host/careers/callback.php";
$scope = "identify";

$discord_login_url = "https://discord.com/api/oauth2/authorize?client_id={$client_id}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}";
header("Location: $discord_login_url");
exit;
?>
