(function($){
    $(window).load(function() {

        // PreLoading page animation turn off START
        $("#loading").delay(1000).fadeOut(500);
        // PreLoading page animation turn off END


        // BxSlider START
        $('.top-news-slider .slider').bxSlider({
            slideWidth: 300,
            preloadImages: 'all',
            maxSlides: 1,
            minSlides: 1,
            slideMargin: 0            
        });

        $('.top-matches-slider ul').bxSlider({
            slideWidth: 300,
            slideHeight: 80,
            maxSlides: 1,
            minSlides: 1,
            slideMargin: 0,
            infiniteLoop: false,
            startSlide:4,
        });

        $('.video-report .video-list, .photo-report .photo-list').bxSlider({
            slideWidth: 290,
            preloadImages: 'all',
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
              'clName'    : '.item',
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
    autosize($('#comment-form textarea'));
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
    $(document).on('click', '.toggle-button', function(event) {
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

    $(document).on('click', '.replies-toggle-btn.toggle-show', function(){
        var $el = $('#' + $(this).attr('data-target')).parent().parent().find('.new-replies-count')
        $el.text('');
        $el.addClass('no-replies');
    });
    // Toggle comments END
    

    // => Comments form START
    $('#comment-form').on('beforeSubmit', function () {
        $.ajax({
            url: $(this).attr('action'),
            type: 'post',
            dataType: 'json',
            data: $(this).serialize(),
            success: function(data) {
                if(data.success) {
                    var parent_id = $('#commentform-parent_id').val();
                    target = parent_id == '' ? $('#comments-container') : $('#comment-' + parent_id);
                    $('#commentform-content').val('');
                    $('#commentform-parent_id').val('');
                    $('#comment-form .reply-data').hide();
                    if($('.cabinet-comments').length > 0) $('#comment-form').slideUp(500);
                    $.pjax.reload({container:'#comments-container'});
                }
            }
        });
        return false;
    });

    $("#comments-container").on("pjax:end", function() {
        $.ias().reinitialize();
    });

    function replyButtonHandle($btn, $target){
        var $comment = $btn.parents('.comment').first();
        var commentId = $comment.attr('data-comment-id');
        var own = $comment.attr('data-own');
        var commentUserName = $comment.find('.user-name a').first().text().trim();
        
        $('#commentform-parent_id').val(commentId);
        $('#comment-form .reply-data .user').text(commentUserName);
        $('#comment-form .reply-data').show();

        if(own == 'no') {
            $('#commentform-content').text(commentUserName + ', ');
        } else {
            $('#commentform-content').text('');
        }
        $('html,body').animate({
            scrollTop: $target.offset().top
        }, 500);
        $('#commentform-content').focus();
    }

    // Comments on post page
    $(document).on('click', '.comments-block .button-reply', function(){
        replyButtonHandle($(this), $('.comments-block'));
    });
    $(document).on('click', '.comments-block .button-cancel', function(){
        $('#commentform-parent_id').val('');
        $('#comment-form .reply-data').hide();
    });

    // Comments on profile page
    $(document).on('click', '.cabinet-comments .button-reply', function(){
        var $comment = $(this).parents('.comment').first();
        if($('#comment-form').css('display') == 'none') {
            $('#comment-form').slideDown(500);
        }
        $('#commentform-commentable_type').val($comment.attr('data-commentable-type'));
        $('#commentform-commentable_id').val($comment.attr('data-commentable-id'));
        replyButtonHandle($(this), $('.cabinet-comments'));
    });
    $(document).on('click', '.cabinet-comments .button-cancel', function(){
        $('#comment-form').slideUp(500);
        $('#commentform-commentable_id').val('');
        $('#commentform-commentable_type').val('');
        $('#commentform-parent_id').val('');
        $('#comment-form .reply-data').hide();
    });
    // => Comments form END


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

    $(document).on("change", "#select-season", function(){
        $(this).parents("form").first().submit();
    });

    $(document).on("change", "#select-championship", function(){
        $(this).parents("form").first().submit();
    });

    $(document).on("change", "#select-transfer-type", function(){
        $(this).parents("form").first().submit();
    });

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
    

    // => User image preview on register and settings page START
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

    function readURL(input, $previewBox) {
        $previewBox.html('');
        if (input.files && input.files[0]) {
            if(input.files[0].type == "image/jpeg" || 
                input.files[0].type == "image/png" ||
                input.files[0].type == "image/gif") {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $previewBox.html('<img src="' + e.target.result + '" alt="preview image" />');
                    var $image = $(input).parents('form').find('.preview-image img').first();
                    var $form = $previewBox.parents('form').first();
                    // If the image bigger than form box
                    if($form.length > 0 && $image.width() > $form.width())
                    {
                        $image.width($form.width());
                        $previewBox.css('margin', 0);
                    } else {
                        $previewBox.width($image.width());
                        $previewBox.css('margin', '0 auto');
                    }
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
                        minSize: [80, 80],
                        onSelect: saveCoords,
                        onChange: saveCoords,
                    });
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    }

    $(".default-form :file").change(function(){
        readURL(this, $('.default-form .preview-image'));
    });
    // => User image preview on register page END


    // => Scroll to begin of pagination block START
    $(document).on('click', '.blog-posts .pagination a', function(event) {
        $('html, body').animate({
            scrollTop: $('.blog-posts').offset().top
        }, 800);
    });
    $(document).on('click', '.cabinet-comments .pagination a', function(event) {
        $('html, body').animate({
            scrollTop: $('.cabinet-comments').offset().top
        }, 800);
    });
    // => Scroll to begin of pagination block END
    

    // => Vote buttons START 
    $(document).on('click', '.rating-counter a', function(event) {
        if($(this).hasClass('disable')) return;
        if($(this).hasClass('voted')) return;
        var vote = $(this).hasClass('rating-up') ? 1 : 0;
        var voteableId = $(this).attr('data-id');
        var voteableType = $(this).attr('data-type');
        $ratingCounter = $(this);
        $.getJSON( "/admin/vote/vote", { 'id': voteableId, 'type': voteableType, 'vote' : vote}, function( data ) {
            if(data.success) {
                $ratingCounter.parent().find('.rating-count').first().text(data.rating);
                $ratingCounter.parent().find('.voted').removeClass('voted');
                $ratingCounter.addClass('voted');
            }
        });
    });
    // => Vote buttons END 


    // => Question voting START 
    $(document).on('submit', '#inquirer-form', function(event) {
        var $form = $(this);
        var dataUrl = $form.attr('action');
        var $box = $form.parents('.default-box').first();
        var data = $form.serialize();
        if(data) {
            $.ajax({
                type: "GET",
                url: dataUrl,
                dataType: "json",
                data: data,
                success: function(response){
                    console.log(response);
                },
                error: function(e) {
                    console.log(e);
                },
            }).done(function(){
                $box.slideUp(500);
                if($('.inquirers-container').length > 0) {
                    console.log('asd')
                    $('.inquirers-container').indyMasonry('_newElement');
                }
            });
        }
        return false;
    });
    $(document).on('click', '.item', function(event) {
                    console.log('asd')
       $('.inquirers-container').indyMasonry({
              'clName'    : '.item',
              'gap'       : 15,
              'mTop'      : 0,
              'mBottom'   : 15,
              'column'    : 2,
        });
    });
    // => Question voting END 

  });
})(jQuery);