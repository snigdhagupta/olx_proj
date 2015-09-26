<?php
session_start();

include __DIR__."/../classes/Users.php";
// added in v4.0.0
require_once 'autoload.php';
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;
// init app with app id and secret
FacebookSession::setDefaultApplication( '1645064372373678','b1b171d25c1c9831b4bbc7ccafb3994a' );
// login helper with redirect_uri
    //$helper = new FacebookRedirectLoginHelper('http://demos.krizna.com/lib_fb/fbconfig.php' );
$helper = new FacebookRedirectLoginHelper('http://www.artharta.com/olx_proj/lib_fb/fbconfig.php' );
try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
} catch( Exception $ex ) {
  // When validation fails or other local issues
}
// see if we have a session
if ( isset( $session ) ) {
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject();
     	$fbid = $graphObject->getProperty('id');              // To Get Facebook ID
 	    $fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
	    $femail = $graphObject->getProperty('email');    // To Get Facebook email ID
            
            
            
            
            /*--------ADDING USER INFO TO DB - START ---------*/
            Users::add_or_update_user($fbid, $fbfullname);
            /*--------ADDING USER INFO TO DB - END ---------*/
            
            
            
            
            
	/* ---- Session Variables -----*/
	    $_SESSION['FBID'] = $fbid;           
        $_SESSION['FULLNAME'] = $fbfullname;
	    $_SESSION['EMAIL'] =  $femail;
    /* ---- header location after session ----*/
  //header("Location: index.php");
            
            
            
/*-----attempting to get the user likes information ---- start -------*/            
$request = new FacebookRequest(
  $session,
  'GET',
  '/'.$fbid.'/likes'
);


var_dump($fbid);
$response = $request->execute();
$graphObject = $response->getGraphObject();

echo "likes: <br/> <pre>";
print_r($response);


echo "</pre>";
/*-----attempting to get the user likes information ---- start -------*/            

} else {
    $loginUrl = $helper->getLoginUrl( array( 'user_likes' ) );
  //$loginUrl = $helper->getLoginUrl();
 header("Location: ".$loginUrl);
}
?>