function showHideFieldset() {
	if($('fieldset.expandable').is(":visible")) {
		$('span.expandButton').html("[+]");
		$('fieldset.expandable').slideUp();
	}
	else {
		$('span.expandButton').html("[-]");
		$('fieldset.expandable').slideDown();
	}
}