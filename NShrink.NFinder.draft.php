<!DOCTYPE html><link rel="stylesheet" href="files/style.css">

<h1>NShrink and NFinder in action</h1>

<pre>
<?php

// Temporarily solution

require dirname(__FILE__) . '/Finder.php52.php';

// PHP 4 & 5 compatibility
if (!defined('T_DOC_COMMENT'))
	define ('T_DOC_COMMENT', -1);

if (!defined('T_ML_COMMENT'))
	define ('T_ML_COMMENT', -1);


function shrink($filename) {
	// read input file
	$input = file_get_contents($filename);
	
	$space = $output = '';
	$set = '!"#$&\'()*+,-./:;<=>?@[\]^`{|}';
	$set = array_flip(preg_split('//',$set));
	
	foreach (token_get_all($input) as $token)  {
		if (!is_array($token))
			$token = array(0, $token);
			
			switch ($token[0]) {
				case T_COMMENT:
				case T_ML_COMMENT:
				case T_DOC_COMMENT:
				case T_WHITESPACE:
					$space = ' ';
					break;
			
				default:
					if (isset($set[substr($output, -1)]) ||
					    isset($set[$token[1]{0}])) $space = '';
					$output .= $space . $token[1];
					$space = '';
			}
	}
	
	
	// write shrinked file
	fwrite(
		fopen($filename, 'w'),
		$output
	);
}

// recursive file search
foreach (NFinder::findFiles('*.php')->from('files/app') as $file) {
	echo $file, "\n";
	shrink($file);
}

