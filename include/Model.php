<?php

function getChild($domElement, $name) {
	foreach ($domElement->childNodes as $child) {
        if ( $child->nodeName == $name ) {
           return $child;
        }
     }
     return Null;
}

function getValue($domElement, $name) {
	return getChild($domElement, $name)->nodeValue;
}

function getChildren($domElement, $listName, $itemName) {
	$list = getChild($domElement, $listName);
	if ($list == Null) {
		throw new Exception("list not found : $listName"); 
	}
	$res = array();
	foreach ($list->childNodes as $child) {
		if ($child->nodeName == $itemName) {
			$res[] = $child;
		}
	}
	return $res;
}

function addChild($element, $name) {
	$res = $element->ownerDocument->createElement($name);
	$element->appendChild($res);
	return $res;
}
function addValue($element, $name, $value) {
	$res = $element->ownerDocument->createElement($name, $value);
	$element->appendChild($res);
	return $res;
}

function getXmlWithin($xml, $tag_name) {

	$res = $xml->ownerDocument->saveXml($xml);
	$res = str_replace("<$tag_name>", "", $res);
	$res = str_replace("</$tag_name>", "", $res);
	return $res;
}

function addNode($node, $child) {
	$node->appendChild($node->ownerDocument->importNode($child, true));
}


 class Questionnaire {
 	public $options = array();
 	public $sections = array();
 	function __construct($xml) {
 		foreach (getChildren($xml, "sections", "section") as $section) {
 			$this->sections[] = new Section($section);
 		}
 		foreach (getChildren($xml, "options", "option") as $option) {
 			$option = new Option($option);
 			$this->options[$option->id] = $option;
 		}
 	}
 	function toXML() {
 		$doc = new DomDocument("1.0", "UTF-8");
 		$root = $doc->createElement("questionnaire");
		$doc->appendChild($root);
 		$options = addChild($root, 'options');
 		foreach ($this->options as $option) {
 			$options->appendChild($option->toXML($doc));
 		}
 		$sections = addChild($root, 'sections');
 		foreach ($this->sections as $section) {
 			$options->appendChild($section->toXML($doc));
 		}
 		return $doc;
 	}
 }

 class Option {
	public $id = "";
	public $score = 0;
	public $color = "";
	public $label = "";
  	function __construct($xml) {
		$this->id = getValue($xml, 'id');
		$this->score = (int) getValue($xml, 'score');
		$this->color = getValue($xml, 'color');
		$this->label = getValue($xml, 'label');
  	}
  	function toXML($doc) {
 		$res = $doc->createElement("option");
 		addValue($res, "id", $this->id);
 		addValue($res, "score", $this->score);
 		addValue($res, "color", $this->color);
 		addValue($res, "label", $this->label);
 		return $res;
 	}
 }

 class User {
	public $id = null;
	public $firstname = "";
	public $name = "";
	public $secret = "";
	public $email = "";
	public $authenticated = false;
	public $last_update=0;
 }

class Section {

	public $id = "";
	public $name = 0;
	public $description = "";
	public $icon_symbol = "";
	public $icon_color = "";
	public $questions = array();
	
	function __construct($xml) {
		$this->id = getValue($xml, 'id');
		$this->name = getValue($xml, 'name');
		$this->description = getChild($xml, 'description');
		$icon = getChild($xml, 'icon');
		$this->icon_color = getValue($icon, 'color');
		$this->icon_symbol = getValue($icon,  'symbol');
		foreach (getChildren($xml, "questions", "question") as $question) {
			$this->questions[] = new Question($question);
		}
	}

	function getDescription() {
		return getXmlWithin($this->description, "description");
	}
	
	function toXML($doc) {
 		$res = $doc->createElement("section");
 		addValue($res, "id", $this->id);
 		addValue($res, "name", $this->name);
 		addNode($res, $this->description);
 		$icon = addChild($res, "icon");
 		addValue($icon, "color", $this->icon_color);
 		addValue($icon, "symbol", $this->icon_symbol);
 		$questions = addChild($res, "questions");
 		foreach ($this->questions as $question) {
 			$questions->appendChild($question->toXML($doc));
 		}
 		return $res;
 	}
}

class Question {
	public $id = "";
	public $name = 0;
	public $description = "";
	public $extra_option_label = Null;
	public $extra_option_description = "";
	public $response = null;

	function __construct($xml) {
		$this->id = getValue($xml, "id");
		$this->name = getValue($xml, "name");
		$this->description = getChild($xml, "description");
		$extra_option = getChild($xml, "extra_option");
		if ($extra_option != Null) {
			$this->extra_option_label = getValue($extra_option, "label");
			$this->extra_option_description = getChild($extra_option, "description");
		}
	}

	function getDescription() {
		return getXmlWithin($this->description, "description");
	}

	function getExtraOptionDescription() {
		return getXmlWithin($this->extra_option_description, "description");
	}


	function toXML($doc) {
 		$res = $doc->createElement("question");
 		addValue($res, "id", $this->id);
 		addValue($res, "name", $this->name);
 		addNode($res, $this->description);
 		if ($this->extra_option_label != Null)  {
 			$extra_option = addChild($res, "extra_option");
 			addValue($extra_option, "label", $this->extra_option_label);
 			addNode($extra_option, $this->extra_option_description);
 		}
 		return $res;
 	}
}


?>