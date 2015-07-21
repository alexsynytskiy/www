(function( $, undefined ) {

	'use strick';

	/*
	*DENNY K.
	*indyMasonry object.
	*/

	$.indyMasonry  		= function( element, options ) {

		this.$el    = $( element );
		this._init( options );

	};

	$.indyMasonry.defaults       = {

		'clName'        : '.indy-masonry',
		'gap' 			: 20,
		'mTop'			: 10,
		'mBottom'		: 10,
		'column' 		: 4,

	};

	$.indyMasonry.prototype   = {


		_init               : function( options ) {

			var 
			_self 				= this,
			element 			= _self.$el;


			_self._masonryCallback(options);


		},

		_newElement : function () {

			var
			_self 	= this,
			element = _self.$el,
			elSet 	= _self.elSet,
			newEl 	= element.find(_self.options.clName).not(elSet);

			_self._elementPosition(newEl);

			_self.elSet = _self.elSet.add(newEl);


		},

		_masonryCallback : function (options) {

			var
			_self 		= this,
			element 	= _self.$el,
			cssPosition = element.css('position');

			_self.options  		= $.extend( true, {}, $.indyMasonry.defaults, options );

			_self.columnCount 		= _self.options.column;
			_self.columnDelta 		= _self.options.column -1;
			_self.gap 				= _self.options.gap;
			_self.mTop 				= _self.options.mTop;
			_self.mBottom 			= _self.options.mBottom;
			_self.elSet 			= element.find(_self.options.clName);
			_self.columnWidth 		= Math.floor(( element.width() - (_self.gap * _self.columnDelta) ) / _self.columnCount);
			_self.containerBottom 	= 0;
			_self.positionArray 	= [];

			for ( var i = 0; i <= _self.columnDelta; i++ ) {

				_self.positionArray[i] = 0;

			}

			if (!cssPosition || cssPosition == 'static') element.css('position', 'relative');


			_self._elementPosition(_self.elSet);

		},

		_elementPosition : function (elSet) {


			var
			_self = this,
			count = elSet.length;			

			elSet.each(function(index) {


				var 
				element = $(this),
				leftValue, countDelta, elementHeight, minBottom;

				element.css({

					'width' 			: _self.columnWidth + 'px',
					'margin-top' 		: _self.mTop 		+ 'px',
					'margin-bottom' 	: _self.mBottom 	+ 'px',

				});

				countDelta 		= 0;
				elementHeight 	= element.outerHeight(true);
				minBottom 		= _self.positionArray[countDelta] + elementHeight;


				for ( var i = 0; i <= _self.columnDelta; i++ ) {

					var newBottom = _self.positionArray[i] + elementHeight;

					if (newBottom < minBottom) {

						minBottom 	= newBottom;
						countDelta 	= i;

					}

				}


				leftValue 		= (_self.columnWidth + _self.gap) * countDelta;

				element.css({

					'top' 			: _self.positionArray[countDelta] + 'px',
					'left' 			: leftValue + 'px',
					'position' 		: 'absolute',				

				});

				_self.positionArray[countDelta] = minBottom;


				if (!--count) _self._containerHeight(index);


			});

		},

		_containerHeight : function (index) {

			var
			_self = this,
			element = _self.$el;


			_self.containerBottom 		= _self.positionArray[0];

			for ( var i = 0; i <= _self.columnDelta; i++ ) {

				if (_self.positionArray[i] > _self.containerBottom) _self.containerBottom = _self.positionArray[i];

			}

			element.css({

				'height' : _self.containerBottom + 'px'

			});


		},

	};


	$.fn.indyMasonry          = function( options ) {


		var args = Array.prototype.slice.call(arguments, 1);
		return this.each(function() {
			var item = $(this), instance = item.data('indyMasonry');
			if(!instance) {

				item.data('indyMasonry', new $.indyMasonry(this, options));

			} else {

				if(typeof options === 'string') {
					instance[options].apply(instance, args);
				}
			}
		});


	};

})( jQuery );

