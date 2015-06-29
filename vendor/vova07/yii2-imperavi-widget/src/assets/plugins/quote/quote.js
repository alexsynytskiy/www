if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.quote = function()
{
	return {
		init: function()
		{
			var button = this.button.add('quote', 'Цитата');
			this.button.addCallback(button, this.quote.add);
		},
		add: function()
		{
			this.selection.restore();
			// console.log(this.selection.getHtml());
			this.insert.html('<span class="quoation">' + this.selection.getHtml() + '</span>');
			this.observe.load();
		},
	};
};

