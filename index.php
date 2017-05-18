<?php

require_once 'common.php';

// Render a template
echo $templates->render('questionnaire', ['data' => $data, 'user' => $user]);

?>