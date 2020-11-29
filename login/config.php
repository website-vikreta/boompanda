
<?php

    //Include Google Client Library for PHP autoload file
    require_once 'vendor/autoload.php';

    //Make object of Google API Client for call Google API
    $google_client = new Google_Client();

    //Set the OAuth 2.0 Client ID
    // $google_client->setClientId('984822172283-bhea0ssu44mm7hrnblve16s2kaj9i9jn.apps.googleusercontent.com');
    // for boompanda
    $google_client->setClientId('733275217399-9d6q867l32pr7f89efva7v0ieo131qd5.apps.googleusercontent.com');

    //Set the OAuth 2.0 Client Secret key
    // $google_client->setClientSecret('_vqxhLfeicANLz_QO7c0IxPf');
    // for boompanda
    $google_client->setClientSecret('BnreZmOwASPYdSi_aIUs0tzT');

    //Set the OAuth 2.0 Redirect URI
    $google_client->setRedirectUri('http://localhost/boompanda/login/login.php');
    // redirect path
    // $google_client->setRedirectUri('https://www.boompanda.in/login/login.php');

    // to get the email and profile 
    $google_client->addScope('email');
    $google_client->addScope('profile');

?>
