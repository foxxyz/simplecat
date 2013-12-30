<?php

	/**
	 * config.php 
	 *
	 * Configuration variables for simplecat
	 * Author: Ivo Janssen
	 */
	 
	// Paths
	define('SOURCE_PATH', dirname(__FILE__) . "/source-images/");
	define('IMAGE_PATH', dirname(__FILE__) . "/images/");
	
	// Quality settings
	//
	// These settings range from 1-100, 
	// 	where 100 is better quality, largest filesize
	//	and 1 is worst quality, smallest filesize)
	define('LARGE_QUALITY', 50); # Quality of the large version when clicked through
	define('RESIZED_QUALITY', 75); # Quality of the version used in the catalog
	
	// Size of pages in px
	define('PAGE_WIDTH', 1000);
	define('PAGE_HEIGHT', 650);
	define('PAGE_WIDTH_FULL', 2000);
	define('PAGE_HEIGHT_FULL', 1300);
	
	// Number of pages
	define('MAX_PAGES', 41);
	
?>