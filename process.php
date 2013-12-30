<?php

	/**
	 * process.php 
	 *
	 * Image processing script for simplecat
	 * Author: Ivo Janssen
	 */
	 
	// Get config
	require_once('config.php');

	$validTypes = array('png', 'gif', 'jpg', 'jpeg');
	
	print "Processing images...\n";
	
	// Open directory
	$sourceDir = opendir(SOURCE_PATH);
	// Loop through files and determine page number
	while(false !== ($sourceFile = readdir($sourceDir))) {

		try {
			// Make sure it's a valid image file
			$sourcePath = SOURCE_PATH . $sourceFile;
			$extension = pathinfo($sourcePath, PATHINFO_EXTENSION);
			if (!is_file($sourcePath) || !in_array($extension, $validTypes)) continue;
	
			// Check for number in the file name
			if (preg_match("%^(\d+)|(\d+)\.%", $sourceFile, $matches)) $pageNumber = intval($matches[1]) + 1;
			else $pageNumber = MAX_PAGES;
			
			print "Copying page " . $pageNumber . "\n";
			
			// Re-save a compressed version of the original
			$sourceImage = openImage($sourcePath);
			$result = imagejpeg($sourceImage, IMAGE_PATH . "page" . $pageNumber . "-big.jpg", LARGE_QUALITY);
			
			print "Resizing page " . $pageNumber . "\n";
			
			// Save a resized version as well
			$pageImage = imagecreatetruecolor(PAGE_WIDTH, PAGE_HEIGHT);
			imagecopyresampled($pageImage, $sourceImage, 0, 0, 0, 0, PAGE_WIDTH, PAGE_HEIGHT, imagesx($sourceImage), imagesy($sourceImage));
			$result = imagejpeg($pageImage, IMAGE_PATH . "page" . $pageNumber . ".jpg", RESIZED_QUALITY);
		}
		catch(Exception $e) {
			print "Error: " . $e->getMessage();
		}

	}
	
	print "Done!\n";
	
	// Open image using GD
	function openImage($sourcePath) {
		$extension = pathinfo($sourcePath, PATHINFO_EXTENSION);
		// Open original
		switch($extension) {
			case 'jpg':
			case 'jpeg':
				$sourceImage = imagecreatefromjpeg($sourcePath);
				break;
			case 'png':
				$sourceImage = imagecreatefrompng($sourcePath);
				break;
			case 'gif':
				$sourceImage = imagecreatefromgif($sourcePath);
				break;
		}
		if (!$sourceImage) throw new Exception("Not a valid image: " . $sourcePath);
		return $sourceImage;
	}
	
?>