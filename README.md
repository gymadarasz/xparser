# XParser
## XParser is a very fast XHTML DOM Parser and easy to use. 
If you like the jQuery style DOM selection at the PHP backend side 
or you like simple_html_dom or Ganon you will love this smart library!

### Why you sould use it? 
### - benchmark:
```
[2015-12-26 15:50:09] Measuring Simple HTML DOM Parser...
[2015-12-26 15:50:09] Time:		316ms	0.31599998474121
[2015-12-26 15:50:09] Memory usage:	2.00Mb	2097152
[2015-12-26 15:50:09] Memory peak:	2.00Mb	2097152
[2015-12-26 15:50:09] 
[2015-12-26 15:50:09] Measuring XParser...
[2015-12-26 15:50:09] Time:		31ms	0.030999898910522
[2015-12-26 15:50:09] Memory usage:	2.25Mb	2359296
[2015-12-26 15:50:09] Memory peak:	2.25Mb	2359296
[2015-12-26 15:50:09] 
[2015-12-26 15:50:09] Measuring Ganon...
[2015-12-26 15:50:10] Time:		933ms	0.93300008773804
[2015-12-26 15:50:10] Memory usage:	5.25Mb	5505024
[2015-12-26 15:50:10] Memory peak:	5.25Mb	5505024
[2015-12-26 15:50:10] 
[2015-12-26 15:50:10] Symfony CSS Selector combined with DOMDocument and DOMXPath...
[2015-12-26 15:50:10] Time:		53ms	0.052999973297119
[2015-12-26 15:50:10] Memory usage:	5.25Mb	5505024
[2015-12-26 15:50:10] Memory peak:	5.25Mb	5505024 
```
and:
- PHP 5.5
- Composer & PSR-4 Support
- Unit testing via Minitest
- PHP-Quality testing via SensioLabsInsight


### Install via Composer

`$ composer require gymadarasz/xparser`

### Usage

```php
<?php
include 'vendor/autoload.php';

// load a DOM root form a string or file/url
$x = new XNode(file_get_contents('http://your-important-document-or-template.com'));

// select elements via simple CSS selector and read attributs or manipulate contents easily e.g.:
$x('a#your_link')->inner('Hello world')->href = 'http://your.hellopage.com';

// or make a foreach on all elements
foreach($x('div.hello') as $index => $hellodiv) {
  $hellodiv->inner('It is the ' . $index . 'th Hello DIV!');
}

// show document
echo $x;
```

#### Other facilities

```php

// make a DOM node element
$x = new XNode('<p>Your XHTML or XML here...</p>');

// get any attribute
$attr = $x->attr('href');

// same but shorten:
$attr = $x->href;

// set any attributes
$x->attr('href', 'link-here');

// or just:
$x->href = 'link-here';

// find elements in the dom struct, e.g. find all <div> elements:
$x->find('div');
// or find an n-th element only:
$x->find('div', 3);

// or sort format:
$x('div');

// here, you can use complex css selection e.g:
$x('div#first, div#sixth, div.selected')

// or you can use typically method calls
$x->getElementById('element01');
$x->getElements($tagName, $attributeRegex, $attributeValueRegex); // <- all parameters are optional
$x->getElementsByClass('class-name');

// get/set element inner text:
$inner = $x->inner();
$x->inner('Hello World!');

// get/set element outer text:
$outer = $x->outer();
$x->outer('It\'ll replace the element!');

```

Note:
My goal is not to make a better CSS selection than e.g symphony. I want to make a realy fast html reader and/or manipulator lib for php, the css selection is just an 'extra' in this lib. If you have any idea how it will better please [leave an issue](https://github.com/gymadarasz/xparser/issues/new) on github.
