/*
BBD Hyphy Catalog Javascript v2

URL: 			http://bakerboysdist.com/catalogs/
Created by: 	Ivo KH Janssen <ivo@codedealers.com>
For:			Code Dealers
Site Design by:	Code Dealers

http://codedealers.com

*/

// Amount of pages to buffer after the current ones
pageBuffer = 2;
topIndex = 90;

// Starting animation
var startAnimation;

// Create new page
function createPage(pageNum, flipped) {
	page = $("<div class=\"page\" style=\"margin-left: -" + Math.round(pageWidth / 2) + "px; width: " + pageWidth + "px; height: " + pageHeight + "px;\">" +
		"<div><a href=\"images/page" + pageNum + "-big.jpg\"><img src=\"images/page" + pageNum + ".jpg\" width=\"" + pageWidth + "\" height=\"" + pageHeight + "\" alt=\"Page" + pageNum + "\" /></a></div>" + 
	"</div>");
	// Swipe events for mobile
	page.swipe({
		swipeLeft: nextPage,
		swipeRight: prevPage,
		tap: function(event, target) {
			$(target).parent().click();
		},
		threshold:0,
		excludedElements:'',
	});
	if (flipped) page.addClass('flipped');
	return page;
}

// Cull pages beyond the buffering range for optimization
function cull() {
	activePage = $('.page:not(.flipped):first');
	beyondBuffer = activePage.nextAll('.page').slice(pageBuffer);
	beyondBuffer.remove();
	beforeBuffer = activePage.prevAll('.page').slice(pageBuffer);
	beforeBuffer.remove();
}

// Go to next page
function nextPage() {
	
	// Return if past end
	if (currentPage >= numPages) return false;
	
	// Starting animation
	if ($("body").hasClass("start")) {
		$("body").removeClass("start");
		$(".next").removeClass("active");
		clearInterval(startAnimation);
	}
	
	// Track current page
	currentPage++;
	
	// Make sure pages are stacked correctly
	restack();
	
	// Flip page
	$(".page:not(.flipped):first").addClass("flipped");
	
	// Load new pages if necessary
	bufferLength = $(".page:not(.flipped)").length;
	if (bufferLength < pageBuffer) {
		for(page = currentPage + bufferLength; page < currentPage + pageBuffer && page <= numPages; page++) {
			$(".page:last").after(createPage(page + 1), false);
		}
	}

	updateNavigation();	
	cull();
	
}

// Go to previous page
function prevPage() {
	
	// Return if at beginning
	if (currentPage == 0) return false;
	
	// Track current page
	currentPage--;
	
	// Unflip page
	$(".flipped:last").removeClass("flipped");
	
	restack();
	
	// Load new pages if necessary
	bufferLength = $(".page.flipped").length;
	if (bufferLength < pageBuffer) {
		for(page = currentPage - bufferLength; page > currentPage - pageBuffer && page >= 0; page--) {
			$(".page:first").before(createPage(page, true));
		}
	}
	
	updateNavigation();	
	cull();
		
}

// Resize layout to keep aspect ratio
function resize() {
		
	// Get catalog ratio
	catalogRatio = $(".page img").attr('height') / $(".page img").attr('width');
	viewportRatio = $(window).height() / $(window).width();
	
	// For landscape overflow, set the catalog to the viewport height
	if (catalogRatio > viewportRatio) {
		pageHeight = $(window).height();
		pageWidth = Math.round(pageHeight / catalogRatio);
	}
	// For portrait overflow (or exact ratio match), set the catalog to the viewport width
	else {
		pageWidth = $(window).width();
		pageHeight = Math.round(pageWidth * catalogRatio);
	}
	
	// Set on page
	$(".page").height(pageHeight).width(pageWidth)
		.css("margin-left", "-" + Math.round(pageWidth / 2) + "px");

	// Make sure catalog is properly centered
	marginSpace = Math.round(($(window).height() / 2) - (pageHeight / 2));
	if (marginSpace < 30) $("h1").addClass("hidden");
	else $("h1").removeClass("hidden");
	if (marginSpace < 90) $("footer").addClass("hidden");
	else $("footer").removeClass("hidden");
	$("#catalog").height(pageHeight).css("margin-top", marginSpace + "px");
	// SPICELLY FOR UR GOOD OL FRIND ONTERNAT EXPLURER
	$("#wrapper").css("margin-top", marginSpace + "px");
	
	restack();
	
}

// Make sure pages are stacked correctly
function restack() {
	$(".page:not(.flipped):first").css("z-index", topIndex)
		.nextAll().each(function(index, el) {
			$(el).css("z-index", topIndex - index - 1)
		});
}

// Show navigation based on current page
function updateNavigation() {
	if (currentPage <= 0) $(".prev").hide();
	else $(".prev").show();
	if (currentPage >= numPages) $(".next").hide();
	else $(".next").show();	
}

$(function(){
	
	// Resize layout
	resize();
	$(window).resize(resize);
	
	// Check for enhanced or normal version
	if (!$("body").hasClass("forced")) {
		if (Modernizr.csstransforms3d) $("body").addClass("enhanced");
		else $("body").addClass("normal");
	}
	
	// Footer hide/show
	$("footer").click(function() {
		$(this).toggleClass("show");
	});
	
	// Swipe events for mobile
	$(".page").swipe({
		swipeLeft: nextPage,
		swipeRight: prevPage,
		tap: function(event, target) {
			$(target).parent().click();
		},
		threshold:0,
		excludedElements:'',
	});
	
	// Open bigger versions in new window/tab
	$("#catalog").on('click', '.page a', function(event) {
		window.open($(this).attr("href"), "bigger");
		event.preventDefault();
		event.stopPropagation();
	})
	// Navigation
	.on('mousedown', '.prev', prevPage)
	.on('mousedown', '.next', nextPage);
		
	// Starting animation
	startAnimation = setInterval(function() {
		$(".start .next").toggleClass("active");
	}, 100);
		
});