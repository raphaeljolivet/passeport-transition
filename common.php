<?php

require __DIR__ . '/vendor/autoload.php';
require_once 'config.php';
require_once 'include/Model.php';
require_once 'include/db.php';
require_once 'image.php';

if(!session_id()) {
    session_start();
}

define('COOKIE_USERID', 'userid');
define('COOKIE_SECRET', 'secret');

function newFacebookApp() {
        return new Facebook\Facebook([
                'app_id' => FB_APP_ID,
                'app_secret' => FB_APP_SECRET,
                'default_graph_version' => 'v2.8',
        ]);
}


function deleteProfilePictures($id) {
	$prof_pictures = glob(PROFILE_DIR . "$id.*");
	foreach ($prof_pictures as $prof_picture) {
		unlink($prof_picture);
	}
}


function getToken($fb) {
        if (isset($_SESSION['facebook_access_token'])) {
                return $_SESSION['facebook_access_token'];
        } else {

                $helper = $fb->getRedirectLoginHelper();
                try {
                    $accessToken = $helper->getAccessToken();   
                        
                } catch(Facebook\Exceptions\FacebookResponseException $e) {
                        // When Graph returns an error
                        echo 'Graph returned an error: ' . $e->getMessage();
                        return null;

            	} catch(Facebook\Exceptions\FacebookSDKException $e) {
                        // When validation fails or other local issues
                        echo 'Facebook SDK returned an error: ' . $e->getMessage();
                        return null;
                }

                if (!isset($accessToken)) {
                	error_log("Facebook toke not set");
                } else {
                	$_SESSION['facebook_access_token'] = (string) $accessToken;
            	}
                return $accessToken;
        }
}


function getLoginURL($fb, $redirect_url, $permissions) {
	 $helper = $fb->getRedirectLoginHelper();
	 return  $helper->getLoginUrl($redirect_url, $permissions);
}



// Create new Plates instance
$templates = new League\Plates\Engine('templates');

# Load questions
$xml = new DomDocument();
$xml->load("questions.xml");
$data = new Questionnaire($xml->documentElement);

function getFromCookiesOrUrl($key) {
	if (array_key_exists($key, $_GET) ) {
		return $_GET[$key];
	} elseif (array_key_exists($key, $_COOKIE)) {
		return $_COOKIE[$key]; 
	}
}

function setCookies($user) {
	setcookie(COOKIE_USERID, $user->id);		
	setcookie(COOKIE_SECRET, $user->secret);	
}

function getAuthUser() {
	$id = getFromCookiesOrUrl(COOKIE_USERID);
	if ($id) {
		$user = getUser($id);
        if ($user == null) {
            echo "Utilisateur non trouvé : $id";
            exit;
        }
		if ($user->secret == getFromCookiesOrUrl(COOKIE_SECRET)) {
			// Authentication ok, set cookies anyway
			setCookies($user);
            $user->authenticated = true;
		}
	}
    return $user;
}


$user = getAuthUser();

if ($user != null && $user->authenticated) {
	fetchResponses($user->id, $data);
}

?>
