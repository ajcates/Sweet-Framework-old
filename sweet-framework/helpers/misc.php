<?php
//misc functions n stuff that help me


// First element of an array  
function first($in) {
	return empty($in) ? null : @$in[0];
}
// Everything after the first element of an array  
function rest($in) {
	$out = $in;
	if(!empty($out)) {
		array_shift($out);
	}
	return $out;
}  

function chain($baseItem, $items=array()) {
	return eval(D::log('return ' . join('->', f_construct('$baseItem', (array)$items)) . ';', 'evalers') );
}
  
// Take an element and an array  
//  and fuse them together so that the element  
//  is at the front of the array  
function construct($first, $rest) {
	array_unshift($rest, $first);
	return $rest;
}

function same($a, $b) {
	return ($a == $b);
}

function properJsonDecode($json) {
	//maybe if we check something on the left we can validate that the value on the right is actaully a value and not part of a string.
	$return = json_decode(D::log(preg_replace('@"(\w*)"\s*:\s*(-?\d{9,})\s*([,|\}])@', '"$1":"$2"$3', $json), 'raw json') );
	
	
	switch(json_last_error()) {
        case JSON_ERROR_DEPTH:
            $echo = ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_CTRL_CHAR:
            $echo = ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            $echo = ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_NONE:
            $echo = ' - No errors';
        break;
	}
	D::growl($echo, 'json error');
	
	
	return $return;
	
	
	
	
	
}




define('EMPTY_STRING', '');

function foxy_utf8_to_nce($utf = '') { 
	if(empty($utf)) {
		return($utf);
	}  

	$max_count = 5; // flag-bits in $max_mark ( 1111 1000 == 5 times 1) 
	$max_mark = 248; // marker for a (theoretical ;-)) 5-byte-char and mask for a 4-byte-char; 

	$html = '';
	for($str_pos = 0; $str_pos < strlen($utf); $str_pos++) { 
	    $old_chr = $utf{$str_pos}; 
	    $old_val = ord( $utf{$str_pos} ); 
	    $new_val = 0; 
	
	    $utf8_marker = 0; 
	
	    // skip non-utf-8-chars 
	    if( $old_val > 127 ) { 
			$mark = $max_mark; 
			for($byte_ctr = $max_count; $byte_ctr > 2; $byte_ctr--) { 
		        // actual byte is utf-8-marker? 
				if( ( $old_val & $mark  ) == ( ($mark << 1) & 255 ) ) { 
					$utf8_marker = $byte_ctr - 1; 
					break; 
				} 
				$mark = ($mark << 1) & 255; 
			} 
		} 

    // marker found: collect following bytes 
    if($utf8_marker > 1 and isset( $utf{$str_pos + 1} ) ) { 
      $str_off = 0; 
      $new_val = $old_val & (127 >> $utf8_marker); 
      for($byte_ctr = $utf8_marker; $byte_ctr > 1; $byte_ctr--) { 

        // check if following chars are UTF8 additional data blocks 
        // UTF8 and ord() > 127 
        if( (ord($utf{$str_pos + 1}) & 192) == 128 ) { 
          $new_val = $new_val << 6; 
          $str_off++; 
          // no need for Addition, bitwise OR is sufficient 
          // 63: more UTF8-bytes; 0011 1111 
          $new_val = $new_val | ( ord( $utf{$str_pos + $str_off} ) & 63 ); 
        } 
        // no UTF8, but ord() > 127 
        // nevertheless convert first char to NCE 
        else { 
          $new_val = $old_val; 
        } 
      } 
      // build NCE-Code 
      $html .= '&#'.$new_val.';'; 
      // Skip additional UTF-8-Bytes 
      $str_pos = $str_pos + $str_off; 
    } 
    else { 
      $html .= chr($old_val); 
      $new_val = $old_val; 
    } 
  } 
  return($html); 
}





function is_empty($var) {
	return empty($var);
}
function isEmpty($var) {
	return empty($var);
}

function matchAll($pattern, $subject) {
	$matches = array();
	if(preg_match_all($pattern, $subject, $matches)) {
		return $matches;
	}
	return null;
}

function match($pattern, $subject) {
	$matches = array();
	D::log($subject, 'subject');
	if(preg_match($pattern, $subject, $matches)) {
		
		return $matches;
	}
	return null;
}

function varName(&$var, $prefix='unique', $suffix='value') {
	$vals = get_defined_vars();
	$old = $var;
	$var = $new = $prefix.rand().$suffix;
	$vname = FALSE;
	foreach($vals as $key => $val) {
		if($val === $new) {
			$vname = $key;
			$var = $old;
			return $vname;
		}
    }
    return '(Anonymous Variable)';
    
}

function backtrace($NL = "\n") {
	@$dbgMsg .= $NL."Debug backtrace begin:$NL";
	$dbgMsg .= print_r_tree_expanded(debug_backtrace());
	$dbgMsg .= "Debug backtrace end".$NL;
	echo $dbgMsg;
}

function stacktrace() {
	return f_map(
		function($code) {
			return 'Function: ' . @$code['function'] . ' File: ' . @$code['file'] . ' Line: ' . @$code['line'] . "\n";
		},
		debug_backtrace()
	);
}



function objToArray($obj) {
	$dataArray = array();
	foreach($obj as $k => $v) {
		$dataArray[$k] = $v;
	}
	return $dataArray;
}
function arrayToObj($array) {
	$obj = new stdClass();
	foreach($array as $k => $v) {
		$obj->$k = $v;
	}
	return $obj;
}










function nothing($arg) {
	return $arg;
}

function () {
	global $app;
	$appPointer =& $app;
	return $appPointer;
	//return &$app;
}

function apple() {
	return ();
}

function λ($args, $code) {
	static $n = 0;
	$functionName = 'λ_' . ++$n;
	if(empty($args)) {
		$functionArgs = '';
	} else {
		$functionArgs = '$' . join(',$', $args);
	}
	$function = 'function ' . $functionName . '(' . $functionArgs . ') ';
	$function .= $code;
	//print_p($function);
	eval($function);
	return $functionName;
}

function funktion($args, $code) {
	//just an alisae
	return λ($args, $code);
}

function extendFunction($callback, $function) {
	//sadly only works with varible functions.
	return $callback($function);
}

function fa($functionName, $args) {
	return call_user_func_array($functionName, $args);
}

function f($functionName) {
	$functionParameters = func_get_args();
	array_shift($functionParameters);
	return call_user_func_array($functionName, $functionParameters);
}

function map($transformer, $in) {
	if(!empty($in)) {
		return construct(
			f(
				$transformer,
				first($in)
			),
			map(
				$transformer,
				rest($in)
			)
		);
	} else {
		return array();
	}
};

function keyMap($combiner, $in, $keys=null) {
	if(!empty($in)) {
		if(!isset($keys)) {
			$keys = array_keys($in);
			$in = array_values($in);	
		}
		return construct(
			fa(
				$combiner,
				array(
					first($keys),
					first($in)
				)
			),
			keyMap(
				$combiner,
				rest($in),
				rest($keys)
			)
		);
	} else {
		return array();
	}
	//takes a combiner function
}
// 1 2 3 4

// x + Y

// 1 + 2) + 3 + 4 = reduce(x+y, 1234, array)
function cleanse($array) {
	//don't think this should be used.
	//removes any items matching item from the array.
	return map(
		funktion(
			array('item'),
			'{
				if($item != false && empty($item) != true) {
					return $item;
				} else {
					return false;
				}
			}'
		),
		$array
	);
}

function reduce($combiner, $in, $identity) {
	if(!empty($in)) {
		return $combiner(
			first($in),
			reduce(
				$combiner,
				rest($in),
				$identity
			)
		);
	} else {
		$identity;
	}
}; 
function notRetardedSort($sort, $type=SORT_REGULAR) {
	sort($sort, $type);
	return $sort;
}

function sortBy($objects_array, $p) {
	uasort(
		$objects_array,
		function($a, $b) use($p) {
			if($a->$p == $b->$p) {
				return 0;
			} else if($a->$p > $b->$p) {
				return 1;
			} else {
				return -1;
			}
		}
	);
	return $objects_array;
}
function arraySortBy($array, $p) {
	uasort(
		$array,
		function($a, $b) use($p) {
			if($a[$p] == $b[$p]) {
				return 0;
			} else if($a[$p] > $b[$p]) {
				return 1;
			} else {
				return -1;
			}
		}
	);
	return $array;
}



function array_implode($arrays, &$target = array()) {
    foreach ($arrays as $item) {
        if (is_array($item)) {
            array_implode($item, $target);
        } else {
            $target[] = $item;
        }
    }
    return $target;
}


function print_r_tree_expanded($data) {
	print_r_tree($data, $display='block');
}
function print_p($value) {
    echo '<pre>';
    print_r($value);
    echo '</pre>'; 
}
function print_r_tree($data, $display='none') {
/* @todo
	make this function actaully loop thur the data and build a more structered debugging output.
	 */


    // capture the output of print_r
    $out = print_r($data, true);

    // replace something like '[element] => <newline> (' with <a href="javascript:toggleDisplay('...');">...</a><div id="..." style="display: none;">
    $out = preg_replace(
		'/([ \t]*)(\[[^\]]+\][ \t]*\=\>[ \t]*[a-z0-9 \t_]+)\n[ \t]*\(/iUe',
		"'\\1<a href=\"javascript:toggleDisplay(\''.(\$id = substr(md5(rand().'\\0'), 0, 7)).'\');\">\\2</a><div id=\"'.\$id.'\" style=\"display: ' . \$display . ';\">'",
		$out
	);

    // replace ')' on its own on a new line (surrounded by whitespace is ok) with '</div>
    $out = preg_replace('/^\s*\)\s*$/m', '</div>', $out);

    // print the javascript function toggleDisplay() and then the transformed output
	echo '<div class="print_r">';
	echo '<style>
			.print_r {
				background: #E0E0E0;
				padding: .5em;
				font-size: 11px;
				font-family: monaco;
				margin: .5em;
				clear: both;
				float:left;
			}
			.print_r a {
				display: block;
				margin-left: 1em;
			}
			.print_r div {
				margin-left: 2em;
			}
		</style>';
    echo '<script language="Javascript">function toggleDisplay(id) { document.getElementById(id).style.display = (document.getElementById(id).style.display == "block") ? "none" : "block"; }</script>'."\n$out";
	echo '</div>';
}



function wtf($var, $arrayOfObjectsToHide=null, $fontSize=11) {
    $text = print_r($var, true);

    if (is_array($arrayOfObjectsToHide)) {
    
        foreach ($arrayOfObjectsToHide as $objectName) {
    
            $searchPattern = '#('.$objectName.' Object\n(\s+)\().*?\n\2\)\n#s';
            $replace = "$1<span style=\"color: #FF9900;\">--&gt; HIDDEN - courtesy of wtf() &lt;--</span>)";
            $text = preg_replace($searchPattern, $replace, $text);
        }
    }

    // color code objects
    $text = preg_replace('#(\w+)(\s+Object\s+\()#s', '<span style="color: #079700;">$1</span>$2', $text);
    // color code object properties
    $text = preg_replace('#\[(\w+)\:(public|private|protected)\]#', '[<span style="color: #000099;">$1</span>:<span style="color: #009999;">$2</span>]', $text);
    
    return '<pre style="font-size: '.$fontSize.'px; line-height: '.$fontSize.'px;">'.$text.'</pre>';
}






