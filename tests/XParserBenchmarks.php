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
		
		$url = 'http://madsoft.hu/cv/';
		$file = 'test.html';
		
		if(!file_exists($file)) {
			$htmlstr = file_get_contents($url);
			file_put_contents($file, $htmlstr);
		}
		$htmlstr = file_get_contents($file);
		
		
		$this->log('', true);
		
		$this->log('Measuring Simple HTML DOM Parser...');		
		$result = $bench->run(function($htmlstr){
			$html = HtmlDomParser::str_get_html($htmlstr);
			$html->find('title', 0)->innertext('New Title');
			foreach($html->find('div') as $div) {
				$div->innertext = 'change';
			}
			return $html->__toString();
		}, $htmlstr);
		//$this->log('distance: ' . similar_text($htmlstr, $result));
		$this->logBench($bench);
		
		$this->log('', true);
		
		
		$this->log('Measuring XParser...');		
		$result = $bench->run(function($htmlstr){
			$html = new XNode($htmlstr);
			$html->find('title')->inner('New Title');
			$html->find('div')->inner('change');
			return $html->__toString();
		}, $htmlstr);
		//$this->log('distance: ' . similar_text($htmlstr, $result));
		$this->logBench($bench);
		
		$this->log('', true);
		
		
		$this->log('Measuring Ganon...');		
		$result = $bench->run(function($htmlstr){
			$html = str_get_dom($htmlstr);
			foreach($html('title') as $title) {
				$title->setInnerText('New Title');
			}
			foreach($html('div') as $div) {
				$div->setInnerText('change');
			}
			return $html->__toString();
		}, $htmlstr);
		//$this->log('distance: ' . similar_text($htmlstr, $result));
		$this->logBench($bench);
		
		$this->log('', true);
		$this->log('', true);
		
	}
	
	private function logBench(Ubench $bench) {
		$this->log("Time:\t\t" . $bench->getTime() . "\t" . $bench->getTime(true));
		$this->log("Memory usage:\t" . $bench->getMemoryUsage() . "\t" . $bench->getMemoryUsage(true));
		$this->log("Memory peak:\t" . $bench->getMemoryPeak() . "\t" . $bench->getMemoryPeak(true));
	}
	
}
