<?php

namespace gymadarasz\xparser;

class XNodeList implements \Iterator {

	private $__elements = [];


	public function __construct($elements = [], XNode &$source = null) {
		foreach($elements as &$element) {
			if(is_string($element)) {
				$element = new XNode($element, $source);
			}
			if(!($element instanceof XNode)) {
				throw new XParserException('Invalid element type: ' . gettype($element));
			}
		}
		$this->__elements = $elements;
	}

	public function addElement(XNode &$elem) {
		$this->__elements[] = $elem;
	}
	
	public function addElementsArray($elems = [], &$source) {
		foreach($elems as $elem) {
			$this->addElement(new XNode($elem, $source));
		}
	}

	public function getElements() {
		return $this->__elements;
	}
	
	public function getElement($index = 0) {
		return $this->__elements[$index];
	}

	public function __call($name, $arguments) {
		$ret = [];
		foreach($this->__elements as $elem) {
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

	public function __invoke() {
		foreach($this->getElements() as $element) {
			$this->__elements(func_get_args());
		}
	}
	
	// todo: iterable ------
	
    public function rewind() {
        reset($this->__elements);
    }
  
    public function current() {
        $var = current($this->__elements);
        return $var;
    }
  
    public function key() {
        $var = key($this->__elements);
        return $var;
    }
  
    public function next() {
        $var = next($this->__elements);
        return $var;
    }
  
    public function valid() {
        $key = key($this->__elements);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }

}