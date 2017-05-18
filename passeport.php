<?php 

require_once 'common.php';

$FB_CALLBACK_URL = "http://passeport-transition.fr/passeport.php?fb_callback";

# Create user if not existing yet
if ($user == null) {
	$user = new User();
	$user = saveUser($user);
	setCookies($user);
} else {
	if (!$user->authenticated) {
		echo "Non authentifié";
		exit;
	}
}

# Update data with responses
foreach ($data->sections as $section) {
	foreach ($section->questions as $question) {
		$key = $section->id . '_' . $question->id ;
		if (array_key_exists($key, $_POST)) {
			$question->response = $_POST[$key];
		}
	}
}
saveResponses($user->id, $data);

# Update User
$save = false;
if (array_key_exists('firstname', $_POST)) {
	$user->firstname = $_POST['firstname'];
	$save = true;
}
if (array_key_exists('name', $_POST)) {
	$user->name = $_POST['name'];
	$save = true;
}
if (array_key_exists('email', $_POST)) {
	$user->email = $_POST['email'];
	$save = true;
}

if ($save) {
	saveUser($user);
}


// Upload image
if (array_key_exists('photo', $_FILES) && $_FILES['photo']['size'] > 0) {
	$name = $_FILES['photo']['name'];
	$ext = pathinfo($name)['extension'];
	$target = PROFILE_DIR . $user->id . '.' . $ext;

	error_log('Files' . print_r($_FILES, true));
	deleteProfilePictures($user->id);
	move_uploaded_file($_FILES['photo']['tmp_name'], $target);
}

$fb = newFacebookApp();

# Come from facebook ? => Update info and image
if (array_key_exists('fb_callback', $_GET)) {

	$token = getToken($fb);

	# Get profile information
	$profile = $fb->get('/me?fields=id,first_name,last_name,email', $token)->getGraphUser();
	$user_id = $profile['id'];


	# Update user
	$user->email = $profile['email'];
	$user->firstname = $profile['first_name'];
	$user->name = $profile['last_name'];
	saveUser($user);

	deleteProfilePictures($user->id);

	# Copy profile picture
	$res = $fb->get("/$user_id/picture?redirect=false&width=500&height=500", $token)->getGraphNode();
	$profile_pic_url = $res['url'];
	$ext = pathinfo($profile_pic_url)['extension'];
	$target = PROFILE_DIR . $user->id . '.' . $ext;
	copy($profile_pic_url, $target);
}


updateImage($user, $data);


$fb_url = getLoginURL($fb, $FB_CALLBACK_URL, ['email', 'public_profile']);

// Render a template
echo $templates->render('passeport', [
	'data' => $data, 
	'user' => $user,
	'fb_url' => $fb_url]);


?>