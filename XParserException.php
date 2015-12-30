<?php

namespace gymadarasz\xparser;

class XParserException extends \Exception {
	
	private static $developMode = true;
	
	public function __construct($message = null, $code = null, $previous = null) {
		$help = '';
		if(self::$developMode) {
			$title = 'XParserException report: ' . $message;
			$body = 'Trace info:' . PHP_EOL . '```' . PHP_EOL . $this->getTraceAsString() . PHP_EOL . '```' . PHP_EOL  . PHP_EOL;
					// TODO add selector and __xhtml if called from an xnode or __elements if called from XnodeList to body.
			$urlNew = 'https://github.com/gymadarasz/xparser/issues/new?title=' . urlencode($title) . '&body=' . urlencode($body);
			$keyword = $message;
			$urlSearch = 'https://github.com/gymadarasz/xparser/issues?q=' . urlencode($keyword);
			$help = ', <a href="' . $urlSearch . '">search a resolution on github</a> or report it in a <a href="' . $urlNew . '" target="_blank">new issue</a>';
		}
		$message = "Hoops, an exception occurred" . ($message ? ', message is "' . $message . '"' : '') . $help . '.';
		parent::__construct($message, $code, $previous);
	}
	
}