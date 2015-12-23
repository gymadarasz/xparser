<?php

namespace gymadarasz\xparser;

class XParserException extends \Exception {
	
	public function __construct($message = null, $code = null, $previous = null) {
		// todo : report link..
		$message = "Hoops, an exception occurred, please report it," . ($message ? ' message: ' . $message : '');
		parent::__construct($message, $code, $previous);
	}
	
}