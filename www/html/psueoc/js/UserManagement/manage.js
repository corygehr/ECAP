$(document).ready(function () {
	$('#responsible_lots').tablesorter();
});

$("#deleteUser").submit(function() {
	return confirm("Click OK to confirm you would like to delete this user.");
});