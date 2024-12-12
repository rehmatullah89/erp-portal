/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config )
{
	config.skin            = "moonocolor";
	config.language        = "en";
	config.height          = "400px";
	config.removePlugins   = "about,print,save,newpage,templates,forms";
//	config.extraPlugins    = "youtube,oembed";
	config.allowedContent  = true;
	config.enterMode       = 2;
	config.autoParagraph   = false;
	config.entities        = false;
	config.fillEmptyBlocks = false;
	config.baseHref        = jQuery("base").attr("href");
	config.contentsCss     = (jQuery("base").attr("href") + "css/portal.css");

	// Toolbar groups configuration.
	config.toolbarGroups = [
								{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
								{ name: 'clipboard', groups: [ 'clipb oard', 'undo' ] },
								{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] },
								{ name: 'forms' },
								{ name: 'insert' },
								'/',
								{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
								{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ] },
								{ name: 'links' },
								'/',
								{ name: 'styles' },
								{ name: 'colors' },
								{ name: 'tools' },
								{ name: 'others' },
								{ name: 'about' }
							  ];

	config.toolbar_Basic = [
							  ['Bold', 'Italic', 'Underline', '-', 'NumberedList', 'BulletedList', '-', 'Image', 'Link', 'Unlink', '-', 'Source', 'Maximize']
						   ];
};
