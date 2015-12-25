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
		$resultsSimpleHtmlDomParser = $bench->run(function($htmlstr){
			$results = [];
			
			$html = HtmlDomParser::str_get_html($htmlstr);
			$html->find('title', 0)->innertext('New Title');
			foreach($html->find('div') as $div) {
				$div->innertext = 'change';
			}
			$results[1] = $html->__toString();
						
			$tpl = HtmlDomParser::str_get_html(file_get_contents('tests/templated-retrospect/index.html'));
			foreach($tpl->find('link') as $elem) {
				$elem->href = '//localhost/xparser/tests/templated-retrospect/' . $elem->href;
			}
			foreach($tpl->find('img, script') as $elem) {
				$elem->src = '//localhost/xparser/tests/templated-retrospect/' . $elem->src;
			}
			$results[2] = $tpl->__toString();
			
			return $results;
			
		}, $htmlstr);
		//$this->log('distance: ' . similar_text($htmlstr, $result));
		$this->logBench($bench);
		
		$this->log('', true);
		
		
		$this->log('Measuring XParser...');		
		$resultsXParser = $bench->run(function($htmlstr){
			$results = [];
			
			$html = new XNode($htmlstr);
			$html->find('title')->inner('New Title');
			$html->find('div')->inner('change');
			$results[1] = $html->__toString();
						
			$tpl = new XNode(file_get_contents('tests/templated-retrospect/index.html'));
			foreach($tpl('link') as $elem) {
				$elem->href = '//localhost/xparser/tests/templated-retrospect/' . $elem->href;
			}
			foreach($tpl('img, script')->getElements() as $elem) {
				$elem->src = '//localhost/xparser/tests/templated-retrospect/' . $elem->src;
			}
			$results[2] = $tpl->__toString();
			
			return $results;
		
		}, $htmlstr);
		//$this->log('distance: ' . similar_text($htmlstr, $result));
		$this->logBench($bench);
		
		$this->log('', true);
		
		
		$this->log('Measuring Ganon...');		
		$resultsGanon = $bench->run(function($htmlstr){
			$html = str_get_dom($htmlstr);
			foreach($html('title') as $title) {
				$title->setInnerText('New Title');
			}
			foreach($html('div') as $div) {
				$div->setInnerText('change');
			}
			$results[1] = $html->__toString();
			
			$tpl = new XNode(file_get_contents('tests/templated-retrospect/index.html'));		
			foreach($tpl('link') as $elem) {
				$elem->href = '//localhost/xparser/tests/templated-retrospect/' . $elem->href;
			}
			foreach($tpl('img, script')->getElements() as $elem) {
				$elem->src = '//localhost/xparser/tests/templated-retrospect/' . $elem->src;
			}
			$results[2] = $tpl->__toString();
			
			return $results;
			
		}, $htmlstr);
		//$this->log('distance: ' . similar_text($htmlstr, $result));
		$this->logBench($bench);
		
		
		$this->log('', true);
		
		$this->log('Simple HTML DOM Parser vs Ganon distance: ' . similar_text($resultsSimpleHtmlDomParser[2], $resultsGanon[2]));
		$this->log('Simple HTML DOM Parser vs XParser distance: ' . similar_text($resultsSimpleHtmlDomParser[2], $resultsXParser[2]));
		$this->log('Ganon vs XParser distance: ' . similar_text($resultsGanon[2], $resultsXParser[2]));
		
		$this->log('', true);
		$this->log('', true);
		
	}
	
	private function logBench(Ubench $bench) {
		$this->log("Time:\t\t" . $bench->getTime() . "\t" . $bench->getTime(true));
		$this->log("Memory usage:\t" . $bench->getMemoryUsage() . "\t" . $bench->getMemoryUsage(true));
		$this->log("Memory peak:\t" . $bench->getMemoryPeak() . "\t" . $bench->getMemoryPeak(true));
	}
	
}
