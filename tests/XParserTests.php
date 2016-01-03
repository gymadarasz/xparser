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
use gymadarasz\xparser\XParserException;

/**
 * Description of XParserTests
 *
 * @author Gyula Madarasz <gyula.madarasz at gmail.com>
 */
class XParserTests extends MiniTestAbstract {
	
	public function run() {
		//$this->phptest();
		ini_set('xdebug.var_display_max_data', 10000);
		$this->start('eachtest');
		$this->start('test8');
		$this->start('select1');
		$this->start('validation1');
		$this->start('test7');
		$this->start('parent1');
		$this->start('test6');
		$this->start('test5');
		$this->start('test4');
		$this->start('test3');
		$this->start('test2');
		$this->start('mainTest');
	}
	
	protected function eachtest() {
		$x = new XNode(file_get_contents('tests/templated-retrospect/index.html'));
		$x->each('#nav a', function($elem) {
			$elem->href = '//myurl/' . $elem->href;
		});
		$x->each('#nav a', function($elem) {
			$this->equ(substr($elem->href, 0, strlen('//myurl/')), '//myurl/');
		});
	}
	
	protected function test8() {
		$x = new XNode('
					<header class="major narrow"><h2>Aliquam Blandit Mauris</h2>
						<p>Ipsum dolor tempus commodo turpis adipiscing Tempor placerat sed amet accumsan</p>
					</header><div class="image-grid">
						<a href="#" class="image"><img src="images/pic03.jpg" alt=""/></a>
						<a href="#" class="image"><img src="images/pic04.jpg" alt=""/></a>
						<a href="#" class="image"><img src="images/pic05.jpg" alt=""/></a>
						<a href="#" class="image"><img src="images/pic06.jpg" alt=""/></a>
						<a href="#" class="image"><img src="images/pic07.jpg" alt=""/></a>
						<a href="#" class="image"><img src="images/pic08.jpg" alt=""/></a>
						<a href="#" class="image"><img src="images/pic09.jpg" alt=""/></a>
						<a href="#" class="image"><img src="images/pic10.jpg" alt=""/></a>
					</div>
					<ul class="actions"><li><a href="#" class="button big alt">Tempus Aliquam</a></li>
					</ul>');
		$this->equ($x->getCount('div.image-grid'), 1);
	}
	
	protected function select1() {
		$x = new XNode(
'<html>
    <head>
        <title>Test page</title>
    </head>
    <body>
                <div id="hello1" class="message">
                    <div id="sub1">
                      <span>
                        ho
                      </span>
                    </div>
                </div>
    </body>
</html>');
		
		
        $result = $x->find('#hello1 > span')->inner();
		$good = [];
		$this->equ($result, $good);

        $result = $x->find('#sub1 > span')->inner();
		$good = '
                        ho
                      ';
		$this->equ($result, $good);
		
        $result = $x->find('#sub1 > span', 0)->outer();
		$good = '<span>
                        ho
                      </span>';
		$this->equ($result, $good);
	}
	
	protected function validation1() {
		$x = new XNode(
		'<html>
			<head>
				<title>Test page</title>
			</head>
			<body>
						<div class="hello2">
			</body>
		</html>');

		$this->equ($x->validate(), false);
		
		
		$x = new XNode(
		'<html>
			<head>
				<title>Test page</title>
			</head>
			<body>
						<div class="hello2">
						</div>
			</body>
		</html>');

		$this->equ($x->validate(), true);
		
	}
	
	protected function test7() {
		$x = new XNode(
		'<html>
			<head>
				<title>Test page</title>
			</head>
			<body>
				  <div onch-class="toto">
				  </div>
			</body>
		</html>');

		$this->equ($x->getCount('.toto'), 0);
		$this->equ($x->find('.toto')->outer(), false);
		

		$x = new XNode(
		'<html>
			<head>
				<title>Test page</title>
			</head>
			<body>
				  <div class-eh="toto">
				  </div>
			</body>
		</html>');		

		$this->equ($x->getCount('.toto'), 0);
		$this->equ($x->find('.toto')->outer(), false);		
		
		
		$x = new XNode(
		'<html>
			<head>
				<title>Test page</title>
			</head>
			<body>
				  <div onch-class-eh="toto">
				  </div>
			</body>
		</html>');

		$this->equ($x->getCount('.toto'), 0);
		$this->equ($x->find('.toto')->outer(), false);		
	}
	
	protected function parent1() {
		$x = new XNode('start of node <div> in parent before span <span> in span </span> after the span </div> end of node');
		$span = $x->find('span', 0);
		$parent = $span->getParent();
		$result = $parent->outer();
		$good = '<div> in parent before span <span> in span </span> after the span </div>';
		$this->equ($result, $good);
	}	
	
	// remove it, just for optimizer strategy testing
	private function phptest() {

		$length = 100;
		$variants = 10;
		
		$founds = [];
		$start = microtime(true);
		for($i=0; $i<1000; $i++) {
			$news = [];
			for($j=0; $j<rand(0,$length); $j++) {
				$news[] = rand(1,$variants);
			}
			
			$founds = array_merge($founds, $news);
			
//			foreach($news as $new) {
//				if(!in_array($new, $founds)) {
//					$founds[] = $new;
//				}
//			}

		}
		$founds = array_unique($founds);
		var_dump(microtime(true)-$start);
		echo "[count:" . count($founds) . "]\n";
		
		$founds = [];
		$start = microtime(true);
		for($i=0; $i<1000; $i++) {
			$news = [];
			for($j=0; $j<rand(0,$length); $j++) {
				$news[] = rand(1,$variants);
			}
			
			//$founds = array_merge($founds, $news);
			
			foreach($news as $new) {
				if(!in_array($new, $founds)) {
					$founds[] = $new;
				}
			}

		}
		$founds = array_unique($founds);
		var_dump(microtime(true)-$start);
		echo "[count:" . count($founds) . "]\n";
		
		$founds = [];
		$start = microtime(true);
		for($i=0; $i<1000; $i++) {
			$news = [];
			for($j=0; $j<rand(0,$length); $j++) {
				$news[] = rand(1,$variants);
			}
			
			//$founds = array_merge($founds, $news);
			
			foreach($news as $new) {
				//if(!in_array($new, $founds)) {
					$founds[] = $new;
				//}
			}

		}
		$founds = array_unique($founds);
		var_dump(microtime(true)-$start);
		echo "[count:" . count($founds) . "]\n";
				
	}
	
	public function test6() {
		$html = <<<HTML

<!DOCTYPE HTML>
<!--
	Retrospect by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>
	<head>
		<title>Retrospect by TEMPLATED</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	</head>
	<body class="landing">

		<!-- Header -->
				
			<header id="header" class="alt">
				<h1><a href="index.html">Retrospect</a></h1>
				<a href="#nav">Menu</a>
			</header>
				

		<!-- Nav -->
				
			<nav id="nav">
				<ul class="links">
					<li><a href="index.html">Home</a></li>
					<li><a href="generic.html">Generic</a></li>
					<li><a href="elements.html">Elements</a></li>
				</ul>
			</nav>
				
		<!-- Banner -->
				
			<section id="banner">
				<i class="icon fa-diamond"></i>
				<h2>Etiam adipiscing</h2>
				<p>Magna feugiat lorem dolor egestas</p>
				<ul class="actions">
					<li><a href="#" class="button big special">Learn More</a></li>
				</ul>
			</section>
				
		<!-- One -->
				
			<section id="one" class="wrapper style1">
				<div class="inner">
					<article class="feature left">
						<span class="image"><img src="images/pic01.jpg" alt="" /></span>
						<div class="content">
							<h2>Integer vitae libero acrisus egestas placerat  sollicitudin</h2>
							<p>Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est.</p>
							<ul class="actions">
								<li>
									<a href="#" class="button alt">More</a>
								</li>
							</ul>
						</div>
					</article>
					<article class="feature right">
						<span class="image"><img src="images/pic02.jpg" alt="" /></span>
						<div class="content">
							<h2>Integer vitae libero acrisus egestas placerat  sollicitudin</h2>
							<p>Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est.</p>
							<ul class="actions">
								<li>
									<a href="#" class="button alt">More</a>
								</li>
							</ul>
						</div>
					</article>
				</div>
			</section>
				
		<!-- Two -->
				
			<section id="two" class="wrapper special">
				<div class="inner">
					<header class="major narrow">
						<h2>Aliquam Blandit Mauris</h2>
						<p>Ipsum dolor tempus commodo turpis adipiscing Tempor placerat sed amet accumsan</p>
					</header>
					<div class="image-grid">
						<a href="#" class="image"><img src="images/pic03.jpg" alt="" /></a>
						<a href="#" class="image"><img src="images/pic04.jpg" alt="" /></a>
						<a href="#" class="image"><img src="images/pic05.jpg" alt="" /></a>
						<a href="#" class="image"><img src="images/pic06.jpg" alt="" /></a>
						<a href="#" class="image"><img src="images/pic07.jpg" alt="" /></a>
						<a href="#" class="image"><img src="images/pic08.jpg" alt="" /></a>
						<a href="#" class="image"><img src="images/pic09.jpg" alt="" /></a>
						<a href="#" class="image"><img src="images/pic10.jpg" alt="" /></a>
					</div>
					<ul class="actions">
						<li><a href="#" class="button big alt">Tempus Aliquam</a></li>
					</ul>
				</div>
			</section>
				
		<!-- Three -->
				
			<section id="three" class="wrapper style3 special">
				<div class="inner">
					<header class="major narrow	">
						<h2>Magna sed consequat tempus</h2>
						<p>Ipsum dolor tempus commodo turpis adipiscing Tempor placerat sed amet accumsan</p>
					</header>
					<ul class="actions">
						<li><a href="#" class="button big alt">Magna feugiat</a></li>
					</ul>
				</div>
			</section>
				
		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
	</body>
</html>
HTML;
		
		$x = new XNode($html);
		
		$excepted = false;
		try {
			$x->find('div');
		}
		catch(XParserException $e) {
			$excepted = true;
		}
		$this->equ($excepted, true);
		
		$divs = $x->find('div.inner')->getElements();
		$good = 3;
		$found = count($divs);
		$divsCnt = $x->getCount('div.inner');
		$this->equ($divsCnt, $found);
		$this->equ($good, $found);
		
		
		// more test for bench..
		$results = [];

		$html = new XNode($html);
		$html->find('title')->inner('New Title');

		$results[1] = $html->__toString();
		$this->equ(strpos($results[1], 'New Title')!==false, true);

		$tpl = new XNode(file_get_contents('tests/templated-retrospect/index.html'));
		foreach($tpl('link') as $elem) {
			$elem->href = '//localhost/xparser/tests/templated-retrospect/' . $elem->href;
		}
		foreach($tpl('img, script') as $elem) {
			$elem->src = '//localhost/xparser/tests/templated-retrospect/' . $elem->src;
		}
		$results[2] = $tpl->__toString();		
		
		$links = count($tpl('link')->getElements());
		$imgs = count($tpl('img')->getElements());
		$scripts = count($tpl('script')->getElements());
		$sum = $links+$imgs+$scripts;
		
		$linksCnt = $tpl->getCount('link');
		$imgsCnt = $tpl->getCount('img');
		$scriptsCnt = $tpl->getCount('script');		
		$this->equ($linksCnt, $links);
		$this->equ($imgsCnt, $imgs);
		$this->equ($scriptsCnt, $scripts);
		
		$pieces = count(explode('//localhost/xparser/tests/templated-retrospect/', $results[2]));
		
		$this->equ($sum, $pieces-1);
		
	}
	
	public function test5() {
		
		$x = new XNode('<div class="hello2">
							  2
							</div>');
		$divs = $x->find('div.hello2')->getElements();
		$result = count($divs);
		$good = 1;
		$this->equ($result, $good, 'pre-test #1');
		
		$divsCnt = $x->getCount('div.hello2');
		$this->equ($divsCnt, $result);
		
		
		$x = new XNode('
						  <div class="hello2">
							
						</div>');
		$divs = $x->find('div.hello2')->getElements();
		$result = count($divs);
		$good = 1;
		$this->equ($result, $good, 'pre-test #2');
		
		$divsCnt = $x->getCount('div.hello2');
		$this->equ($divsCnt, $result);
		
		
		$x = new XNode('
						  <div class="hello2">
							<div class="hello2">
							  2
							</div>
						</div>');
		$divs = $x->find('div.hello2')->getElements();
		$result = count($divs);
		$good = 2;
		$this->equ($result, $good, 'pre-test #3' . PHP_EOL . htmlentities(print_r($divs, true)));
		
		$divsCnt = $x->getCount('div.hello2');
		$this->equ($divsCnt, $result);
		
		
		$x = new XNode('<div class="hello2">
						  <span>1</span>
						</div>
						  <div class="hello2">
							
						</div>');
		$divs = $x->find('div.hello2')->getElements();
		$result = count($divs);
		$good = 2;
		$this->equ($result, $good, 'pre-test #4' . PHP_EOL . htmlentities(print_r($divs, true)));
		
		$divsCnt = $x->getCount('div.hello2');
		$this->equ($divsCnt, $result);
		
		
		
		$x = new XNode('<div class="hello2">
						  <span>1</span>
						</div>
						  <div class="hello2">
							<div class="hello2">
							  2
							</div>
						</div>');
		$divs = $x->find('div.hello2')->getElements();
		$result = count($divs);
		$good = 3;
		$this->equ($result, $good, 'pre-test #5');
		
		$divsCnt = $x->getCount('div.hello2');
		$this->equ($divsCnt, $result);
		

		
		
		$x = new XNode('<div class="hello2">
							<div class="hello2">
							  2
							</div>
						</div>
						<div class="hello2">
						  <span>1</span>
						</div>');
		$divs = $x->find('div.hello2')->getElements();
		$result = count($divs);
		$good = 3;
		$this->equ($result, $good, 'pre-test #6' . PHP_EOL . htmlentities(print_r($divs, true)));		
		
		$divsCnt = $x->getCount('div.hello2');
		$this->equ($divsCnt, $result);
		
		
		$x = new XNode(
		'<html>
			<head>
				<title>Test page</title>
			</head>
			<body>
						<div class="hello2">
						  <span>1</span>
						</div>
						  <div class="hello2">
							<div class="hello2">
							  2
							</div>
						</div>
			</body>
		</html>');

		$spans = $x->find('div.hello2')->getElements();
		$result = count($spans);
		$good = 3;
		$this->equ($result, $good, '@debug it...');
		
		$divsCnt = $x->getCount('div.hello2');
		$this->equ($divsCnt, $result);
	}
	
	public function test4() {
		$x = new XNode('
                  <div class="hello2"></div>
                ');
		$divs = $x->find('div');
		$this->equ(count($divs), 1);
				
		$this->equ($x->getCount('div'), count($divs));
		
		
		$x = new XNode(
'<html>
    <head>
        <title>Test page</title>
    </head>
    <body>
                <div id="hello1" class="message">
                  <div class="hello2"></div>
                </div>
    </body>
</html>');


		$good = '<div class="hello2"></div>';
		$inner = $x->find('#hello1 div')->outer();		

		$this->equ($good, $inner);
		
		$x = new XNode(
'<html>
    <head>
        <title>Test page</title>
    </head>
    <body>
                <div id="hello1" class="message">
                  <div class="hello2"></div>
                </div>
    </body>
</html>');		
		$hello1OuterGood = '<div id="hello1" class="message">
                  <div class="hello2"></div>
                </div>';
		$hello1InnerTrimmedGood = '<div class="hello2"></div>';
		$hello2OuterGood = $hello1InnerTrimmedGood;
		
		$hello1Outer = $x->find('#hello1')->outer();
		$hello1InnerTrimmed = trim($x->find('#hello1')->inner());
		$hello1DivOuter = $x->find('#hello1 div')->outer();
		$hello2Outer = $x->find('.hello2')->outer();
		
		$this->equ($hello1OuterGood, $hello1Outer);
		$this->equ($hello1InnerTrimmedGood, $hello1InnerTrimmed);
		$this->equ($hello2OuterGood, $hello2Outer);
		$this->equ($hello2Outer, $hello1InnerTrimmed);
		$this->equ($hello1DivOuter, $hello2Outer);
		
		$this->equ($x->find('.hello2', 0)->getParent()->outer(), $x->find('#hello1')->outer());
	}
	
	public function test3() {
$x = new XNode(
'<html>
    <head>
        <title>Test page</title>
    </head>
    <body>
                <div id="hello1" class="message">
                    <div>
                      <span>
                        ho
                      </span>
                    </div>
                </div>
    </body>
</html>');

		$good = [];
        $inner = $x->find('#hello1 > span')->inner();

		$this->equ($good, $inner);
	}
	
	public function test2() {
		include 'vendor/autoload.php';
		$x = new XNode(
				'<html>
<head>
<title>Test page</title>
</head>
<body>
<div id="hello1" class="message">
<div>
ho
</div>
</div>
</body>
</html>');
		
		$good = '
<div>
ho
</div>
';

		$inner = $x->find('#hello1')->inner();
		
		$this->equ($good, $inner);
	}
	
	public function mainTest() {
		$tpl = new XNode(
'<html>
	<head>
		<title>Test page</title>
	</head>
	<body>
		<h1>Lorem ipsum</h1>		

		<div />

		<div>asd</div>

		<div id= "hello01" asdasdw />
<!--
		<div id= "hello02" asdasdw class="message" asdasd />
-->
		<hr>

		<div id="hello1" class="message"> Hello World! </div>

		<hr>

		<div id="hello2" class="message selected"> before <span>Hello World!</span> after </div>

		<hr>

		<div id="hello3" class="message"> before <div>Hello <span>here</span> World!</div> after </div>

		<hr>
		
		<input type="text" id="myinput1" value="my value here..">

	</body>
</html>');

		$before = $tpl->find('div#hello2.selected.message, div#hello1')->inner();
		$tpl->find('div#hello2.selected.message, div#hello1')->inner('yupeeee!');
		$after = $tpl->find('div#hello2.selected.message, div#hello1')->inner();
		$this->equ($before, ' Hello World! ');
		$this->equ($after, 'yupeeee!');

		$before = $tpl->find('html body input')->attr('value');
		$tpl->find('input')->attr('value', 'elembe!');
		$after = $tpl->find('html body input')->attr('value');
		$this->equ($before, 'my value here..');
		$this->equ($after, 'elembe!');
		
		$before = $tpl->outer();
		$this->equ(count($tpl('#hello02')->getElements()), 0);
		$after = $tpl->outer();
		$this->equ($before, $after);
		
		$this->equ(count($tpl('#hello02')->getElements()), $tpl->getCount('#hello02'));
		
	}
	
}
