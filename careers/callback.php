<?php
session_start();

$client_id = "1485848489190494228";
$client_secret = "x6qY77Z6dpYLEUx_9miPHT42CgFhkHI0";
$redirect_uri = "https://fruitinteractive-fruits-web.mgw6ep.easypanel.host/careers/callback.php";

if(isset($_GET['code'])){
    $code = $_GET['code'];

    $token_url = "https://discord.com/api/oauth2/token";
    $data = [
        'client_id'=>$client_id,
        'client_secret'=>$client_secret,
        'grant_type'=>'authorization_code',
        'code'=>$code,
        'redirect_uri'=>$redirect_uri,
        'scope'=>'identify'
    ];

    $ch = curl_init($token_url);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($data));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-Type: application/x-www-form-urlencoded']);
    $response = curl_exec($ch);
    curl_close($ch);

    $token_data = json_decode($response,true);
    $access_token = $token_data['access_token'];

    $ch = curl_init("https://discord.com/api/users/@me");
    curl_setopt($ch,CURLOPT_HTTPHEADER,["Authorization: Bearer $access_token"]);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $user = json_decode(curl_exec($ch),true);
    curl_close($ch);

    $_SESSION['discord_user'] = [
        'id'=>$user['id'],
        'username'=>$user['username'] . '#' . $user['discriminator'],
        'avatar_url'=>"https://cdn.discordapp.com/avatars/{$user['id']}/{$user['avatar']}.png"
    ];

    header("Location: .");
    exit;
}
?>
