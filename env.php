<?php
// Set ke false jika sudah siap live (Production)
define('IS_PRODUCTION', false); 

// Ganti dengan key dari Dashboard Midtrans Anda
define('SB_CLIENT_KEY', 'SB-Mid-client-j_yYcFc7dCF9r1KY');
define('SB_SERVER_KEY', 'SB-Mid-server-Y2SCfg6GMAIUCP6UOpJJKCsV');
define('PROD_CLIENT_KEY', 'Mid-client-xxxxxxxxxxxxxx');
define('PROD_SERVER_KEY', 'Mid-server-xxxxxxxxxxxxxx');

function getServerKey() {
    return IS_PRODUCTION ? PROD_SERVER_KEY : SB_SERVER_KEY;
}
