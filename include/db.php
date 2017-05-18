<?php


	require_once "Model.php";
	$CONNECTION = null;

	function get_connection() {
		global $CONNECTION;
		if ($CONNECTION == null) {
			$CONNECTION = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_BASE);
		}
		return $CONNECTION;
	}

	function getUser($id) {
		$conn = get_connection();
		$id = $conn->real_escape_string($id);
		$result = $conn->query("SELECT * FROM users WHERE id = '$id'");
		while($row = $result->fetch_assoc()) {
			$res = new User();
			$res->id = $row['id'];
			$res->firstname = $row['firstname'];
			$res->name = $row['name'];
			$res->secret = $row['secret'];
			$res->email = $row['email'];
			$res->last_update = $row['last_update'];
			return $res;
		}
	}

	function saveUser(&$user) {
		$conn = get_connection();
		$user->last_update = time();
		if ($user->secret == null) {
			$bytes = openssl_random_pseudo_bytes(16);
			$user->secret = bin2hex($bytes);
		}
		if ($user->id == null) {
			$user->id = uniqid("");
			$stmt = $conn->prepare("INSERT INTO users (firstname, name, secret, email, last_update, id) VALUES (?, ?, ?, ?, ?, ?)");
		} else {
			$stmt = $conn->prepare("UPDATE users SET firstname=?, name=?, secret=?, email=?, last_update=? WHERE id=?");
		}
		if ($stmt !== true){
    		error_log("ERROR: Could not able to execute query: " . $conn->error);
    	}
		$stmt->bind_param('ssssss', 
			$user->firstname, $user->name, 
			$user->secret, $user->email, $user->last_update, 
			$user->id);
		$stmt->execute();
		return $user;
	}

	function saveResponses($user_id, &$data) {
		$conn = get_connection();
		$query = "REPLACE INTO choices (user_id, section, question, response) VALUES ('0', 'foo', 'bar', 'bar')"; 
		foreach ($data->sections as $section) {
			foreach ($section->questions as $question) {
				if ($question->response) {
					$response = $conn->real_escape_string($question->response);
					$query .= ",('$user_id',  '$section->id', '$question->id', '$response')";	
				}
			}
		}
		$query .= ';';
		$conn->query($query);
		if($conn->query($query) !== true){
    		error_log("ERROR: Could not able to execute $query" . $conn->error);
    	}
	}

	function fetchResponses($user_id, &$data) {
		$conn = get_connection();
		$result = $conn->query("SELECT * FROM choices WHERE user_id = '$user_id'");
		$responses = array();
		while($row = $result->fetch_assoc()) {
			$responses[$row['section'] . '_' . $row['question']] = $row['response'];
		}
		foreach ($data->sections as $section) {
			foreach ($section->questions as $question) {
				$key = $section->id . '_' . $question->id ;
				if (array_key_exists($key, $responses)) {
					$question->response = $responses[$key];
				}
			}
		}
	}

?>