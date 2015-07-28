(function($){

    $(".spoiler-trigger").click(function() {
        $(this).parent().next().collapse('toggle');
        if($(this).hasClass('dropdown')) {
            $(this).removeClass('dropdown');
            $(this).addClass('dropup');
        } else if($(this).hasClass('dropup')) {
            $(this).removeClass('dropup');
            $(this).addClass('dropdown');
        }
    });

    $(window).load(function() {

        if($('.redactor-toolbar').length > 0) {
            $('nav.navbar-fixed-top').removeClass('navbar-fixed-top');
            $('.wrap > .container').css('padding-top','10px');
        }

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

        function initJcrop($image, ratio)
        {
            var height = $image.height();
            var width = $image.width();
            var minSize = 100;
            $image.Jcrop({
                aspectRatio: ratio,
                setSelect: [
                    0,
                    0,
                    height,
                    width
                ],
                minSize: [ratio * minSize, minSize],
                onSelect: saveCoords,
                onChange: saveCoords,
            });
        };

        var $inputJcrop = $(".file-input :file.jcrop");
        var ratio = $inputJcrop.attr('data-crop-ratio');
        ratio = ratio ? ratio : 1;
        $inputJcrop.on('fileimageloaded', function(event, previewId){
            $('img.file-preview-image').css('width','auto');
            $('img.file-preview-image').css('height','auto');
            var $image = $('#' + previewId + ' img');
            initJcrop($image, ratio);
        });

        var $input = $(".file-input :file");
        $input.on("filepredelete", function(event, key) {
            var imagesData = $("#images-data").val();
            if(imagesData) {
                var keys = imagesData.split(';');
                var index = keys.indexOf(key.toString());
                if (index > -1) {
                    keys.splice(index, 1);
                }
                $("#images-data").val(keys.join(';'));
            }
            return false;
        });

        // Modal functions
        $(document).on('pjax:timeout', function(event) {
          // Prevent default timeout redirection behavior
          event.preventDefault()
        })

        $(document).on('click', '.modal-button', function(){
            $($(this).attr('data-target')).modal('show')
                .find('.modal-body')
                .load($(this).attr('data-url'));
        });

        $(document).on('click', '.delete-button', function(){
            var $pjaxContainer = $(this).parents('.panel').first().find('.pjax-container').first();
            var url = $(this).attr('data-url');
            $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
            }).done(function(){
                $.pjax.reload('#' + $pjaxContainer.attr('id'));
            });
            return false;
        });

        $(document).on('click', '.modal form :submit', function(){
            var $modalWindow = $(this).parents('.modal').first();
            var $pjaxContainer = $(this).parents('.panel').first().find('.pjax-container').first();
            var $form = $(this).parents('form').first();
            $.ajax({
                type: "POST",
                url: $form.attr('action'),
                dataType: "json",
                data: $form.serialize(),
                success: function(response){
                    console.log(response);
                },
            }).done(function(){
                $modalWindow.modal('hide');
                if($pjaxContainer) $.pjax.reload('#' + $pjaxContainer.attr('id'));
            });
            return false;
        });

        $(document).on('click', '.dual-list-submit', function(){
            var $modalWindow = $(this).parents('.modal').first();
            var $pjaxContainer = $(this).parents('.panel').first().find('.pjax-container').first();
            var $selected = $(this).parents('.modal').first().find('select.selected option');
            var dataUrl = $(this).attr('data-url');
            var dataTeamId = $(this).attr('data-teamId');
            var dataMatchId = $(this).attr('data-matchId');
            var options = [];
            $selected.each(function(index, el) {
                options.push($(el).val());
            });
            $.ajax({
                type: "POST",
                url: dataUrl,
                dataType: "json",
                data: {
                    list: options.join(';'),
                    teamId: dataTeamId,
                    matchId: dataMatchId,
                },
                success: function(response){
                    console.log(response);
                },
            }).done(function(){
                $modalWindow.modal('hide');
                $.pjax.reload('#' + $pjaxContainer.attr('id'));
            });
            return false;
        });

        $(document).on('change', '#match-command_guest_id', function(event) {
            if($(this).val() != '') {
                $('.guest-side').slideUp(500);
            }
        }); 

        // Match ball possesion changing 
        $(document).on('change', '#match-home_ball_possession', function(event) {
            var value = 100 - $(this).val();
            if (value < 0) value = 0;
            if (value > 100) value = 100;
            var sliderInput = $("#match-guest_ball_possession").slider();
            sliderInput.slider('setValue', value);
        }); 
        $(document).on('change', '#match-guest_ball_possession', function(event) {
            var value = 100 - $(this).val();
            if (value < 0) value = 0;
            if (value > 100) value = 100;
            var sliderInput = $("#match-home_ball_possession").slider();
            sliderInput.slider('setValue', value);
        }); 

        $(document).on('change', '#album-match_id', function(event) {
            var matchID = parseInt($(this).val().trim());
            if(!isNaN(matchID)){
                $.ajax({
                    type: "GET",
                    url: '/admin/match/match-name',
                    dataType: "json",
                    data: {
                        matchID: matchID
                    },
                    success: function(response){
                        console.log(response);
                    },
                }).done(function(response){
                    $('#match-preview-name').text(response.data);
                });  
            }
        });

        var initSourceUrl = $('#source-url').text();
        $(document).on('change', '#post-source_id', function(event) {
            var sourceID = parseInt($(this).val().trim());
            if(!isNaN(sourceID) && sourceID == 0) {
                $('#source-url').text(initSourceUrl);
            } else if(!isNaN(sourceID)){
                $.ajax({
                    type: "GET",
                    url: '/admin/source/get-url',
                    dataType: "json",
                    data: {
                        sourceID: sourceID
                    },
                    success: function(response){
                        // console.log(response);
                    },
                }).done(function(response){
                    $('#source-url').text(response.data);
                });  
            } else {
                $('#source-url').text('');
            }
        });

        $(document).on('change', '.match-search-item', function(event) {
            var postData = new Object();
            $('.match-search-item').each(function(index, el) {
                var id = parseInt($(this).val());
                if(!isNaN(id)){
                    postData[$(this).attr('name')] = id;
                }
            });
            // if(Object.keys(postData).length > 0){
                $.ajax({
                    type: "GET",
                    url: '/admin/match/match-list',
                    dataType: "json",
                    data: postData,
                    success: function(response){
                        // console.log(response);
                    },
                }).done(function(response){
                    if(response.success && response.list) {
                        var newHtml = '';
                        $.each(response.list, function(index, value) {
                            newHtml += '<label><input type="checkbox" name="Relation[parent_id][]"' 
                                + 'value="' + index + '"> ' + value + '</label>';
                        });
                        $('#match-list').html(newHtml);
                    } else if(!response.success) {
                        $('#match-list').html(response.message);
                    }
                    // $('#match-preview-name').text(response.data);
                });  
            // }
        });
    
        $(document).on('change', '#match-list :checkbox', function(event) {
            var state = $(this).prop("checked");
            $('#match-list :checkbox').prop("checked", false);
            $(this).prop("checked", state);
        });

    });
})(jQuery);