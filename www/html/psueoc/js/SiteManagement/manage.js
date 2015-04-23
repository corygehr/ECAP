$(document).ready(function() {
	$('#access_type').on('change', function() {
		if($('#access_type').val() == '2') {
			$('select#lot').prop("disabled", false);
			$('p#lot').slideDown();
			$('input#password').prop("disabled", true);
			$('p#password').slideUp();
		}
		else if($('#access_type').val() == '1') {
			$('input#password').prop("disabled", false);
			$('p#password').slideDown();
			$('select#lot').prop("disabled", true);
			$('p#lot').slideUp();
		}
		else {
			$('input#password').prop("disabled", true);
			$('p#password').slideUp();
			$('select#lot').prop("disabled", true);
			$('p#lot').slideUp();
		}
	});

	$('#user_list').tablesorter();
});

$("#resetDb").submit(function() {
	return confirm("Click OK to confirm you would like to reset ALL lots. This operation cannot be undone.");
});