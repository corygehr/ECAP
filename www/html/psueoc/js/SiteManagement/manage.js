$(document).ready(function() {
	$('#access_type').on('change', function() {
		if($('#access_type').val() == 'attendant') {
			$('#lot').prop("disabled", false);
		}
		else {
			$('#lot').prop("enabled", true);
		}
	});
});