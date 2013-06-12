$('document').ready(function() {

	// Action when user click submit button in a form
	$(".submitme").live("click", function() {
		$formdata	= $(".form").serialize();
		$.post('/questionmaster/LogIn/in', $formdata, function($data) {
			$return = $data.trim();
			if ($return == 'true') {
				contentShowData('#test_result', 'Login OK');
				$(location).attr('href', '/questionmaster/');
			} else {
				contentShowData('#test_result', 'Login Failed');
			}
		});
		return false;
	});

});