(function($){
    $(window).load(function() {

        function saveCoords(c) {
            var params = [
                c.x/c.imageWidth,
                c.y/c.imageHeight,
                c.x2/c.imageWidth,
                c.y2/c.imageHeight,
                c.w/c.imageWidth,
                c.h/c.imageHeight
            ];
            $('#jcrop-coords').val(params.join(';'))
        }

        function initJcrop(previewId)
        {
            $image = $('#' + previewId + ' img');
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
        };

        // $.each($('.file-preview-thumbnails img'),function(){
        //     var height = $(this).height();
        //     var width = $(this).width();
        //     var maxSize = height < width ? height : width;
        //     $(this).Jcrop({
        //         aspectRatio: 1,
        //         setSelect: [
        //             width > maxSize ? (width-maxSize)/2 : 0,
        //             height > maxSize ? (height-maxSize)/2 : 0,
        //             maxSize,
        //             maxSize,
        //         ],
        //         minSize: [100, 100],
        //         onSelect: saveCoords,
        //         onChange: saveCoords,
        //     });
        // });

        var $input = $(".file-input :file");
        $input.on('fileimageloaded', function(event, previewId){
            $('img.file-preview-image').css('width','auto');
            $('img.file-preview-image').css('height','auto');
            $('img.file-preview-image').css('max-height','500px');
            $('img.file-preview-image').css('max-width','500px');
            $('img.file-preview-image').css('min-height','100px');
            $('img.file-preview-image').css('min-width','100px');
            initJcrop(previewId);
        });

    });
})(jQuery);