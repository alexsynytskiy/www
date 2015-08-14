if (!RedactorPlugins) var RedactorPlugins = {};

(function($)
{
    RedactorPlugins.quote = function()
    {
        return {
            init: function()
            {
                var that = this;
                var button = this.button.add('quote', 'Добавить цитату');
                this.button.addCallback(button, this.quote.set);
            },
            set: function()
            {
                this.inline.toggleClass('quoation');
            },
        };
    };
})(jQuery);

