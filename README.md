# XParser
## XParser is a very fast XHTML DOM Parser and easy to use. 
If you like the jQuery style DOM selection at the PHP backend side 
or you like simple_html_dom or Ganon you will love this smart library!

### Why you sould use it? 
### - benchmark:
```
  Measuring Simple HTML DOM Parser...
  Time:		442ms	0.44200015068054
  Memory usage:	2.00Mb	2097152
  Memory peak:	2.00Mb	2097152

  Measuring XParser...
  Time:		29ms	0.029000043869019
  Memory usage:	2.25Mb	2359296
  Memory peak:	2.25Mb	2359296

  Measuring Ganon...
  Time:		945ms	0.9449999332428
  Memory usage:	5.00Mb	5242880
  Memory peak:	5.00Mb	5242880
  
  Symfony CSS Selector combined with DOMDocument and DOMXPath...
  Time:		53ms	0.052999973297119
  Memory usage:	5.25Mb	5505024
  Memory peak:	5.25Mb	5505024  
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
