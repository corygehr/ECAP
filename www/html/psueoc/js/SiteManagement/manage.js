$(document).ready(function() {
	$('#access_type').on('change', function() {
		if($('#access_type').val() == '2') {
			$('select#lot').prop("disabled", false);
			$('p#lot').slideDown();
		}
		else {
			$('select#lot').prop("disabled", true);
			$('p#lot').slideUp();
		}
	});

	$('#user_list').tablesorter();
});