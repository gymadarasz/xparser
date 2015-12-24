<?php

/*
 * The MIT License
 *
 * Copyright 2015 Gyula Madarasz <gyula.madarasz at gmail.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace gymadarasz\xparser\tests;

use gymadarasz\minitest\MiniTestAbstract;
use gymadarasz\xparser\XNode;
use Ubench;
use voku\helper\HtmlDomParser;

/**
 * Description of XParserBenchmarks
 *
 * @author Gyula Madarasz <gyula.madarasz at gmail.com>
 */
class XParserBenchmarks extends MiniTestAbstract {
	
	public function run() {
		$this->start('compare');
	}
	
	protected function compare() {
		$bench = new Ubench;
		
		$google = file_get_contents('http://google.com');
		
		
		$this->log('', true);
		
		$this->log('Measuring Simple HTML DOM Parser...');
		
		$bench->run(function($google){
			$html = HtmlDomParser::str_get_html($google);
			$html->find('title', 0)->innertext('New Title');
			foreach($html->find('div') as $div) {
				$div->innertext = 'change';
			}
		}, $google);
		
		$this->logBench($bench);
		
		$this->log('', true);
		
		
		$this->log('Measuring XParser...');
		
		$bench->run(function($google){
			$html = new XNode($google);
			$html->find('title')->inner('New Title');
			$html->find('div')->inner('change');
		}, $google);
		
		$this->logBench($bench);
		
		$this->log('', true);
		$this->log('', true);
		
	}
	
	private function logBench(Ubench $banch) {
		$this->log("Time:\t" . $banch->getTime());
		$this->log("Memory usage:\t" . $banch->getMemoryUsage());
		$this->log("Memory peak:\t" . $banch->getMemoryPeak());
	}
	
}
