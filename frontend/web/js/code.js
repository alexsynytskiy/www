(function($){
    $(window).load(function() {
        // BxSlider START
        $('.top-news-slider ul').bxSlider({
            slideWidth: 300,
            slideHeight: 335,
            maxSlides: 1,
            minSlides: 1,
            slideMargin: 0
        });

        $('.top-matches-slider ul').bxSlider({
            slideWidth: 300,
            slideHeight: 80,
            maxSlides: 1,
            minSlides: 1,
            slideMargin: 0
        });

        $('.video-report ul.video-list, .photo-report ul.photo-list').bxSlider({
            slideWidth: 290,
            slideHeight: 240,
            maxSlides: 1,
            minSlides: 1,
            slideMargin: 0
        });
        // BxSlider END


        // Hover functions for matches slider on top of 2nd column at main page START
        $('.top-matches-slider .bx-prev, .top-matches-slider .bx-next').hover( function(){
           $(this).addClass("bx-control-hover");
        },
        function(){
           $(this).removeClass("bx-control-hover");
        });
        // Hover functions for matches slider on top of 2nd column at main page END


        // Masonry tiles view START
        $('.inquirers-container').indyMasonry({
              'clName'    : '.inquirer',
              'gap'       : 15,
              'mTop'      : 0,
              'mBottom'   : 15,
              'column'    : 2,
        });
        $('.additional-data').indyMasonry({
              'clName'    : '.data-box',
              'gap'       : 15,
              'mTop'      : 0,
              'mBottom'   : 15,
              'column'    : 2,
        });
        // Masonry tiles view END


        // Smooth scroll to some elements START
        $('#table-translation-link').click(function(){
            var target = $(this).attr('href');
            $('html, body').animate({
                scrollTop: $(target).offset().top
            }, 800);
            return false;
        });

         $('#text-translation-link').click(function(){
            var target = $(this).attr('href');
            $('html, body').animate({
                scrollTop: $(target).offset().top
            }, 800);
            return false;
        });
        // Smooth scroll to some elements END
    });

$(document).ready(function() {

    // iCheck START
	$('input').iCheck({
		checkboxClass: 'icheckbox_flat-blue',
		radioClass: 'iradio_flat-blue'
	});
    // iCheck END


    // Autoresize textarea START
    $('#comment-form textarea').autoResize();
    // Autoresize textarea START


	// Hover functions for header social buttons, rss and inform buttons START
	$('.block-top .button, .social-buttons .button').hover( function(){
	   $(this).addClass("button-hover");
	}, function(){
	   $(this).removeClass("button-hover");
	});

	$(".upload-button .field-label").click(function () {
	    $(this).parent().find(':file').first().trigger('click');
	});
	$('.upload-button').hover( function(){
	   $(this).addClass("hover");
	},
	function(){
	   $(this).removeClass("hover");
	});
    // Hover functions for header social buttons, rss and inform buttons END


    // Toggle comments START
    $('.toggle-button').click(function(){
        if($(this).hasClass('toggle-show')) {
            $(this).removeClass('toggle-show');
            $(this).addClass('toggle-hide');
            $(this).find('.toggle-text span').first().text("Скрыть");
            $('#' + $(this).attr('data-target')).slideToggle(300);
        } else {
            $(this).removeClass('toggle-hide');
            $(this).addClass('toggle-show');
            $(this).find('.toggle-text span').first().text("Показать");
            $('#' + $(this).attr('data-target')).slideToggle(300);
        }
    });

    $('.replies-toggle-btn.toggle-show').click(function(){
        var $el = $('#' + $(this).attr('data-target')).parent().parent().find('.new-replies-count')
        $el.text('');
        $el.addClass('no-replies');
    });
    // Toggle comments END
    

    $('.comments-block .button-reply').click(function(){
        var commentId = $(this).attr('data-comment-id');
        var commentUserName = $(this).parents('.comment').first().find('.user-name a').first().text();
        var target = $('.comments-block');
        
        $('#comment-form #comment-parent_id').val(commentId);
        $('#comment-form .reply-data .user').text(commentUserName);
        $('#comment-form .reply-data').show();

        $('#comment-form #comment-content').text(commentUserName + ', ');
        $('html,body').animate({
          scrollTop: target.offset().top
        }, 500);
        $('#comment-form #comment-content').focus();
    });

    $('.comments-block .button-cancel').click(function(){
        $('#comment-form #comment-parent_id').val('');
        $('#comment-form .reply-data').hide();
    });


    // => Calendar START
    var $datepicker = $('.calendar .content').datepick({
        dateFormat: 'dd.mm.yyyy', // Format for dates, defaults to calendar setting if null
        defaultDate: new Date(2015, 1 - 1, 1),
        maxDate: +0,
        fixedWeeks: true, // True to always show 6 weeks, false to only show as many as are needed
        firstDay: 1, // First day of the week, 0 = Sunday, 1 = Monday, ...
        changeMonth: false, // True to change month/year via drop-down, false for navigation only
        useMouseWheel: false,
        monthsToShow: [4,3],
    });

    $('.calendar .o-year').click(function() {
        var date = new Date($(this).text(), 1 - 1, 1);
        $('.calendar .content').datepick('setDate', date);
        $('.calendar .datepick-month.selected').removeClass('selected');
        $('.calendar .current-year div').text($(this).text());
        $('.calendar .o-year.active').removeClass('active');
        $(this).addClass('active');
    });
    
    if(typeof(calendarDate)!=='undefined') {
        var date = new Date(calendarDate);
        $('.calendar .content').datepick('setDate', date);
        $('.calendar .datepick-month.selected').removeClass('selected');
        $('.calendar .current-year div').text($(this).text());
        $('.calendar .o-year.active').removeClass('active');
        console.log(calendarDate);
        var year = date.getFullYear();
        $('.calendar .current-year div').text(year);
        $.each($('.calendar .o-year'),function(){
            if($(this).text() == year) {
                $(this).addClass('active');
            }
        });
    }

    $('.calendar').on('click', '.cancel-btn', function(){
        $('.datepick-month.selected').removeClass('selected');
    });
    // => Calendar END


    // => Selectize START
    $('.selectize-box select').selectize({
        hideSelected: true,
        item: function(data) {
            return "<div data-value='"+data.value+"' data-default='"+data.type+"' class='item'>"+data.label+" </div>";
        }
    });
    // => Selectize END


    // => Alert tick START
    function alertTick() {
        second--;
        if(second > 0) {
            $('.alert .sec').text(second);
            setTimeout(alertTick, 1000);
        } else {
            $('.alert').parents('.default-box').slideToggle(300);
        }
    }
    if($('.default-box .alert').length > 0) {
        var second = 11;
        alertTick();
    }
    // => Alert tick END
    

    // => User image preview on register page START
    function saveCoords(c) {
        var x = c.x < 0 ? 0 : c.x/c.imageWidth;
        var y = c.y < 0 ? 0 : c.y/c.imageHeight;

        var x2 = c.x2 < 0 ? 1 : c.x2/c.imageWidth;
        var y2 = c.y2 < 0 ? 1 : c.y2/c.imageHeight;

        var w = c.x < 0 ? x2 : c.x2 < 0 ? 1 - x : c.w/c.imageWidth;
        var h = c.y < 0 ? y2 : c.y2 < 0 ? 1 - y : c.h/c.imageHeight;

        var params = [x,y,w,h];
        $('#crop-data').val(params.join(';'));
    }

    function readURL(input, previewBox) {
        previewBox.html('');
        if (input.files && input.files[0]) {
            if(input.files[0].type == "image/jpeg" || 
                input.files[0].type == "image/png" ||
                input.files[0].type == "image/gif") {
                var reader = new FileReader();
                reader.onload = function (e) {
                    previewBox.html('<img src="' + e.target.result + '" alt="preview image" />');
                    $image = $('#register-form .preview-image img');
                    var height = $image.height();
                    var width = $image.width();
                    var maxSize = height < width ? height : width;
                    $image.Jcrop({
                        aspectRatio: 1,
                        setSelect: [
                            width > maxSize ? (width-maxSize)/2 : 0,
                            height > maxSize ? (height-maxSize)/2 : 0,
                            maxSize,
                            maxSize
                        ],
                        minSize: [100, 100],
                        onSelect: saveCoords,
                        onChange: saveCoords,
                    });
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    }

    $("#register-form :file").change(function(){
        readURL(this, $('#register-form .preview-image'));
    });
    // => User image preview on register page END


  });
})(jQuery);