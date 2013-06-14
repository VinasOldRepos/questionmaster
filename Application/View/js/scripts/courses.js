$('document').ready(function() {

	// What happens when user clicks on "Cancel" while creating
	// a new course
	$(".bt_cancel, .show_form").live("click", function() {
		$(location).attr('href', '/questionmaster/Courses/insert');
	});

	// What happens when user clicks on "Close window"
	$(".close_modal").live("click", function() {
		parent.$.fancybox.close();
	});

	// What happens when user selects a branch for input
	$("#id_branch").live("change", function() {
		document.body.style.cursor	= 'wait';
		$id_branch	= $(this).val();
		if (($id_branch) && ($id_branch != 0)) {
			$.post('/questionmaster/Courses/loadFields/', {
				id_branch:	$id_branch
			}, function($data) {
				if ($data) {
					$("#id_field").html($data);
				} else {
					alert("ATENTION:\n\nThere are no Fields of Study for the chosen Branch.\n\nTo Proceed, please add a Field to it by editing this Branch");
				}
				document.body.style.cursor = 'default';
				return false;
			});
		} else if ($id_branch == 0) {
			$("#id_field").html('<option value="0">-</option>');
		}
		document.body.style.cursor	= 'default';
		return false;
	});

	// What happens when user submit new branch
	$(".new_course").live("click", function() {
		$id_field			= $("#id_field").val().trim();
		$vc_course			= $("#course").val().trim();
		$level				= $("#level").val().trim();
		$status				= $("#status").val().trim();
		if (($id_branch) && ($id_field) && ($vc_course) && ($level) && ($status)) {
			$.post('/questionmaster/Courses/addCourse/', {
				id_field:	$id_field,
				vc_course:	$vc_course,
				level:		$level,
				status:		$status
			}, function($data) {
				$return		= $data.trim();
				if ($return	== 'ok') {
					$("#formInsertCourse").hide();
					$("#message_area").hide();
					$("#main_title").hide();
					contentShowData("#message_area", '<span class="title_01">New Course succefully created!!<br /><br /><a href="#" class="text_01 show_form"><u>Add another Course</u></a>');
				}
			});
		} else {
			alert("Please, check Branch's name\nand if it has at least one field.");
		}
		return false;
	});

	$("#add_questions").live("click", function() {
		$id_course	= $("#id_course").val();
		//parent.$.fancybox.close();
		$(location).attr('href', '/questionmaster/Questions/insertCourse/'+$id_course);
		return false;
	});

	// What happens when user submit new branch
	$(".updt_course").live("click", function() {
		document.body.style.cursor	= 'wait';
		$id_field			= $("#id_field").val().trim();
		$id_course			= $("#id_course").val().trim();
		$vc_course			= $("#course").val().trim();
		$level				= $("#level").val().trim();
		$status				= $("#status").val().trim();
		if (($id_field) && ($vc_course) && ($level) && ($status)) {
			$.post('/questionmaster/Courses/updateCourse/', {
				id_course:	$id_course,
				id_field:	$id_field,
				vc_course:	$vc_course,
				level:		$level,
				status:		$status
			}, function($data) {
				$return		= $data.trim();
				if ($return	== 'ok') {
					$("#formInsertCourse").hide();
					$("#message_area").hide();
					$("#main_title").hide();
					contentShowData("#message_area", '<span class="title_01">This Course was succefully updated!!<br /><br /><a href="#" class="text_01 close_modal"><u>Click here to close</u></a>');
				} else {
					alert("Please,\n\ncheck if all required information was provided.");
				}
				document.body.style.cursor	= 'default';
			});
		} else {
			alert("Please,\n\ncheck if all required information was provided.");
		}
		document.body.style.cursor	= 'default';
		return false;
	});

	// What happens when user click on database result line
	$(".return_row").live("click", function() {
		$key		= $(this).attr('key');
		if ($key) {
			openFancybox('/questionmaster/Courses/details/'+$key, 800, 500);
		}
		return false;
	});

	// What happens when user tries to delete a Course
	$(".delete_course").live("click", function () {
		$res					= confirm("ATENTION,\n\nThis will also erase:\n\n- questions\n\nthat are associated with this Course.\n\nAre you sure you want to continue??\n(this process may take some seconds)");
		if ($res) {
			document.body.style.cursor	= 'wait';
			$id_course			= $("#id_course").val();
			if ($id_course) {
				$.post('/questionmaster/Courses/deleteCourse/', {
					id_course:	$id_course
				}, function($return) {
					if ($return == 'ok') {
						alert("Course and related information were succefully deleted!");
						document.body.style.cursor	= 'default';
						parent.$.fancybox.close();
					} else {
						alert("Course could NOT be deleted!\n\n"+$return);
						document.body.style.cursor	= 'default';
					}
					return false;
				});
			}
		}
		document.body.style.cursor	= 'default';
	});

});