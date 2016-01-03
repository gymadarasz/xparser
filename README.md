# XParser
## XParser is a very fast XHTML DOM Parser and easy to use. 
If you like the jQuery style DOM selection at the PHP backend side 
or you like simple_html_dom or Ganon you will love this smart library!

### Why you sould use it? 
### - benchmark:
```
[2015-12-30 04:12:58] Measuring Simple HTML DOM Parser...
[2015-12-30 04:12:59] Time:		855ms	0.85500001907349
[2015-12-30 04:12:59] Memory usage:	1.75Mb	1835008
[2015-12-30 04:12:59] Memory peak:	1.75Mb	1835008
[2015-12-30 04:12:59] 
[2015-12-30 04:12:59] Measuring XParser...
[2015-12-30 04:12:59] Time:		67ms	0.067000150680542
[2015-12-30 04:12:59] Memory usage:	1.75Mb	1835008
[2015-12-30 04:12:59] Memory peak:	1.75Mb	1835008
[2015-12-30 04:12:59] 
[2015-12-30 04:12:59] Measuring Ganon...
[2015-12-30 04:13:00] Time:		917ms	0.91700005531311
[2015-12-30 04:13:00] Memory usage:	3.75Mb	3932160
[2015-12-30 04:13:00] Memory peak:	3.75Mb	3932160
[2015-12-30 04:13:00] 
[2015-12-30 04:13:00] Symfony CSS Selector combined with DOMDocument and DOMXPath...
[2015-12-30 04:13:00] Time:		129ms	0.12899994850159
[2015-12-30 04:13:00] Memory usage:	4.25Mb	4456448
[2015-12-30 04:13:00] Memory peak:	4.25Mb	4456448
```
and:
- PHP 5.6
- Composer & PSR-4 Support
- Unit testing via Minitest
- PHP-Quality testing via SensioLabsInsight


### Install via Composer

`$ composer require gymadarasz/xparser`

### Or download latest version

[xparser-0.3.1-dev.zip](http://madsoft.hu/xparser/download/xparser-0.3.1-dev.zip)

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

// or just use jQuery style 'each' function, it's same

$x('#nav a', function($elem) {
  $elem->href = '//myurl/' . $elem->href;
});

// show document
echo $x;
```

#### Validation
The validation is not too quick process and you don't have to use it every single html loading and parsing but if you aren't sure that your html is valid, you can check it before use:

```php
if($x->validate()) {
  // ..do something here
  $x->find('.hello2')->outer();
}
else {
  // your html contains an invalid closure structure
  die('Invalid document!');
}
```

#### Note: Be carefull!
Technically when you make a query which try to find something recursion in html struct the regex search time exponentially improve but If you try to find an exact element it seem to be very fast. So this lib fast incase if you know which is the element (or a few of elements) what you looking for and these elements aren't too deep in DOM tree in a recursion. 

We never can know in the recurions before try to proccess it, so I had to make a recursion detect in the element search function. It will working until get the deeply process as a class variable in XNode. So if you need deeper searching as default at `private static $maxRecursionInStartegy = 8;` you can modify it but be carefull!

#### Get count of elements
When you can not know how many element will in your query incase I created a `getCount` function which relatively fast and give you how much element are in your selection:

```php
// it will be return how many <div> element are in your xhtml
$count = $xnode->getCount('div'); 
```


#### Get parent element:
```php
$span = $x->find('span', 0);
$parent = $span->getParent();
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

#### Symfony CSS-Selector implemented:

If you want to use more css selection in your queries more option e.g. '>' child selection and 'nth-child' or selection by attribute etc. it's possible via Symfony CSS-Selector DOMDocument and XPath.

Note:
My goal is not to make a better CSS selection than e.g symphony. I want to make a realy fast html reader and/or manipulator lib for php, the css selection is just an 'extra' in this lib. If you have any idea how it will better please [leave an issue](https://github.com/gymadarasz/xparser/issues/new) on github.
