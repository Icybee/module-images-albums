
Icybee.Widget.Pop = new Class({

	Extends: Brickrouge.Widget.Spinner,

	options: {

		adjust: null

	},

	initialize: function(el, options)
	{
		this.parent(el, options)
		this.popover = null
		this.popoverRequest = new Request.Widget(this.options.adjust + '/popup', this.setupPopover.bind(this))
	},

	revokePopover: function()
	{
		if (!this.popover) return

		this.popover.hide()
		this.popover = null
	},

	open: function()
	{
		var value = this.getValue()

		this.resetValue = value

		if (this.popover)
		{
			this.popover.show()
		}
		else
		{
			this.popoverRequest.get({ value: this.getValue() })
		}
	},

	close: function()
	{
		if (this.popover)
		{
			this.popover.hide()
		}
	},

	setupPopover: function(el)
	{
		this.popover = popover = new Icybee.Widget.AdjustPopover(el, {

			anchor: this.element

		})

		popover.show()
		popover.adjust.addEvent('change', this.onAdjust.bind(this))
		popover.addEvent('action', this.onAction.bind(this))
	},

	onAdjust: function()
	{
//		console.log('onAdjust', arguments)
	},

	onAction: function(ev)
	{
		switch (ev.action)
		{
			case 'cancel': return this.cancel()
			case 'remove': return this.remove()
			case 'use': return this.use()
		}
	},

	change: function(ev)
	{
		this.setValue(this.popover.adjust.getValue())
	},

	cancel: function()
	{
		this.reset()
		this.close()
	},

	remove: function()
	{
		this.element.fireEvent('remove', {})
		this.setValue(null)
		this.close()
	},

	use: function()
	{
		this.element.fireEvent('change', {})
		this.close()
	},

	reset: function()
	{
		this.setValue(this.resetValue)
	}
})

Brickrouge.Widget.PopPhoto = new Class({

	Extends: Icybee.Widget.Pop,

	initialize: function(el, options)
	{
		this.parent(el, options)
	},

	getValue: function()
	{
		return this.element.get('data-photo')
	},

	close: function()
	{
		this.parent()
		this.revokePopover()
	},

	remove: function()
	{
		this.close()

		this.element.fireEvent('remove', {});

		//console.log('should remove me', this.element)

		new Request.API({

			url: 'images.albums/photos/' + this.getValue(),
			method: 'delete',

			onSuccess: function(response)
			{
				console.log('delete: ', response)

			}.bind(this)

		}).send()

		this.element.destroy()
	},

	use: function()
	{
//		console.log('use:', arguments, this.popover.adjust.getValue())

		this.popover.hide()
		this.element.addClass('disabled')

		new Request.Element({

			url: 'images.albums/photos/' + this.getValue(),

			onSuccess: function(el, response) {

				el.replaces(this.element)

				Brickrouge.updateDocument()

			}.bind(this)

		}).post(this.popover.adjust.getValue())
	}
})

Object.append(Element.NativeEvents, {
	dragenter: 2, dragleave: 2, dragover: 2, drop: 2
})

Brickrouge.Widget.AddPhoto = new Class({

	Extends: Brickrouge.Widget.PopPhoto,

	initialize: function(el, options)
	{
		this.parent(el, options)

		this.element.addEvents({

			'dragover': function(ev) {

//				console.log('dragover')

				ev.preventDefault()

			},

			'dragenter': function(ev) {

				if (ev.target != this.element) return

				//console.log('enter', ev.target)
				this.element.addClass('drop-target')

			}.bind(this),

			'dragleave': function(ev) {

				if (ev.target != this.element) return

				//console.log('leave', ev.target)
				this.element.removeClass('drop-target')

			}.bind(this),

			'drop': function(ev) {

				this.element.removeClass('drop-target')

				this.onDrop(ev)

			}.bind(this)
		})

		/*
		this.element.addEventListener('dragover', function(event) {
			  event.preventDefault();
			}, true);

		this.element.addEventListener("drop", function(event) {
			  event.preventDefault();
			  // Ready to do something with the dropped object
			  var allTheFiles = event.dataTransfer.files;
			  alert("You've just dropped " + allTheFiles.length + " files");
			}, true);
			*/
	},

	setupPopover: function(el)
	{
		el.getElement('[data-action="remove"]').setStyle('display', 'none')

		this.parent(el)
	},

	cancel: function()
	{
		this.close()
	},

	use: function()
	{
		this.popover.hide()
		this.element.addClass('disabled')

		new Request.Element({

			url: 'images.albums/photos/',

			onSuccess: function(el, response) {

				el.inject(this.element, 'after')

				//console.log('update document with:', el)
				Brickrouge.updateDocument(el)

			}.bind(this)

		}).post(this.popover.adjust.getValue())
	},

	onDrop: function(ev)
	{
		ev.preventDefault()

		//console.log(ev.event.dataTransfer.files)
	}
})


//

Icybee.Widget.Adjust = new Class({

	Implements: [ Options, Events ],

	initialize: function(el, options)
	{
		this.element = document.id(el)
		this.setOptions(options)
	},

	getValue: function() {

		return this.element.toQueryString()

	},

	setValue: function(value) {

//		console.log('setValue:', value)

		if (typeOf(value) == 'string')
		{

		}
	}
})



Brickrouge.Widget.AdjustPhoto = new Class({

	Extends: Icybee.Widget.Adjust

})



Brickrouge.Widget.AlbumEditor = new Class({

	initialize: function(el, options)
	{
		this.element = el = document.id(el)
		this.content = el.getElement('.album-content')
		this.sortables = new Sortables(this.content, {

			clone: true,
			constrain: true,
			opacity: 0.2,

			onStart: function(el, clone)
			{
				clone.setStyle('z-index', 10000);
			}
		})

		/*
		 * The `brickrouge.construct` element is used to spy on photos changes, in order to
		 * keep our sortables up to date.
		 */
		window.addEvent('brickrouge.construct', function(ev) {

			var hasChild = false

			ev.constructed.each(function(constructed) {

				if (this.content.contains(constructed))
				{
					hasChild = true
				}
			}, this)

			if (hasChild)
			{
				this.updateSortables()
			}

		}.bind(this))
	},

	updateSortables: function()
	{
		this.sortables.addItems(this.content.getChildren())
	}
})