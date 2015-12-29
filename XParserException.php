<?php

namespace gymadarasz\xparser;

class XParserException extends \Exception {
	
	public function __construct($message = null, $code = null, $previous = null) {
		$message = "Hoops, an exception occurred" . ($message ? ', message is "' . $message : '"') . ', if you think it\'s my fault please report it in an <a href="https://github.com/gymadarasz/xparser/issues/new">issue on github</a>.';
		parent::__construct($message, $code, $previous);
	}
	
}