// Scroll to top button
$(document).ready(function(){
	$(window).scroll(function () {
		if ($(this).scrollTop() > 50) {
			$('.btn-to-top').fadeIn();
		} else {
			$('.btn-to-top').fadeOut();
		}
	});

	// scroll body to 0px on click
	$('.btn-to-top').click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 400);
		return false;
	});
});

// Add bootstrap table class to all tables
$(document).ready(function(){
	$('#content').find('table').addClass('table');
});

// Tooltips
$(document).ready(function(){
	$('.footnote-ref').tooltip({
		html: true,
		title: function() {
			var target = $(this).attr('href').replace(':', '\\:');
			var content = $(target).html();
			var $content = $(content);

			$content.find('.footnote-backref').remove();
			
			return $content.html().trim();
		},
	});

	$('abbr').tooltip({
		html: true,
		template: '<div class="tooltip abbr-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
	});
});

// Hide footnotes
$(document).ready(function(){
	// Add toggle footnotes button
	$('.footnotes').each(function () {
		var $footnotes = $(this);
		$footnotes.attr('id', 'footnotes');
		$footnotes.addClass('collapse');
		
		var $button = $('<a id="footnotes-toggle" href="#" data-target="#footnotes" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="footnotes">Show footnotes <i class="fas fa-chevron-right"></i></a>');
		$footnotes.before($button);
	});

	$('#footnotes').on('hide.bs.collapse', function () {
		$('#footnotes-toggle').html('Show footnotes <i class="fas fa-chevron-right"></i>');

		// Remove style if added when clicking footnote
		$('#footnotes').attr('style', '');
	});

	$('#footnotes').on('show.bs.collapse', function () {		
		$('#footnotes-toggle').html('Hide footnotes <i class="fas fa-chevron-down"></i>');
	});

	// If a footnote is clicked we need to make sure to first expand the footnotes
	$('.footnote-ref').click(function(e) {
		$('#footnotes').css('transition', 'none');
		$('#footnotes').collapse('show');
	});
});

// Smooth scroll all anchors
$(document).ready(function() {
	$('a[href^="#"]').on('click', function(e) {
		var target = $(this).attr('href').replace(':', '\\:');
		var $target = $(target);

		if ($target.length) {
			e.preventDefault();

			var offset = $target.offset().top;
			offset -= $('#menu').height();
			offset -= 8; // same as $spacer
			
			$('html, body').stop().animate({
				scrollTop: offset,
			}, 400);

			return false;
		}
	});
});

// Bind galleries to open with Photoswipe
$(document).ready(function() {
    $('a[data-gallery]').on('click', function(e) {
		e.preventDefault();

		var items = [];
		var pswpElement = document.querySelectorAll('.pswp')[0];
		var $gallery = $(this).closest('div[data-gallery-shortcode]');

		$gallery.find('[data-gallery]').each(function(i) {
			var url = $(this).attr('href');
			var size = $(this).attr('data-size').split("x");
			
			items.push({
				src: url,
				w: size[0],
				h: size[1]
			});
		});

        var pswp = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, {
			bgOpacity: 0.7,
			index: $(this).index(),
        });
        
        pswp.init();
    });
});