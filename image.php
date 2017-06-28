<?php


require __DIR__ . '/vendor/autoload.php';
require_once  'include/Model.php';
require_once "include/Spyc.php";

$FONT = 'font-awesome/fonts/fontawesome-webfont.ttf';
$ICONS = 'font-awesome/icons.yml';
$ICON_SIZE = 40;
$ICONS_LEFT = 450;
$ICONS_TOP = 180;
$ICONS_HEIGHT = 100;
$NB_ICONS_PER_COL=4;
$COL_WIDTH = 370;
$LABEL_SIZE = 30;
$NAME_SIZE = 50;

$PROFILE_LEFT=40;
$PROFILE_TOP=50;
$PROFILE_WIDTH=350;
$PROFILE_HEIGHT=400;
$ICON_CODES = null;


# Load font-awesome icon definition
function loadIconCodes() {
	global $ICON_CODES, $ICONS;
	$icons = spyc_load_file($ICONS);
	$ICON_CODES = array();
	foreach ($icons['icons'] as $icon) {
		$id = $icon['id'];
		$unicode = $icon['unicode'];
		$ICON_CODES[$id]=$unicode;
	}
}

function withBrightness($color, $ratio) {
	list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
	$r = max(0, min(255, floor($r + $ratio)));	
	$g = max(0, min(255, floor($g + $ratio)));	
	$b = max(0, min(255, floor($b + $ratio)));
	$res =  sprintf("#%02x%02x%02x", $r, $g, $b);
	return $res;
}

function section_sum($section) {
	$res = 0;
	foreach ($section->questions as $question) {
		if ($question->extra_option_label) {
			$res += 3;
		} else {
			$res += 2;
		}
	}
	return $res;
}

function section_score($section, $options) {
	$score = 0;
	foreach ($section->questions as $question) {
		if ($question->response) {
			$score += $options[$question->response]->score;
		}
	}
	return $score;
}


function draw_section($img, $x, $y, $label, $icon, $color, $score, $sum) {
	global $icon_codes, $FONT, $ICON_SIZE, $LABEL_SIZE, $COL_WIDTH, $ICONS_HEIGHT, $ICON_CODES;

	# Draw rectangle
	$box_draw = new ImagickDraw();
	$box_draw->setFillColor($color);
	$box_draw->setFillOpacity(0.8);
	$box_draw->roundRectangle(
			$x - $ICON_SIZE / 2, 
			$y - $ICON_SIZE - 5, 
			$x + $COL_WIDTH - $ICON_SIZE,
			$y + $ICON_SIZE + 5, 
			10, 10);
	$img->drawImage($box_draw);

	# Draw jauge
	$jauge_draw = new ImagickDraw();
	$jauge_draw->setFillColor(withBrightness($color, 80));
	$jauge_draw->roundRectangle(
			$x - $ICON_SIZE / 2 + 10, 
			$y + 10, 
			$x + $COL_WIDTH - $ICON_SIZE - 10,
			$y + $ICON_SIZE - 5, 
			5, 5);
	$img->drawImage($jauge_draw);

	$ratio = ($sum == 0) ? 0 : $score / $sum;

	# Draw dark jauge
	$jauge_draw = new ImagickDraw();
	$jauge_draw->setFillColor(withBrightness($color, -50));
	$left = $x - $ICON_SIZE / 2 + 12;
	$jauge_draw->roundRectangle(
			$left, 
			$y + 12, 
			$left + ($COL_WIDTH - $ICON_SIZE - 4) * $ratio,
			$y + $ICON_SIZE - 7, 
			5, 5);
	$img->drawImage($jauge_draw);

	# Draw icon 
	$unicode = $ICON_CODES[$icon]; 
	$text = html_entity_decode("&#x$unicode;", ENT_COMPAT, 'UTF-8');
	$icon_draw = new ImagickDraw();
	$icon_draw->setFont($FONT);
	$icon_draw->setFontSize($ICON_SIZE);
	$img->annotateImage($icon_draw, $x, $y, 0, $text);

	# Draw label
	$label_draw = new ImagickDraw();
	$label_draw->setFontSize($LABEL_SIZE);
	$label_draw->setFontWeight(600);
	$img->annotateImage($label_draw, $x + $ICON_SIZE * 1.5 , $y, 0, $label);

	# Draw score
	$score_draw = new ImagickDraw();
	$score_draw->setFontSize($LABEL_SIZE);
	$score_draw->setTextAlignment(Imagick::ALIGN_RIGHT);
	$img->annotateImage($score_draw, $x + $COL_WIDTH - $ICON_SIZE - 5, $y, 0, "$score/$sum");

}

function updateImage($user, $data) {
	global 
		$ICONS_LEFT, $ICON_SIZE, $NB_ICONS_PER_COL, 
		$ICONS_TOP, $ICONS_HEIGHT, $COL_WIDTH, $PROFILE_WIDTH, $PROFILE_HEIGHT, $PROFILE_LEFT, 
		$PROFILE_TOP, $NAME_SIZE;

	loadIconCodes();

	# Load base image
	$img= new Imagick();
	$file = fopen('img/passeport.png', 'rb');
	$img->readImageFile($file);

	# Draw all sections
	$i = 0;
	$total_score = 0;
	$total_sum = 0;
	foreach ($data->sections as $section) {
		$x = floor($i / $NB_ICONS_PER_COL);
		$y = $i % $NB_ICONS_PER_COL;
		$score = section_score($section, $data->options);
		$sum = section_sum($section, $data->options);
		$total_score += $score;
		$total_sum += $sum;
		draw_section(
			$img, 
			$ICONS_LEFT+$x*$COL_WIDTH, 
			$ICONS_TOP+$y * $ICONS_HEIGHT, 
			$section->name, 
			$section->icon_symbol,
			$section->icon_color,
			$score, $sum);
		$i += 1;
	}

	# Draw total 
	draw_section(
			$img, 
			$ICONS_LEFT+0.5*$COL_WIDTH, 
			$ICONS_TOP+4* $ICONS_HEIGHT, 
			"Total", 
			"calculator",
			"#B5955C",
			$total_score, $total_sum);

	$prof_pictures = glob(PROFILE_DIR . "$user->id.*");
	if (sizeof($prof_pictures) == 1) {
		$prof_picture = $prof_pictures[0];
	} else {
		$prof_picture = PROFILE_DIR . 'default.jpg';
	} 

	# Copy profile picture
	$prof= new Imagick();
	$file = fopen($prof_picture, 'rb');
	$prof->readImageFile($file);
	$width = $prof->getImageWidth();
	$height = $prof->getImageHeight();
	$width_ratio = $width / $PROFILE_WIDTH;
	$height_ratio = $height / $PROFILE_HEIGHT;
	$ratio = max($width_ratio, $height_ratio);
	$new_width = floor($width / $ratio);
	$new_heigth = floor($height  / $ratio);
	$prof->resizeImage($new_width, $new_heigth, imagick::FILTER_UNDEFINED, 1);
	$img->compositeImage($prof, imagick::COMPOSITE_ATOP, $PROFILE_LEFT, $PROFILE_TOP);

	# Draw name
	$name_draw = new ImagickDraw();
	$name_draw->setFontSize($NAME_SIZE);
	$name_draw->setFontWeight(600);
	$name_draw->setTextInterlineSpacing(20);
	$name_draw->setTextAlignment(Imagick::ALIGN_CENTER);

	$firstname = $user->firstname;
	if (empty($firstname)) {
		$firstname = "Pr\xc3\xa9nom";
	}
	$name = $user->name;
	if (empty($name)) {
		$name = "Nom";
	}


	$img->annotateImage(
		$name_draw, 
		$PROFILE_LEFT + floor($PROFILE_WIDTH / 2) , 
		$PROFILE_TOP + $PROFILE_HEIGHT + 20, 
		0,         
		$firstname . "\n" . $name);

	# Save image
	$img->setImageFormat('png');
	$id = $user->id;
	$img->writeImage("img/out/$id.png");
}

?>