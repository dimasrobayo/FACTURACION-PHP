<?php
/**
* @package   yoo_enterprise Template
* @file      cache.php
* @version   1.5.0 March 2010
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
*/

/*
	Class: YOOCache
		Provides compression & caching for JDocument.
*/
class YOOCache {

	var $document;
	var $source;
	var $files;
	var $rules;
	var $cachetime;
	var $cachepath;
	var $rootpath;
	var $uri;

	function YOOCache(&$document) {
		
		// import filesystem lib
		jimport('joomla.filesystem.file');

		// get config
		$conf =& JFactory::getConfig();

		$this->document =& $document;
		$this->files = array();
		$this->rules = array();
		$this->cachetime = $conf->getValue('config.cachetime') * 60;
		$this->cachepath = JPATH_CACHE.'/template/';
		$this->rootpath = JPATH_ROOT;
		$this->uri = JURI::base(true);
	}

	function addRule($rule) {
		$this->rules[] = $rule;
	}

	function addFile($file) {
		$files[$file] = $this->rootpath.'/'.$file;
	}

	function getId($files) {
		return md5($this->document->template.implode(';', array_keys($files)));
	}	

	function getName($id) {
		return null;
	}	

	function getFiles() {
		$files = array();

		foreach ($this->files as $file => $filepath) {
			
			// match rules, if set
			if (count($this->rules)) {
				$match = false;

				foreach ($this->rules as $rule) {
					if ($match = $rule->match($file)) {
						break;
					}
				}
				
				if (!$match) {
					continue;
				}
			}

			// check if file exists
			if (JFile::exists($filepath)) {
				$files[$file] = $filepath;
			}
		}
		
		return $files;
	}

	function buildCache($files, $cachefile) {

		// init vars
		$data = '';

		// get file data
		foreach ($files as $file => $filepath) {
			$data .= file_get_contents($filepath)."\n\n"; 
		}

	    // create cache file
	    return JFile::write($cachefile, $data);
	}	

	function process($gzip = false) {

		// init vars
		$caching   = false;
		$files     = $this->getFiles();
		$name      = $this->getName($this->getId($files), $gzip);
		$type      = $gzip ? 'gzip' : 'cache';
		$cachefile = $this->cachepath.'/'.$name['file'];
		$headdata  = $this->document->getHeadData();
		
		// process files and check/create cache
		if (JFile::exists($cachefile) && (time() - filemtime($cachefile)) < $this->cachetime) {
			$caching = true; // set caching true, cache file already exists
		} else if (count($files)) {
			$caching = $this->buildCache($files, $cachefile); // build cache file
		}

		// add cached files to document
		if ($caching) {

			$added  = false;
			$source = array();
			$cached = array_keys($files);

			// add cache file by keeping the ordering
			foreach ($headdata[$this->source] as $file => $attr) {
				if (!in_array($file, $cached)) {
					$source[$file] = $attr;
				} else if (!$added) {
					$added = true;
					$source = array_merge($source, $name[$type]);
				}
			}

			$headdata[$this->source] = $source;
			$this->document->setHeadData($headdata);
		}

	}

}

/*
	Class: YOOCacheStylesheet
		Provides Stylesheet compression & caching for JDocument.
*/
class YOOCacheStylesheet extends YOOCache {

	function YOOCacheStylesheet(&$document) {
		parent::YOOCache($document);

		// init vars
		$this->source = 'styleSheets';
		$headdata     = $document->getHeadData();
		
		// only add non print stylesheet files
		$pattern = '/^.*'.str_replace('/', '\/', $this->uri).'(.*)/';
		foreach ($headdata[$this->source] as $file => $attr) {
			if ($attr['media'] == 'print' || !preg_match('/^.*(\.css)$/i', $file)) {
				continue;
			}

			if ($this->uri && preg_match($pattern, $file, $matches) > 0) {
				$filepath = $this->rootpath.$matches[1];
			} else {
				$filepath = $this->rootpath.$file;
			}

			$this->files[$file] = $filepath;
		}
	}

	function getName($id) {

		$name['file']  = 'css-'.$id.'.css';
		$name['cache'] = array($this->uri.'/cache/template/'.$name['file'] => array('mime' => 'text/css', 'media'=> null, 'attribs' => array()));
		$name['gzip']  = array($this->uri.'/templates/'.$this->document->template.'/lib/gzip/css.php?id='.$id => array('mime' => 'text/css', 'media'=> null, 'attribs' => array()));
		
		return $name;
	}	

	function buildCache($files, $cachefile) {

		// init vars
		$data  = '';

		// get stylesheet data
		foreach ($files as $file => $filepath) {
			$contents = YOOCacheStylesheet::loadFile($filepath, false);

			// return the path to where this CSS file originated from
			YOOCacheStylesheet::buildPath(null, dirname($file).'/');

			// prefix all paths within this CSS file, ignoring external and absolute paths
			$data .= preg_replace_callback('/url\([\'"]?(?![a-z]+:|\/+)([^\'")]+)[\'"]?\)/i', array('YOOCacheStylesheet', 'buildPath'), $contents);
		}

	    // move @import rules to the top
	    $regexp = '/@import[^;]+;/i';
	    preg_match_all($regexp, $data, $matches);
	    $data = preg_replace($regexp, '', $data);
	    $data = implode('', $matches[0]) . $data;

	    // create cache file
	    return JFile::write($cachefile, $data);
	}

	function buildPath($matches, $base = null) {
		static $_base;

		// store base path for preg_replace_callback
		if (isset($base)) {
			$_base = $base;
		}

		// prefix with base and remove '../' segments where possible
		$path = $_base . $matches[1];

		$last = '';
		while ($path != $last) {
			$last = $path;
			$path = preg_replace('`(^|/)(?!\.\./)([^/]+)/\.\./`', '$1', $path);
		}

		return 'url('. $path .')';
	}

	function loadFile($file) {
		$contents = null;

		if (file_exists($file)) {
			// load the local CSS stylesheet
			$contents = file_get_contents($file); 

			// change to the current stylesheet's directory
			$cwd = getcwd();
			chdir(dirname($file));

			// replaces @import commands with the actual stylesheet content
			// this happens recursively but omits external files
			$contents = preg_replace_callback('/@import\s*(?:url\()?[\'"]?(?![a-z]+:)([^\'"\()]+)[\'"]?\)?;/', array('YOOCacheStylesheet', 'loadFileRecursive'), $contents);

			// remove multiple charset declarations for standards compliance (and fixing Safari problems)
			$contents = preg_replace('/^@charset\s+[\'"](\S*)\b[\'"];/i', '', $contents);

			// change back directory.
			chdir($cwd);
		}

		return $contents;
	}

	function loadFileRecursive($matches) {
		$filename = $matches[1];

		// load the imported stylesheet and replace @import commands in there as well
		$file = YOOCacheStylesheet::loadFile($filename);

		// if not current directory, alter all url() paths, but not external
		if (dirname($filename) != '.') {
			$file = preg_replace('/url\([\'"]?(?![a-z]+:|\/+)([^\'")]+)[\'"]?\)/i', 'url('.dirname($filename).'/\1)', $file);	
		}
		
		return $file;
	}	

}

/*
	Class: YOOCacheScript
		Provides Javascript compression & caching for JDocument.
*/
class YOOCacheScript extends YOOCache {
	
	function YOOCacheScript(&$document) {
		parent::YOOCache($document);

		// init vars
		$this->source = 'scripts';
		$headdata     = $document->getHeadData();
	
		// add script files
		$pattern = '/^.*'.str_replace('/', '\/', $this->uri).'(.*)/';
		foreach ($headdata[$this->source] as $file => $attr) {
			if (!preg_match('/^.*(\.js)$/i', $file)) {
				continue;
			}

			if ($this->uri && preg_match($pattern, $file, $matches) > 0) {
				$filepath = $this->rootpath.$matches[1];
			} else {
				$filepath = $this->rootpath.$file;
			}

			$this->files[$file] = $filepath;
		}
	}

	function getName($id) {

		$name['file']  = 'js-'.$id.'.js';
		$name['cache'] = array($this->uri.'/cache/template/'.$name['file'] => 'text/javascript');
		$name['gzip']  = array($this->uri.'/templates/'.$this->document->template.'/lib/gzip/js.php?id='.$id => 'text/javascript');
		
		return $name;
	}	

}

/*
	Class: YOOCacheRule
		Provides default include rules for YOOCache.
*/
class YOOCacheRule {

	var $pattern;

	function YOOCacheRule($pattern) {
		$this->pattern = $pattern;
	}
	
	function match($subject) {
		return preg_match($this->pattern, $subject);
	}

}