/**
 * @description	This module takes care of the sidebar.
 * @copyright	2015 by Tobias Reich
 */

sidebar = {

	_dom: $('#sidebar'),
	types: {
		DEFAULT: 0,
		TAGS: 1
	},
	createStructure: {}

}

sidebar.dom = function(selector) {

	if (selector===undefined||selector===null||selector==='') return sidebar._dom;
	return sidebar._dom.find(selector);

}

sidebar.bind = function() {

	// This function should be called after building and appending
	// the sidebars content to the DOM.
	// This function can be called multiple times, therefore
	// event handlers should be removed before binding a new one.

	// Event Name
	var eventName = ('ontouchend' in document.documentElement) ? 'touchend' : 'click';

	sidebar
		.dom('#edit_title')
		.off(eventName)
		.on(eventName, function() {
			if (visible.photo())		photo.setTitle([photo.getID()]);
			else if (visible.album())	album.setTitle([album.getID()]);
		});

	sidebar
		.dom('#edit_description')
		.off(eventName)
		.on(eventName, function() {
			if (visible.photo())		photo.setDescription(photo.getID());
			else if (visible.album())	album.setDescription(album.getID());
		});

	sidebar
		.dom('#edit_tags')
		.off(eventName)
		.on(eventName, function() {
			photo.editTags([photo.getID()])
		});

	sidebar
		.dom('#tags .tag span')
		.off(eventName)
		.on(eventName, function() {
			photo.deleteTag(photo.getID(), $(this).data('index'))
		});

	return true;

}

sidebar.toggle = function() {

	if (visible.sidebar()||
		visible.sidebarbutton()) {

			header.dom('.button--info').toggleClass('active')
			lychee.content.toggleClass('sidebar');
			sidebar.dom().toggleClass('active');

			return true;

	}

	return false;

}

sidebar.setSelectable = function(selectable = true) {

	// Attributes/Values inside the sidebar are selectable by default.
	// Selection needs to be deactivated to prevent an unwanted selection
	// while using multiselect.

	if (selectable===true)	sidebar.dom().removeClass('notSelectable');
	else					sidebar.dom().addClass('notSelectable');

}

sidebar.changeAttr = function(attr, value = '-') {

	if (attr===undefined||attr===null||attr==='') return false;

	// Set a default for the value
	if (value===''||value===null) value = '-';

	sidebar.dom('.attr_' + attr).html(value);

	return true;

}

sidebar.createStructure.photo = function(data) {

	if (data===undefined||data===null||data==='') return false;

	var editable	= false,
		exifHash	= data.takestamp + data.make + data.model + data.shutter + data.aperture + data.focal + data.iso,
		structure	= {},
		visible		= '';

	// Enable editable when user logged in
	if (lychee.publicMode===false) editable = true;

	// Set value for public
	switch (data.public) {

		case '0':	visible = 'No';
					break;
		case '1':	visible = 'Yes';
					break;
		case '2':	visible = 'Yes (Album)';
					break;
		default:	visible = '-';
					break;

	}

	structure.basics = {
		title: 'Basics',
		type: sidebar.types.DEFAULT,
		rows: [
			{ title: 'Title',		value: data.title,			editable },
			{ title: 'Uploaded',	value: data.sysdate },
			{ title: 'Description', value: data.description,	editable },
		]
	}

	structure.image = {
		title: 'Image',
		type: sidebar.types.DEFAULT,
		rows: [
			{ title: 'Size',		value: data.size },
			{ title: 'Format',		value: data.type },
			{ title: 'Resolution',	value: data.width + ' x ' + data.height }
		]
	}

	// Only create tags section when user logged in
	if (lychee.publicMode===false) {

		structure.tags = {
			title: 'Tags',
			type: sidebar.types.TAGS,
			value: build.tags(data.tags),
			editable
		}

	} else {

		structure.tags = {}

	}

	// Only create EXIF section when EXIF data available
	if (exifHash!=='0') {

		structure.exif = {
			title: 'Camera',
			type: sidebar.types.DEFAULT,
			rows: [
				{ title: 'Captured',		value: data.takedate },
				{ title: 'Make',			value: data.make },
				{ title: 'Type/Model',		value: data.model },
				{ title: 'Shutter Speed',	value: data.shutter },
				{ title: 'Aperture',		value: data.aperture },
				{ title: 'Focal Length',	value: data.focal },
				{ title: 'ISO',				value: data.iso }
			]
		}

	} else {

		structure.exif = {}

	}

	structure.sharing = {
		title: 'Sharing',
		type: sidebar.types.DEFAULT,
		rows: [
			{ title: 'Public', value: visible },
		]
	}

	// Construct all parts of the structure
	structure = [
		structure.basics,
		structure.image,
		structure.tags,
		structure.exif,
		structure.sharing
	]

	return structure;

}

sidebar.render = function(structure) {

	if (structure===undefined||structure===null||structure==='') return false;

	var html = '';

	var renderDefault = function(section) {

		let _html = '';

		_html +=	`
					<div class='divider'>
						<h1>${ section.title }</h1>
					</div>
					<table>
					`

		section.rows.forEach(function(row) {

			let value = row.value;

			// Set a default for the value
			if (value===''||value===null||value===undefined) value = '-';

			// Wrap span-element around value for easier selecting on change
			value = `<span class='attr_${ row.title.toLowerCase() }'>${ value }</span>`;

			// Add edit-icon to the value when editable
			if (row.editable===true) value += ' ' + build.editIcon('edit_' + row.title.toLowerCase());

			_html +=	`
						<tr>
							<td>${ row.title }</td>
							<td>${ value }</td>
						</tr>
						`

		});

		_html +=	`
					</table>
					`

		return _html;

	};

	var renderTags = function(section) {

		let _html = '';

		_html +=	`
					<div class='divider'>
						<h1>${ section.title }</h1>
					</div>
					<div id='tags'>
						<div class='attr_${ section.title.toLowerCase() }'>${ section.value }</div>
					`

		// Add edit-icon to the value when editable
		if (section.editable===true) _html += build.editIcon('edit_tags');

		_html +=	`
					</div>
					`

		return _html;

	}

	structure.forEach(function(section) {

		if (section.type===sidebar.types.DEFAULT)	html += renderDefault(section);
		else if (section.type===sidebar.types.TAGS)	html += renderTags(section);

	});

	return html;

}