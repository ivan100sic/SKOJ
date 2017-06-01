function tab_hook(event, elem) {
	// standing on the shoulders of elgholm from
	// http://stackoverflow.com/questions/6637341/use-tab-to-indent-in-textarea
	event.preventDefault();
	if (event.keyCode === 9) {
		var v = elem.value;
		var s = elem.selectionStart;
		var e = elem.selectionEnd;
		elem.value = v.substring(0, s) + '\t' + v.substring(e);
		elem.selectionStart = elem.selectionEnd = s+1;
		return false;
	}
}