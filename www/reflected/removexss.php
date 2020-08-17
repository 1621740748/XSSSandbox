<?php

if (isset($_REQUEST['source'])) {
	$source = file_get_contents(__FILE__);
	$source = highlight_string($source, true);
	die($source);
}

class TikiFilter_PreventXss
{
	function filter($value)
	{
		// No need to filter really simple values
		if (ctype_alnum($value) || empty($value)) {
			return $value;
		} else {
			return $this->RemoveXSS($value);
		}
	}
	/* RemoveXSS initially developped by kallahar - quickwired.com, modified for Tiki
	 * Original code could be found here:
	 * http://quickwired.com/smallprojects/php_xss_filter_function.php
	 */
	function RemoveXSS($val)
	{
		static $ra_as_tag_only = null;
		static $ra_as_attribute = null;
		static $ra_as_content = null;
		static $ra_javascript = null;
		// now the only remaining whitespace attacks are \t, \n, and \r
		if ($ra_as_tag_only == null) {
			$ra_as_tag_only = [
				'style',
				'script',
				'embed',
				'object',
				'applet',
				'meta',
				'iframe',
				'frame',    // also filters frameset
				'ilayer',
				'layer',
				'bgsound',
				'base',
				'xml',
				'import',
				'link',
				'audio',
				'video'
			];
			// note: short forms, such as onmouse, will filter its long forms:　onmouseup, onmousedown etc.
			$ra_as_attribute = [
				'onabort',
				'onactivate',
				'onafter',
				'onbefore',
				'onbegin',
				'onblur',
				'onbounce',
				'onbroadcast',
				'oncellchange',
				'onchange',
				'onclick',
				'onclose',
				'oncommand',
				'oncontextmenu',
				'oncontrolselect',
				'oncopy',
				'oncut',
				'ondata',
				'ondblclick',
				'ondeactivate',
				'ondrag',
				'ondrop',
				'onend',
				'onerror',
				'onfilterchange',
				'onfinish',
				'onfocus',
				'ongotpointer',
				'onhashchange',
				'onhelp',
				'oninput',
				'oninvalid',
				'onkeydown',
				'onkeypress',
				'onkeyup',
				'onlayoutcomplete',
				'onload',
				'onlosecapture',
				'onlostpointer',
				'onmedia',
				'onmessage',
				'onmouse',
				'onmove',
				'onoffline',
				'ononline',
				'onoutofsync',
				'onover',
				'onpage', // Short for pageshow and pagehide
				'onpaste',
				'onpause',
				'onpointer',
				'onpopstate',
				'onprogress',
				'onpropertychange',
				'onreadystatechange',
				'onredo',
				'onrepeat',
				'onreset',
				'onresize',
				'onresume',
				'onreverse',
				'onrow',
				'onscroll',
				'onseek',
				'onsearch',
				'onselect',
				'onstart',
				'onshow',
				'onstop',
				'onstorage',
				'onsyncrestored',
				'onsubmit',
				'ontimeerror',
				'ontoggle',
				'ontouch',
				'ontrackchange',
				'onunder', // Where is this specified? 2017-06-19
				'onundo',
				'onunload',
				'onurlflip',
				'onwheel',
				'onpopup',
				'codebase',
				'dynsrc',
				'lowsrc',
				'xmlns'
			];
			$ra_as_content = ['vbscript', 'eval'];
			$ra_javascript = ['javascript'];
	///		$ra_style = array('style'); // Commented as it has been considered as a bit too aggressive
		}
		// keep replacing as long as the previous round replaced something
		while ($this->RemoveXSSchars($val)
			|| $this->RemoveXSSregexp($ra_as_tag_only, $val, '(\<|\[\\\\xC0\]\[\\\\xBC\])\??')
			|| $this->RemoveXSSregexp($ra_as_attribute, $val, '\s')
			|| $this->RemoveXSSregexp($ra_as_content, $val, '[\.\\\\+\*\?\[\^\]\$\(\)\{\}\=\!\<\|\:;\-\/`#"\']', '(?!\s*[a-z0-9])', true)
			|| $this->RemoveXSSregexp($ra_javascript, $val, '', ':', true)
	///		|| RemoveXSSregexp($ra_style, $val, '[^a-z0-9]', '=') // Commented as it has been considered as a bit too aggressive
		) {
		}
		return $val;
	}
	function RemoveXSSchars(&$val)
	{
		static $patterns = null;
		static $replacements = null;
		$val_before = $val;
		$found = true;
		if ($patterns == null) {
			$patterns = [];
			$replacements = [];
			// remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are
			// allowed this prevents some character re-spacing such as <java\0script>
			// note that you have to handle splits with \n, \r, and \t later since they
			// *are* allowed in some inputs
			$patterns[] = '/([\x00-\x08\x0b-\x0c\x0e-\x19])/';
			$replacements[] = '';
			// straight replacements, the user should never need these since they're
			// normal characters this prevents like
			// <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
			// Calculate the search and replace patterns only once
			$search = 'abcdefghijklmnopqrstuvwxyz';
			$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$search .= '1234567890!@#$%^&*()';
			$search .= '~`";:?+/={}[]-_|\'\\';
			for ($i = 0, $istrlen_search = strlen($search); $i < $istrlen_search; $i++) {
				// ;? matches the ;, which is optional
				// 0* matches any padded zeros, which are optional
				// &#x0040 @ search for the hex values
				$patterns[] = '/(&#x0*' . dechex(ord($search[$i])) . ';?)/i';
				$replacements[] = $search[$i];
				// &#00064 @ 0* matches padded zeros
				// with a ;
				$patterns[] = '/(&#0*' . ord($search[$i]) . ';?)/';
				$replacements[] = $search[$i];
			}
		}
		$val = preg_replace($patterns, $replacements, $val);
		if ($val_before == $val) {
			// no replacements were made, so exit the loop
			$found = false;
		}
		return $found;
	}
}
