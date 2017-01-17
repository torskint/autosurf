<?php

(preg_match('#^(http|https)://[\w-]+[\w.-]+\.[a-zA-Z]{2,6}#i', $domain)) ? TRUE : FALSE;
(preg_match('/^[a-z0-9_.-]+@[0-9a-z-_]+\.[0-9a-z.]+$/i', $mail)) ? TRUE : FALSE;




    $goto = $_SERVER['QUERY_STRING'] ;
    $HideRefSites = array("http://refhide.com/?" , "http://www.lolinez.com/?" , "http://hiderefer.me/?" , "http://www.nullrefer.com/?");
    $randomized = array_rand($HideRefSites );
    $RedirectTo = $HideRefSites[$randomized].$goto;
    header("Location: $RedirectTo");
    exit;
?>
<?php
session_start();
/**
  Setp 1. Get the query string variable and set it in a session, then remove it from the URL.
*/
if(isset($_GET ['to']) && !isset($_SESSION ['to'])) {
    $_SESSION['to'] = urldecode($_GET['to']) ;
    header('Location: http://yoursite.com/out.php');// Must be THIS script
    exit();
}
/**
  Step 2. The page has now been reloaded, replacing the original referer with  what ever this script is called.
  Make sure the session variable is set and the query string has been removed, then redirect to the intended location.
*/
if(!isset($_GET['to']) && isset($_SESSION['to'])) {
$output = '<!DOCTYPE html>
<html>
<head>
<meta name="robots" content="none">
<title>Referral Mask</title>
</head>
<body>
<h3>Redirecting...</h3>
<script>window.location.href="' . $_SESSION [ 'to' ] . '"</script>
<a href="' . $_SESSION [ 'to' ] . '">Here is your link</a>
</body>
</html>' . "\n ";
unset($_SESSION [ 'to' ]) ;
echo $output;
exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="robots" content="none">
<title>Referral Mask</title>
</head>
<body>
<h1>Referral Mask</h1>
<p>This resource is used to change the HTTP Referral header of a link clicked from within our secure pages.</p>
</body>
</html>
}


<?php

function urlExists($url) {
    $handle = curl_init($url);
    curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($handle);
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    if($httpCode >= 200 && $httpCode <= 400){
        return true;
    } else {
        return false;
    }
    curl_close($handle);
}
echo var_dum(url($_GET["u"]));

stream_context_set_default(array(
    'http' => array(
        'method' => 'HEAD'
    )
));

$headers = @get_headers($url);
$status = substr($headers[ 0], 9, 3);
if($status >= 200 && $status < 400) {
    print('found!' );
} else {
    print('NOT found!' );
}
    
?>