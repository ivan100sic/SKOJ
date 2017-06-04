function tab_hook(event, elem) {
	// standing on the shoulders of elgholm from
	// http://stackoverflow.com/questions/6637341/use-tab-to-indent-in-textarea
	make_dirty();
	if (event.keyCode === 9) {
		event.preventDefault();
		var v = elem.value;
		var s = elem.selectionStart;
		var e = elem.selectionEnd;
		elem.value = v.substring(0, s) + '\t' + v.substring(e);
		elem.selectionStart = elem.selectionEnd = s+1;
		return false;
	}
}

function make_dirty() {
	window.onbeforeunload = function() {
		return "You have unsaved changes!";
	};
}

function make_clean() {
	window.onbeforeunload = function() {
		return null;
	};
}