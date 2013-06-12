$('document').ready(function() {

	// What happens when user clicks on "Cancel" while creating
	// a new course
	$(".bt_cancel, .show_form").live("click", function() {
		$(location).attr('href', '/questionmaster/Users/insert');
	});

	// What happens when user adds a new user
	$(".new_user").live("click", function() {
		document.body.style.cursor	= 'wait';
		$id_profile		= $("#id_profile").val();
		$vc_user		= $("#vc_user").val();
		$vc_email		= $("#vc_email").val();
		$password1		= $("#password1").val();
		$password2		= $("#password2").val();
		if (($id_profile) && ($vc_user) && ($vc_email) && ($password1) && ($password2)) {
			if ($password1 == $password2) {
				$.post('/questionmaster/Users/addUser/', {
					id_profile:		$id_profile,
					vc_user:		$vc_user,
					vc_email:		$vc_email,
					vc_password:	$password1
				}, function($return) {
					$return			= $return.trim();
					if ($return == 'ok') {
						$("#formInsertCourse").hide();
						$("#message_area").hide();
						$("#main_title").hide();
						contentShowData("#message_area", '<span class="title_01">New User succefully created!!<br /><br /><a href="#" class="text_01 show_form"><u>Add another User</u></a>');
					} else {
						alert("Sorry,\n\nIt was't possible to add this user to the database.\n\nError: "+$return);
					}
					document.body.style.cursor = 'default';
					return false;
				});
			} else {
			alert("Atention\n\nPasswords don't match!");
				$("#password1").focus();
				$("#password1").select();
			}
		} else {
			alert('Please,\n\nMake sure all fields are filled!');
		}
		document.body.style.cursor	= 'default';
		return false;
	});

	// What happens when user click on database result line
	$(".return_row").live("click", function() {
		$key		= $(this).attr('key');
		if ($key) {
			openFancybox('/questionmaster/Users/details/'+$key, 800, 500);
		}
		return false;
	});

	// What happens when a user is updated
	$(".updt_user").live("click", function() {
		$id_user	= $("#id_user").val();
		$id_profile	= $("#id_profile").val();
		$vc_user	= $("#vc_user").val();
		$vc_email	= $("#vc_email").val();
		$pass1		= $("#password1").val();
		$pass2		= $("#password2").val();
		if (($id_user) && ($id_profile) && ($vc_user) && ($vc_email)) {
			if ($pass1 == $pass2) {
				$.post('/questionmaster/Users/updateUser/', {
					id_user:		$id_user,
					id_profile:		$id_profile,
					vc_user:		$vc_user,
					vc_email:		$vc_email,
					vc_password:	$pass1
				}, function($return) {
					$return			= $return.trim();
					if ($return == 'ok') {
						$("#formInsertCourse").hide();
						$("#message_area").hide();
						$("#main_title").hide();
						contentShowData("#message_area", '<span class="title_01">User succefully Updated!!</span>');
						parent.$.fancybox.close();
					} else {
						alert("Sorry,\n\nIt was't possible to update this user on the database.\n\nError: "+$return);
					}
					document.body.style.cursor = 'default';
					return false;
				});
			} else {
				alert("Atention,\n\nGiven passwords don't match");
			}
		} else {
			alert("Please\n\nFill at least the first 3 fields.\n\n(Profile, User and Email)");
		}
		return false;
	});

	// What happens when user tries to delete a User
	$(".delete_user").live("click", function () {
		$res					= confirm("ATENTION,\n\nAre you sure you want to delete this user??");
		if ($res) {
			document.body.style.cursor	= 'wait';
			$id_user			= $("#id_user").val();
			if ($id_user) {
				$.post('/questionmaster/Users/deleteUser/', {
					id_user:	$id_user
				}, function($return) {
					if ($return == 'ok') {
						alert("User succefully deleted!");
						document.body.style.cursor	= 'default';
						parent.$.fancybox.close();
					} else {
						alert("User could NOT be deleted!\n\n"+$return);
						document.body.style.cursor	= 'default';
					}
					return false;
				});
			}
		}
		document.body.style.cursor	= 'default';
	});

});