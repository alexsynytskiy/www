(function($){
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

        function initJcrop(elem)
        {
            $image = $(elem);
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

        var $inputJcrop = $(".file-input :file.jcrop");
        $inputJcrop.on('fileimageloaded', function(event, previewId){
            $('img.file-preview-image').css('width','auto');
            $('img.file-preview-image').css('height','auto');
            initJcrop('#' + previewId + ' img');
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
            return true;
        });

    });
})(jQuery);