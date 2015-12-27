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

/**
 * Description of XParserTests
 *
 * @author Gyula Madarasz <gyula.madarasz at gmail.com>
 */
class XParserTests extends MiniTestAbstract {
	
	public function run() {
		$this->start('test4');
		$this->start('test3');
		$this->start('test2');
		$this->start('mainTest');
	}
	
	public function test4() {
		$x = new XNode('
                  <div class="hello2"></div>
                ');
		$divs = $x->find('div');
		$this->equ(count($divs), 1);
		
		
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

		$good = '
                        ho
                      ';
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
		
	}
	
}
