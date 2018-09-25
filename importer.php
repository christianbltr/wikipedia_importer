<?php
// composer require duzun/hquery
require_once('vendor/duzun/hquery/hquery.php');

use duzun\hquery;

for ($i=0; $i<5; $i++) {
	// get the document
	$doc = hQuery::fromUrl('https://en.wikipedia.org/wiki/Special:Random', ['Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8']);

	// get content
	$title = $doc->find('h1');
	$bodytext = $doc->find('#mw-content-text');

	// cleanup content
	$title = strip_tags($title);
	$bodytext = strip_tags($bodytext, '<p><br><h2><table><tr><td><th><tf>');
	$bodytext = preg_replace('/\s+/', ' ',$bodytext);
	$bodytext = str_replace('[edit]', '', $bodytext);

	// output
	echo '<h1>' . $title . "</h1>\n";
	echo '<div style="border: 1px solid grey;">' . substr($bodytext, 0, 5000) . '...' . "</div>\n\n";
}
