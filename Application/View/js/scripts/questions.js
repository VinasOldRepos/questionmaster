$('document').ready(function() {

	// What happens when user click on database result line
	$(".return_row, .show_answer_form").live("click", function() {
		$key		= $(this).attr('key');
		if ($key) {
			openFancybox('/questionmaster/Questions/details/'+$key, 800, 500);
		}
		return false;
	});

	// What happens when user click on database result line
	$(".show_answer_form_course").live("click", function() {
		$key		= $(this).attr('key');
		if ($key) {
			$(location).attr('href', '/questionmaster/Questions/details/'+$key);
		}
		return false;
	});

	// What happens when user click on database result line
	$(".details_return_row").live("click", function() {
		document.body.style.cursor	= 'wait';
		$(".details_return_row_this").attr('class', 'details_return_row');
		$(this).attr('class', 'details_return_row_this');
		$key		= $(this).attr('key');
		$.post('/questionmaster/Questions/answerDetails/', {
			id_answer:	$key
		}, function($data) {
			contentShowData("#answer_details", $data)
			document.body.style.cursor = 'default';
			return false;
		});
		document.body.style.cursor	= 'default';
		return false;
	});

	// What happens when user updates a question
	$(".updt_answer").live("click", function() {
		$id_answer		= $("#id_answer").val();
		$id_question	= $("#parent_id").val();
		$vc_answer		= $("#vc_answer").val();
		$boo_correct	= $("#boo_correct").val();
		$old_correct	= $("#old_correct").val();
		if (($id_answer) && ($id_question) && ($vc_answer)) {
			if (($boo_correct == 0) && ($old_correct == 1)) {
				alert('Atention,\n\nA question MUST have one correct answer.\nPlease, add another answer as correct, before you are allowed to set this as Incorrect.');
			} else {
				$.post('/questionmaster/Questions/updateAnswer/', {
					id_answer:		$id_answer,
					id_question:	$id_question,
					vc_answer:		$vc_answer,
					id_question:	$id_question,
					boo_correct:	$boo_correct
				}, function($return) {
					if ($return == 'ok') {
						$("#id_answer").val('');
						$("#vc_answer").val('');
						$("#answer_details").html('');
						reloadAnswerList();
					} else {
						$("#vc_answer").focus();
						$("#vc_answer").select();
						alert("Answer could NOT be updated!\n\n"+$return);
					}
					document.body.style.cursor	= 'default';
					return false;
				});
			}
		}
		return false;
	});

	// What happens when user double clicks question or tutor text
	$(".edit_label").live("dblclick", function() {
		$this_label		= $(this).attr('key');
		$(this).hide();
		if ($this_label == 'tx_question') {
			$("#details_edit_tutor").hide();
			$("#updt_tutor").hide();
			$("#details_tutor").show();
			$("#details_edit_question").show();
			$("#updt_question").show();
			$("#tx_question").focus();
			$("#tx_question").select();
		} else if ($this_label == 'tx_tutor') {
			$("#details_edit_question").hide();
			$("#updt_question").hide();
			$("#details_question").show();
			$("#details_edit_tutor").show();
			$("#updt_tutor").show();
			$("#tx_tutor").focus();
			$("#tx_tutor").select();
		}
		return false;
	});

	// What happens when user alters a Question
	$("#updt_question").live("click", function (e) {
		document.body.style.cursor = 'wait';
		$id_question			= $("#parent_id").val();
		$tx_question			= $("#tx_question").val();
		if ($id_question) {
			$.post('/questionmaster/Questions/updateQuestion/', {
				id_question:	$id_question,
				tx_question:	$tx_question
			}, function($data) {
				$("#details_edit_question").hide();
				$("#updt_question").hide();
				contentShowData("#details_question", $data)
				document.body.style.cursor = 'default';
				return false;
			});
		} else {
			alert('Please,\n\nenter a valid question text.');
			$("#tx_question").val($old_question);
			$("#tx_question").select();
			document.body.style.cursor = 'default';
		}
		return false;
	});

	// What happens when user alters a Tutor text
	$("#updt_tutor").live("click", function (e) {
		document.body.style.cursor = 'wait';
		$id_question			= $("#parent_id").val();
		$tx_tutor				= $("#tx_tutor").val();
		if ($id_question) {
			$.post('/questionmaster/Questions/updateTutor/', {
				id_question:	$id_question,
				tx_tutor:		$tx_tutor
			}, function($data) {
				$("#details_edit_tutor").hide();
				$("#updt_tutor").hide();
				contentShowData("#details_tutor", $data)
				document.body.style.cursor = 'default';
				return false;
			});
		} else {
			alert('Please,\n\nenter a valid tutor text.');
			$("#tx_tutor").val($old_question);
			$("#tx_tutor").select();
			document.body.style.cursor = 'default';
		}
		return false;
	});

	// What happens when user tries to delete a Question
	$(".delete_branch").live("click", function () {
		$res						= confirm("ATENTION,\n\nThis will also erase:\n\n- answers\n\nthat are associated with this Question.\n\nAre you sure you want to continue??\n(this process may take some seconds)");
		if ($res) {
			document.body.style.cursor	= 'wait';
			$id_question			= $("#parent_id").val();
			if ($id_question) {
				$.post('/questionmaster/Questions/deleteQuestion/', {
					id_question:	$id_question
				}, function($return) {
					if ($return == 'ok') {
						alert("Question and related information were succefully deleted!");
						document.body.style.cursor	= 'default';
						parent.$.fancybox.close();
					} else {
						alert("Question could NOT be deleted!\n\n"+$return);
						document.body.style.cursor	= 'default';
					}
					return false;
				});
			}
		}
		document.body.style.cursor	= 'default';
	});

	// What happens when user clicks on "Add new answer"
	$(".new_answer").live("click", function() {
		$id_question	= $("#parent_id").val();
		$vc_answer		= $("#new_answer").val();
		$boo_correct	= $("#correct").val();
		if ($vc_answer) {
			$.post('/questionmaster/Questions/addAnswer/', {
				id_question:	$id_question,
				vc_answer:		$vc_answer,
				boo_correct:	$boo_correct
			}, function($return) {
				if ($return == 'ok') {
					$vc_answer	= $("#new_answer").val('');
					reloadAnswerList();
				} else {
					alert("Answer could NOT be inserted!\n\n"+$return);
					document.body.style.cursor	= 'default';
				}
				return false;
			});
		} else {
			alert("Please,\n\nenter a valid answer.");
			$("#new_answer").focus();
			$("#new_answer").select();
		}
		return false;
	});

	// What happens when user clicks on "Add new course"
	$(".new_course").live("click", function() {
		$id_question			= $("#parent_id").val();
		$id_course				= $("#id_course_add").val();
		if (($id_question) && ($id_question > 0)) {
			$.post('/questionmaster/Questions/addCourse/', {
				id_question:	$id_question,
				id_course:		$id_course
			}, function($return) {
				if ($return == 'ok') {
					location.reload();
				} else {
					alert("Course could NOT be inserted!\n\n"+$return);
					document.body.style.cursor	= 'default';
				}
				return false;
			});
		}
		return false;
	});

	// What happens when user deletes a question
	$(".delete_answer").live("click", function() {
		$id_answer		= $("#id_answer").val();
		$old_correct	= $("#old_correct").val();
		if ($id_answer) {
			if ($old_correct == 1) {
				alert('Atention,\n\nA question MUST have one correct answer.\nPlease, add another answer as correct, before you are allowed to delete this.');
			} else {
				$res	= confirm('Are you sure you want to delete this answer?');
				if ($res) {
					$.post('/questionmaster/Questions/deleteAnswer/', {
						id_answer:	$id_answer
					}, function($return) {
						if ($return == 'ok') {
							$("#id_answer").val('');
							$("#vc_answer").val('');
							$("#answer_details").html('');
							reloadAnswerList();
						} else {
							alert("Answer could NOT be deleted!\n\n"+$return);
						}
						document.body.style.cursor	= 'default';
						return false;
					});
				}
			}
		}
		return false;
	});

	// What happens when user change question's status
	$("#status").live("change", function() {
		$id_status		= $(this).val();
		$id_question	= $("#parent_id").val();
		$.post('/questionmaster/Questions/changeQuestionStatus/', {
			id_question:	$id_question,
			id_status:		$id_status
		}, function($return) {
			if ($return != 'ok') {
				alert("Question status could NOT be changed:\n\n"+$return);
			}
			document.body.style.cursor	= 'default';
			return false;
		});
		return false;
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
					$("#id_course").html('<option value="0">-</option>');
					$("#courses").html('');
					$("#courses_arr").val('');
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

	// What happens when user selects a field for input
	$("#id_field").live("change", function() {
		document.body.style.cursor	= 'wait';
		$("#courses").html('');
		$("#courses_arr").val('');
		$id_field	= $(this).val();
		if (($id_field) && ($id_field != 0)) {
			$.post('/questionmaster/Questions/loadCourses/', {
				id_field:	$id_field
			}, function($data) {
				if ($data) {
					$("#id_course").html($data);
				} else {
					alert("ATENTION:\n\nThere are no Courses for the chosen Field.\n\nTo Proceed, please add a Course to it.");
				}
				document.body.style.cursor = 'default';
				return false;
			});
		} else if ($id_field == 0) {
			$("#id_course").html('<option value="0">-</option>');
		}
		document.body.style.cursor	= 'default';
		return false;
	});

	// What happens when user selects a course for input
	$("#id_course").live("change", function() {
		$id_course	= $(this).val();
		$label		= $("#id_course option[value='"+$id_course+"']").text();
		$courses	= $("#courses_arr").val();
		if (($id_course) && ($label)) {
			$('option:selected', this).remove();
			if (!$courses) {
				$("#courses_arr").val($id_course);
			} else {
				$("#courses_arr").val($courses+"|"+$id_course);
			}
			$courses_html	= $("#courses").html();
			$courses_html	= $courses_html + '<div id="'+$id_course+'">&bull;&nbsp;'+$label+' (<a href="#" class="text_01 remove_field" key="'+$id_course+'">X</a>)</div>';
			contentShowData("#courses", $courses_html)
			$("#courses").val('');
			$("#form_step_2").show(400);
		}
		return false;
	});

	// What happens when user click on "X" to remove a field
	$(".remove_field").live("click", function() {
		document.body.style.cursor	= 'wait';
		$courses_arr		= $("#courses_arr").val();
		$key				= $(this).attr('key');
		$courses_arr		= $courses_arr.replace($key, '');
		$courses_arr		= $courses_arr.replace('||', '|');
		if ($courses_arr.charAt(0) == '|') {
			$courses_arr	= $courses_arr.substring(1);
		}
		if ($courses_arr.charAt($courses_arr.length - 1) == '|') {
			$courses_arr	= $courses_arr.substring(0, $courses_arr.length - 1);
		}
		$(this).parent().hide();
		if ($courses_arr != '') {
			$("#courses_arr").val($courses_arr);
		} else {
			$("#courses_arr").val('');
		}
		document.body.style.cursor	= 'default';
		return false;
	});

	// What happens when user click on "X" to remove a course
	$(".remove_field_det").live("click", function() {
		document.body.style.cursor	= 'wait';
		$courses_arr		= $("#courses_arr").val();
		$key				= $(this).attr('key');
		$div_id				= '#'+$(this).parent().attr('id');
		$id_question		= $("#parent_id").val();
		$courses_arr		= $courses_arr.replace($key, '');
		$courses_arr		= $courses_arr.replace('||', '|');
		if ($courses_arr.charAt(0) == '|') {
			$courses_arr	= $courses_arr.substring(1);
		}
		if ($courses_arr.charAt($courses_arr.length - 1) == '|') {
			$courses_arr	= $courses_arr.substring(0, $courses_arr.length - 1);
		}
		$.post('/questionmaster/Questions/updateCourses', {
			id_question:	$id_question,
			courses_arr:	$courses_arr
		}, function($data) {
			$return 		= $data.trim();
			if ($return == 'ok') {
				$($div_id).hide();
				if ($courses_arr != '') {
					$("#courses_arr").val($courses_arr);
				} else {
					$("#courses_arr").val('');
				}
				$.post('/questionmaster/Questions/comboCourses/', {
					id_question:	$id_question,
				}, function($data) {
					$return 		= $data.trim();
					if ($return) {
						$("#id_course_add").html($return);
					}
					document.body.style.cursor	= 'default';
					return false;
				});
			} else {
				alert("It wasn't posible to remove this Course!");
			}
			document.body.style.cursor	= 'default';
			return false;
		});
		document.body.style.cursor	= 'default';
		return false;
	});

	// What happens when user submit new Question
	$(".new_question").live("click", function() {
		$courses		= $("#courses_arr").val();
		$insert_type	= $("#insert_type").val();
		$id_status		= $("#id_status").val();
		$tx_question	= $("#tx_question").val().trim();
		$tx_tutor		= $("#tx_tutor").val().trim();
		if (($courses) && ($id_status) && ($tx_question) && ($tx_tutor)) {
			$.post('/questionmaster/Questions/addQuestion', {
				courses:		$courses,
				id_status:		$id_status,
				tx_question:	$tx_question,
				tx_tutor:		$tx_tutor
			}, function($data) {
				$return = $data.trim();
				if (($.isNumeric($return)) && ($.isNumeric($return) > 0)) {
					$("#formInsertCourse").hide();
					$("#main_title").hide();
					/* $("#id_branch option[value='0']").attr('selected', 'selected');
					$("#id_field").html('<option value="0">-</option>');
					$("#id_course").html('<option value="0">-</option>'); */
					$("#id_status option[value='0']").attr('selected', 'selected');
					// $("#courses_arr").val('');
					//$("#courses").html('');
					$("#tx_question").val('');
					$("#tx_tutor").val('');
					if ($insert_type == 'repeat') {
						contentShowData("#message_area", '<span class="title_01">New Question succefully created!!<br /><br /><a href="#" class="text_01 show_answer_form_course" key="'+$return+'"><u>Add Answers to this Question</u></a><br /><a href="#" class="text_01 show_form"><u>Add another Question to this Course.</u></a>');
					} else {
						contentShowData("#message_area", '<span class="title_01">New Question succefully created!!<br /><br /><a href="#" class="text_01 show_answer_form" key="'+$return+'"><u>Add Answers to this Question</u></a><br /><a href="#" class="text_01 show_form"><u>Add another Question</u></a>');
					}
				} else {
					
				}
			});
		} else {
			alert("Please, check Questions's info.");
		}
		return false;
	});

	// What happens when user clicks on "Add another Question"
	$(".show_form").live("click", function() {
		$("#message_area").hide();
		$("#message_area").html('');
		$("#main_title").show();
		$("#formInsertCourse").show();
	});

	// what happens when user clicks on "add course"
	$("#add_courses").live("click", function() {
		$question_id	= $("#parent_id").val();
		$(location).attr('href', '/questionmaster/Questions/update/'+$question_id);
	});
});

function reloadAnswerList() {
	document.body.style.cursor	= 'wait';
	$key		= $('#pager_pg_num').val();
	$ordering	= $('#ordering').val();
	$offset		= $('#offset').val();
	$limit		= $('#limit').val();
	$direction	= $('#direction').val();
	$direction	= $('#direction').val();
	$str_search	= $('#str_search').val();
	$parent_id	= $('#parent_id').val();
	$actionurl	= actionURL();
	if (($key) && ($ordering) && ($offset) && ($limit) && ($direction) && ($actionurl)) {
		fetchResults($actionurl, $key, $ordering, $offset, $limit, $direction, $str_search, $parent_id);
	}
}