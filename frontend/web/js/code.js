(function($){
    $(window).load(function() {
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

        //hover functions for matches slider on top of 2nd column at main page
        $('.top-matches-slider .bx-prev, .top-matches-slider .bx-next').hover( function(){
           $(this).addClass("bx-control-hover");
        },
        function(){
           $(this).removeClass("bx-control-hover");
        });

        // masonry tiles view
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
    });

$(document).ready(function() {

	$('input').iCheck({
		checkboxClass: 'icheckbox_flat-blue',
		radioClass: 'iradio_flat-blue'
	});

	//hover functions for header social buttons and rss
	//hover functions for footer rss and inform buttons
	$('.block-top .button, .social-buttons .button').hover( function(){
	   $(this).addClass("button-hover");
	}, function(){
	   $(this).removeClass("button-hover");
	});

	$(".upload-button .field-label").click(function () {
	    $(this).parent().find('input').first().trigger('click');
	});
	$('.upload-button').hover( function(){
	   $(this).addClass("hover");
	},
	function(){
	   $(this).removeClass("hover");
	});

	//checking for input name field in register and sign-in forms
	$('.field-username input[type="text"]').on("change paste keyup", function() {
        var empty = false;

        $('.field-username input[type="text"]').each(function() {
            if ($(this).val().length == 0) {
                empty = true;
            }
        });

        if (!empty) {
        	var name = $(this).val();
        	var regex = /^[0-9a-zA-ZА-Яа-я]*$/;

        	if(regex.test($(this).val())) {
        		$(this).next().addClass('success-input');
        		$(this).next().removeClass('error-input');
        		$(this).next().removeClass('empty-input');
        	}
        	else {
        		$(this).next().removeClass('success-input');
        		$(this).next().addClass('error-input');
        		$(this).next().removeClass('empty-input');
        	}

        } else {
            $(this).next().removeClass('success-input');
        	$(this).next().removeClass('error-input');
        	$(this).next().addClass('empty-input');
        }
    });

    //checking for input e-mail field in register form
	$('.field-email input[type="text"]').on("change paste keyup", function() {
        var empty = false;

        $('.field-email input[type="text"]').each(function() {
            if ($(this).val().length == 0) {
                empty = true;
            }
        });

        if (!empty) {
        	var name = $(this).val();
        	var regex = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);

        	if(regex.test($(this).val())) {
        		$(this).next().addClass('success-input');
        		$(this).next().removeClass('error-input');
        		$(this).next().removeClass('empty-input');
        	}
        	else {
        		$(this).next().removeClass('success-input');
        		$(this).next().addClass('error-input');
        		$(this).next().removeClass('empty-input');
        	}

        } else {
            $(this).next().removeClass('success-input');
        	$(this).next().removeClass('error-input');
        	$(this).next().addClass('empty-input');
        }
    });

    //checking for input passwords
	$('.field-pass2 input[type="password"]').on("change paste keyup", function() {
        var emptyfirst = false;
        var emptysecond = false;

        $('.field-pass1 input[type="password"]').each(function() {
            if ($(this).val().length == 0) {
                emptyfirst = true;
            }
        });

        $('.field-pass2 input[type="password"]').each(function() {
            if ($(this).val().length == 0) {
                emptysecond = true;
            }
        });

        if (!emptyfirst && !emptysecond) {
        	var pass1 = $('.field-pass1 input[type="password"]').val();
        	var pass2 = $(this).val();

        	if(pass1 == pass2) {
        		$(this).next().addClass('success-input');
        		$(this).next().removeClass('error-input');
        		$(this).next().removeClass('empty-input');
        	}
        	else {
        		$(this).next().removeClass('success-input');
        		$(this).next().addClass('error-input');
        		$(this).next().removeClass('empty-input');
        	}

        } else {
            $(this).next().removeClass('success-input');
        	$(this).next().removeClass('error-input');
        	$(this).next().addClass('empty-input');
        }
    });

	//checking for input passwords
	$('.field-pass1 input[type="password"]').on("change paste keyup", function() {
        var emptyfirst = false;
        var emptysecond = false;

        $('.field-pass1 input[type="password"]').each(function() {
            if ($(this).val().length == 0) {
                emptyfirst = true;
            }
        });

        $('.field-pass2 input[type="password"]').each(function() {
            if ($(this).val().length == 0) {
                emptysecond = true;
            }
        });

        if (!emptyfirst && !emptysecond) {
        	var pass1 = $('.field-pass2 input[type="password"]').val();
        	var pass2 = $(this).val();

        	if(pass1 == pass2) {
        		$('.field-pass2 input[type="password"]').next().addClass('success-input');
        		$('.field-pass2 input[type="password"]').next().removeClass('error-input');
        		$('.field-pass2 input[type="password"]').next().removeClass('empty-input');
        	}
        	else {
        		$('.field-pass2 input[type="password"]').next().removeClass('success-input');
        		$('.field-pass2 input[type="password"]').next().addClass('error-input');
        		$('.field-pass2 input[type="password"]').next().removeClass('empty-input');
        	}

        } else {
            $('.field-pass2 input[type="password"]').next().removeClass('success-input');
        	$('.field-pass2 input[type="password"]').next().removeClass('error-input');
        	$('.field-pass2 input[type="password"]').next().addClass('empty-input');
        }
    });

    // toggle comments
    $('.toggle-button').click(function(){
        if($(this).hasClass('toggle-show')) {
            $(this).removeClass('toggle-show');
            $(this).addClass('toggle-hide');
            $(this).find('.toggle-text span').first().text("Скрыть");
            $('#' + $(this).attr('data-target')).show(300);
        } else {
            $(this).removeClass('toggle-hide');
            $(this).addClass('toggle-show');
            $(this).find('.toggle-text span').first().text("Показать");
            $('#' + $(this).attr('data-target')).hide(300);
        }
    });

    $('.replies-toggle-btn.toggle-show').click(function(){
        var $el = $('#' + $(this).attr('data-target')).parent().parent().find('.new-replies-count')
        $el.text('');
        $el.addClass('no-replies');
    });

    var $datepicker = $('.calendar .content').datepick({
        dateFormat: 'dd.mm.yyyy', // Format for dates, defaults to calendar setting if null
        defaultDate: new Date(2015, 1 - 1, 1),
        maxDate: +0,
        fixedWeeks: true, // True to always show 6 weeks, false to only show as many as are needed
        firstDay: 1, // First day of the week, 0 = Sunday, 1 = Monday, ...
        changeMonth: false, // True to change month/year via drop-down, false for navigation only
        useMouseWheel: false,
        monthsToShow: [4,3],
//        onSelect: function(){  // Callback when a date is selected
//            var date = $(this).datepick('getDate');
//        },
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

    $('.selectize-box select').selectize({
        hideSelected: true,
        item: function(data) {
            return "<div data-value='"+data.value+"' data-default='"+data.type+"' class='item'>"+data.label+" </div>";
        }
    });
    // $.each($selects, function(){
    //     var control = $(this)[0].selectize;
    //     control.on('blur',function(){
    //         console.log(control.getValue());
    //         if(control.getValue() == '') {
    //             control.setValue('all');
    //         }
    //     });
    // });


  });
})(jQuery);