
import SimpleMDE from 'simplemde';
import 'simplemde/dist/simplemde.min.css';
window.SimpleMDE = SimpleMDE;
import SimpleMDEEditor from 'react-simplemde-editor';
import React from 'react';

function _replaceSelection(cm, active, startEnd, url) {
	if(/editor-preview-active/.test(cm.getWrapperElement().lastChild.className))
		return;

	var text;
	var start = startEnd[0];
	var end = startEnd[1];
	var startPoint = cm.getCursor("start");
	var endPoint = cm.getCursor("end");
	if(url) {
		end = end.replace("#url#", url);
	}
	if(active) {
		text = cm.getLine(startPoint.line);
		start = text.slice(0, startPoint.ch);
		end = text.slice(startPoint.ch);
		cm.replaceRange(start + end, {
			line: startPoint.line,
			ch: 0
		});
	} else {
		text = cm.getSelection();
		cm.replaceSelection(start + text + end);

		startPoint.ch += start.length;
		if(startPoint !== endPoint) {
			endPoint.ch += start.length;
		}
	}
	cm.setSelection(startPoint, endPoint);
	cm.focus();
}

function getState(cm, pos) {
	pos = pos || cm.getCursor("start");
	var stat = cm.getTokenAt(pos);
	if(!stat.type) return {};

	var types = stat.type.split(" ");

	var ret = {},
		data, text;
	for(var i = 0; i < types.length; i++) {
		data = types[i];
		if(data === "strong") {
			ret.bold = true;
		} else if(data === "variable-2") {
			text = cm.getLine(pos.line);
			if(/^\s*\d+\.\s/.test(text)) {
				ret["ordered-list"] = true;
			} else {
				ret["unordered-list"] = true;
			}
		} else if(data === "atom") {
			ret.quote = true;
		} else if(data === "em") {
			ret.italic = true;
		} else if(data === "quote") {
			ret.quote = true;
		} else if(data === "strikethrough") {
			ret.strikethrough = true;
		} else if(data === "comment") {
			ret.code = true;
		} else if(data === "link") {
			ret.link = true;
		} else if(data === "tag") {
			ret.image = true;
		} else if(data.match(/^header(\-[1-6])?$/)) {
			ret[data.replace("header", "heading")] = true;
		}
	}
	return ret;
}

toolbar = [];

var toolbarBuiltInButtons = {
	"bold": {
		name: "bold",
		action: SimpleMDE.toggleBold,
		className: "fa fa-bold",
		title: "Grassetto",
		default: true
	},
	"italic": {
		name: "italic",
		action: SimpleMDE.toggleItalic,
		className: "fa fa-italic",
		title: "Corsivo",
		default: true
	},
	"strikethrough": {
		name: "strikethrough",
		action: SimpleMDE.toggleStrikethrough,
		className: "fa fa-strikethrough",
		title: "Sbarrato"
	},
	"heading": {
		name: "heading",
		action: SimpleMDE.toggleHeadingSmaller,
		className: "fa fa-header",
		title: "Testata",
		default: true
	},
	"heading-smaller": {
		name: "heading-smaller",
		action: SimpleMDE.toggleHeadingSmaller,
		className: "fa fa-header fa-header-x fa-header-smaller",
		title: "Testata più piccola"
	},
	"heading-bigger": {
		name: "heading-bigger",
		action: SimpleMDE.toggleHeadingBigger,
		className: "fa fa-header fa-header-x fa-header-bigger",
		title: "Testata più grande"
	},
	"heading-1": {
		name: "heading-1",
		action: SimpleMDE.toggleHeading1,
		className: "fa fa-header fa-header-x fa-header-1",
		title: "Testata 1"
	},
	"heading-2": {
		name: "heading-2",
		action: SimpleMDE.toggleHeading2,
		className: "fa fa-header fa-header-x fa-header-2",
		title: "Testata 2"
	},
	"heading-3": {
		name: "heading-3",
		action: SimpleMDE.toggleHeading3,
		className: "fa fa-header fa-header-x fa-header-3",
		title: "Testata 3"
	},
	"separator-1": {
		name: "separator-1"
	},
	"code": {
		name: "code",
		action: SimpleMDE.toggleCodeBlock,
		className: "fa fa-code",
		title: "Codice"
	},
	"quote": {
		name: "quote",
		action: SimpleMDE.toggleBlockquote,
		className: "fa fa-quote-left",
		title: "Cita",
		default: true
	},
	"unordered-list": {
		name: "unordered-list",
		action: SimpleMDE.toggleUnorderedList,
		className: "fa fa-list-ul",
		title: "Lista generica",
		default: true
	},
	"ordered-list": {
		name: "ordered-list",
		action: SimpleMDE.toggleOrderedList,
		className: "fa fa-list-ol",
		title: "Lista numerata",
		default: true
	},
	"clean-block": {
		name: "clean-block",
		action: SimpleMDE.cleanBlock,
		className: "fa fa-eraser fa-clean-block",
		title: "Pulisci blocco"
	},
	"separator-2": {
		name: "separator-2"
	},
	"link": {
		name: "link",
		action: SimpleMDE.drawLink,
		className: "fa fa-link",
		title: "Crea collegamento",
		default: true
	},
	"image": {
		name: "image",
		action: SimpleMDE.drawImage,
		className: "fa fa-picture-o",
		title: "Inserisci immagine",
		default: true
	},
	"table": {
		name: "table",
		action: SimpleMDE.drawTable,
		className: "fa fa-table",
		title: "Inserisci tabella"
	},
	"horizontal-rule": {
		name: "horizontal-rule",
		action: SimpleMDE.drawHorizontalRule,
		className: "fa fa-minus",
		title: "Inserisci separatore orizzontale"
	},
	"separator-3": {
		name: "separator-3"
	},
	"preview": {
		name: "preview",
		action: SimpleMDE.togglePreview,
		className: "fa fa-eye no-disable",
		title: "Mostra/chiudi anteprima",
		default: true
	},
	"side-by-side": {
		name: "side-by-side",
		action: SimpleMDE.toggleSideBySide,
		className: "fa fa-columns no-disable no-mobile",
		title: "Mostra/chiudi fianco a fianco",
		default: true
	},
	"fullscreen": {
		name: "fullscreen",
		action: SimpleMDE.toggleFullScreen,
		className: "fa fa-arrows-alt no-disable no-mobile",
		title: "Mostra/chiudi schermo intero",
		default: true
	},
	"separator-4": {
		name: "separator-4"
	},
	"guide": {
		name: "guide",
		action: "https://simplemde.com/markdown-guide",
		className: "fa fa-question-circle",
		title: "Guida Markdown",
		default: true
	},
	"separator-5": {
		name: "separator-5"
	},
	"undo": {
		name: "undo",
		action: SimpleMDE.undo,
		className: "fa fa-undo no-disable",
		title: "Annulla"
	},
	"redo": {
		name: "redo",
		action: SimpleMDE.redo,
		className: "fa fa-repeat no-disable",
		title: "Ripeti"
	}
};

for(var key in toolbarBuiltInButtons) {
			if(toolbarBuiltInButtons.hasOwnProperty(key)) {
				if(key.indexOf("separator-") != -1) {
					toolbar.push("|");
				}

				if(toolbarBuiltInButtons[key].default === true) {
					toolbar.push(key);
				}
			}
}

function uploadImage(editor) {

	var cm = editor.codemirror;
	var stat = getState(cm);
	var options = editor.options;

  window.currentState = {cm: cm, stat: stat, options: options};

  var uploadWindow = window.open($("#uploader").attr("href"),'uploader','height=480,width=350');
  if (window.focus)
    uploadWindow.focus()

  var input = document.createElement('input');
  input.type = 'hidden';
  input.value = 'pushImage';
  input.name = 'fn';

  uploadWindow.onload = function () {
    uploadWindow.document.getElementById('form').appendChild(input);
  }
}

window.pushImage = function (url) {
  _replaceSelection(window.currentState.cm, window.currentState.stat.image, window.currentState.options.insertTexts.image, url);
  window.currentState = null;
}

toolbar.push({
  name: "upload",
  action: uploadImage,
  className: "fa fa-upload",
  title: "Carica immagine",
});

jQuery.fn.extend({
  mde: function() {
    new SimpleMDE({
      element: this[0],
      spellChecker: false,
      status: false,
      toolbar: toolbar
    });
  }
});

export default props => <SimpleMDEEditor {...props} options={{
	//element: this[0],
	spellChecker: false,
	status: false,
	toolbar: toolbar
}} />;