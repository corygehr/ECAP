$(document).ready(function () {
	$('#capacity_history').tablesorter();
});

$("#deleteLot").submit(function() {
	return confirm("Click OK to confirm you would like to delete this lot.");
});