/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	config.font_names = '굴림/gulim;돋움/dotum;궁서/Gungsuh;맑은고딕/Malgun Gothic;나눔고딕/NanumGothic;나눔명조/NanumMyeongjo;나눔손글씨/NanumPen;' + config.font_names; 
	config.toolbar = 'Basic';
	config.extraPlugins = 'simpleuploads';
	config.skin = 'moono-dark';
	config.allowedContent = true;

	config.toolbar_Basic =
	[
	    ['NewPage','Font','FontSize'],
	    ['Bold','Italic','Underline','Strike'],
	    ['TextColor','BGColor'],
	    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	    ['addImage','Flash','Table','SpecialChar'],
	    ['Link','Unlink'],
	    ['Source']
	];

};
