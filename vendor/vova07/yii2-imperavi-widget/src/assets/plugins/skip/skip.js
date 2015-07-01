if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.skip = function()
{
	return {
		init: function()
		{
			var button = this.button.add('horizontalrule', 'Пропуск');
			this.button.addCallback(button, this.skip.add);
		},
		add: function()
		{
			this.selection.restore();
			// console.log(this.selection.getHtml());
			this.insert.html('<span class="skip">***</span>');
			this.observe.load();
		},
	};
};

