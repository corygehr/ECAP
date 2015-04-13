function showHideFieldset(identifier) {
	var fieldsetObj = 'fieldset#'+identifier;
	var fieldsetLegend = 'legend#'+identifier+" span.expandButton";

	if($(fieldsetObj).is(":visible")) {
		$(fieldsetLegend).html("[+]");
		$(fieldsetObj).slideUp();
	}
	else {
		$(fieldsetLegend).html("[-]");
		$(fieldsetObj).slideDown();
	}
}