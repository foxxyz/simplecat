<?php

	/**
	 * index.php 
	 *
	 * Main simplecat catalog display
	 * Author: Ivo Janssen
	 */

	// Get config
	require_once('config.php');

	// Set page
	if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] <= MAX_PAGES) $page = $_GET["page"] - 1;
	else $page = 0;
 
	// Variables
	$title = "Spring 2014";
	$titleToken = "spring-14";
	$catURL = "http://" . $_SERVER["SERVER_NAME"] . reset(explode("?", $_SERVER["REQUEST_URI"])) . "?";
	
	// Classes for enhanced/regular version
	$classes = array();
	if ($page == 0) $classes[] = "start";
	if (isset($_GET["enhanced"])) {
		if ($_GET["enhanced"] == "true") $classes[] = "enhanced";
		else $classes[] = "normal";
		$classes[] = "forced";
		$catURL .= "enhanced=" . $_GET["enhanced"];
	}

?><!DOCTYPE html>
<html>

	<head>
		<title><?= $title ?></title>
		<meta charset="utf-8" />
		<meta name="robots" content="noindex" />
		<meta name="author" content="Ivo Janssen, Code Dealers" />
		<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="js/modernizr.js"></script>
		<script type="text/javascript" src="js/jquery.touchSwipe.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
		<script type="text/javascript">
			var currentPage = <?= $page ?>;
			var numPages = <?= MAX_PAGES - 1 ?>;
			var pageWidth = <?= PAGE_WIDTH ?>;
			var pageHeight = <?= PAGE_HEIGHT ?>;	
		</script>
	</head>
	<body<?= $classes ? " class=\"" . implode(" ", $classes) . "\"" : null ?>>
		<div id="wrapper">
			<h1><a target="_parent" href="<?= $catURL ?>"><?= $title ?></a></h1>
			
			<div id="catalog">
				<?php
				for($pageOffset = -2; $pageOffset <= 3; $pageOffset++) {
					if ($pageOffset <= 0 && $page <= 0 - $pageOffset) continue;
					if ($pageOffset > 0 && $page > (MAX_PAGES - $pageOffset)) continue;
					?>
					<div class="page<?= $pageOffset <= 0 ? " flipped" : null ?>">
						<div>
							<a href="images/page<?= $page + $pageOffset ?>-big.jpg"><img src="images/page<?= $page + $pageOffset ?>.jpg" width="<?= PAGE_WIDTH ?>" height="<?= PAGE_HEIGHT ?>" alt="Page<?= $page + $pageOffset ?>" /></a>
						</div>
					</div>
					<?php
				}
				?>
				<div id="navigation">
					<span title="Previous Page" class="prev"<?= $page == 0 ? " style=\"display:none;\"" : null ?>>&#8678;</span>
					<span title="Next Page" class="next"<?= $page == MAX_PAGES - 1 ? " style=\"display:none;\"" : null ?>>&#8680;</span>
				</div>
			</div>
			
		</div>
		
	</body>
</html>