<?php

namespace gymadarasz\xparser;

class XNodeList {

	private $elements = [];


	public function __construct($elements = [], XNode &$source = null) {
		foreach($elements as &$element) {
			if(is_string($element)) {
				$element = new XNode($element, $source);
			}
			if(!($element instanceof XNode)) {
				throw new XParserException('Invalid element type: ' . gettype($element));
			}
		}
		$this->elements = $elements;
	}

	public function addElement(XNode $elem) {
		$this->elements[] = $elem;
	}
	
	public function addElementsArray($elems = [], &$source) {
		foreach($elems as $elem) {
			$this->addElement(new XNode($elem, $source));
		}
	}

	public function getElements() {
		return $this->elements;
	}

	public function getElement($index = 0) {
		return $this->elements[$index];
	}

	public function __call($name, $arguments) {
		$ret = [];
		foreach($this->elements as $elem) {
			if(method_exists($elem, $name)) {
				$results = call_user_func_array([$elem, $name], $arguments);
				if(is_array($results)) {
					$ret = array_merge($ret, $results);
				}
				else if($results instanceof XNodeList) {
					$ret = array_merge($ret, $results->getElements());
				}
				else if($results instanceof XNode) {
					$ret[] = $results;
				}
				else if(is_string($results)) {
					$ret = $results;
				}
				else if(is_null($results)) {
					$ret = null;
				}
				else {
					throw new XParserException('Invalid results of query: ' . gettype($results) . ' => ' . $results);
				}
			}
		}
		return $ret;
	}
	
	public function __set($name, $value) {
		foreach($this->getElements() as $element) {
			$element->$name = $value;
		}
	}
	
	public function __get($name) {
		$value = $this->getElement()->$name;
		return $value;
	}

	public function __invoke(...$args) {
		foreach($this->getElements() as $element) {
			$this->elements($args);
		}
	}

}