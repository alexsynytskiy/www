if (!RedactorPlugins) var RedactorPlugins = {};

(function($)
{
    RedactorPlugins.skip = function()
    {
        return {
            init: function()
            {
                var that = this;
                var button = this.button.add('horizontalrule', 'Добавить пропуск');
                this.button.addCallback(button, this.skip.set);
            },
            set: function()
            {
                console.log(this.inline);
                this.insert.html('<span class="skip">***</span>');
            },
        };
    };
})(jQuery);
