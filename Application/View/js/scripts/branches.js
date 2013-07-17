$('document').ready(function() {

	// What happens click on "add field of study"
	$(".add_fields").live("click", function() {
		$key	= $(this).attr('key');
		$fields	= $("#fields_arr").val();
		$field	= $("#field").val();
		if (($key) && ($field)) {
			if (!$fields) {
				$("#fields_arr").val($field);
			} else {
				$("#fields_arr").val($fields+"|"+$field);
			}
			$fields_html	= $("#fields").html();
			$fields_html	= $fields_html + '&bull;&nbsp;'+$field+' (<a href="#" class="text_01 remove_field" key="'+$field+'">X</a>)<br />';
			contentShowData("#fields", $fields_html)
			$("#field").val('');
			$("#"+$key).show(400);
		}
		return false;
	});

	// What happens when user submit new branch
	$(".new_branch").live("click", function() {
		$branch		= $("#branch").val().trim();
		$field_arr	= $("#fields_arr").val().trim();
		if (($branch) && ($field_arr)) {
			$.post('/questionmaster/Branches/addBranchFields', {branch: $branch, fields: $field_arr}, function($data) {
				$return = $data.trim();
				if ($return	== 'ok') {
					$("#formInsertBranch").hide();
					$("#branch").val('');
					$("#fields_arr").val('');
					$("#field").val('');
					$("#fields").html('');
					$("#form_step_1").hide();
					$("#form_step_2").hide();
					$("#message_area").hide();
					$("#main_title").hide();
					contentShowData("#message_area", '<span class="title_01">New Branch succefully created!!<br /><br /><a href="#" class="text_01 show_form"><u>Add another branch</u></a>');
				}
			});
		} else {
			alert("Please, check Branch's name\nand if it has at least one field.");
		}
		return false;
	});

	// What happens when user clicks on Cancel button
	$(".bt_cancel").live("click", function() {
		$("#formInsertBranch").hide();
		$("#branch").val('');
		$("#fields_arr").val('');
		$("#field").val('');
		$("#fields").html('');
		$("#form_step_1").hide();
		$("#form_step_2").hide();
		$("#message_area").hide();
		$("#message_area").html('');
		$("#bt_dropdown").attr('src', '/questionmaster/Application/View/img/bt_dropdown_on.png');
		$("#formInsertBranch").show();
	});

	// What happens when user click on "Add another branch"
	$(".show_form").live("click", function() {
		$("#message_area").hide();
		$("#message_area").html('');
		$("#main_title").show();
		$("#bt_dropdown").attr('src', '/questionmaster/Application/View/img/bt_dropdown_on.png');
		$("#formInsertBranch").show();
	});

	// What happens when user click on "X" to remove a field
	// from the list on thw new branch form
	$(".remove_field").live("click", function() {
		$fields_arr				= $("#fields_arr").val();
		$key					= $(this).attr('key');
		$fields_arr				= $fields_arr.replace($key, '');
		$fields_arr				= $fields_arr.replace('||', '|');
		$fields_html			= '';
		if ($fields_arr.charAt(0) == '|') {
			$fields_arr			= $fields_arr.substring(1);
		}
		if ($fields_arr.charAt($fields_arr.length - 1) == '|') {
			$fields_arr			= $fields_arr.substring(0, $fields_arr.length - 1);
		}
		$fields					= $fields_arr.split("|");
		if ($fields != '') {
			$tot_fields			= $fields.length;
			for ($i = 0; $i < $tot_fields; $i++) {
				$fields_html	= $fields_html+'&bull;&nbsp;'+$fields[$i]+' (<a href="#" class="text_01 remove_field" key="'+$fields[$i]+'">X</a>)<br />';
			}
			$("#fields_arr").val($fields_arr);
		} else {
			$fields_html		= '';
			$("#fields_arr").val('');
		}
		contentShowData("#fields", $fields_html)
		return false;
	});

	// What happens when user click on database result line
	$(".return_row").live("click", function() {
		$key		= $(this).attr('key');
		if ($key) {
			openFancybox('/questionmaster/Branches/details/'+$key, 800, 500);
		}
		return false;
	});

	// What happens when user click on database result line
	$(".details_return_row").live("click", function() {
		document.body.style.cursor	= 'wait';
		$(".details_return_row_this").attr('class', 'details_return_row');
		$(this).attr('class', 'details_return_row_this');
		$key		= $(this).attr('key');
		$.post('/questionmaster/Branches/fieldDetails/', {
			id_field:	$key
		}, function($data) {
			contentShowData("#field_details", $data)
			document.body.style.cursor = 'default';
			return false;
		});
		document.body.style.cursor	= 'default';
		return false;
	});

	// What happens when user double clicks Branch or Field name on details (edit)
	$(".edit_label").live("dblclick", function() {
		$this_label	= $(this).attr('key');
		$(this).hide();
		if ($this_label == 'vc_branch') {
			$("#details_edit_title").show();
			$("#vc_branch").focus();
			$("#vc_branch").select();
		} else if ($this_label == 'vc_field') {
			$("#field_status").hide();
			$("#details_edit_field").show();
			$("#vc_field").focus();
			$("#vc_field").select();
		}
		return false;
	});

	// What happens when user alters Branch name
	$(".vc_branch").live("keypress", function (e) {
		if (e.keyCode == 13) {
			document.body.style.cursor = 'wait';
			$id_branch	= $("#parent_id").val();
			$vc_branch	= $(this).val();
			$old_branch	= $("#details_title").html();
			if ($vc_branch) {
				$.post('/questionmaster/Branches/updateBranch/', {
					id_branch:	$id_branch,
					vc_branch:	$vc_branch
				}, function($data) {
					$("#details_edit_title").hide();
					contentShowData("#details_title", $data)
					document.body.style.cursor = 'default';
					return false;
				});
			} else {
				alert('Please,\n\nenter a valid branch name.');
				$("#vc_branch").val($old_branch);
				$("#vc_branch").select();
				document.body.style.cursor = 'default';
			}
			return false;
		}
	});

	// What happens when user alters Field name
	$(".vc_field").live("keypress", function (e) {
		if (e.keyCode == 13) {
			document.body.style.cursor = 'wait';
			$id_field	= $(this).attr('key');
			$vc_field	= $(this).val();
			$old_field	= $("#details_title").html();
			if ($vc_field) {
				$.post('/questionmaster/Branches/updateField/', {
					id_field:	$id_field,
					vc_field:	$vc_field
				}, function($data) {
					$("#details_edit_field").hide();
					contentShowData("#field_title", $data)
					$("#field_status").show();
					reloadFieldList();
					document.body.style.cursor = 'default';
					return false;
				});
			} else {
				alert('Please,\n\nenter a valid field name.');
				$("#vc_field").val($old_field);
				$("#vc_field").select();
				document.body.style.cursor = 'default';
			}
			return false;
		}
	});

	// What happens when user tries to delete a branch
	$(".delete_branch").live("click", function () {
		$res					= confirm("ATENTION,\n\nThis will also erase:\n\n- fields\n- courses\n- questions\n\nthat are associated with this Branch.\n\nAre you sure you want to continue??\n(this process may take some seconds)");
		if ($res) {
			document.body.style.cursor	= 'wait';
			$id_branch			= $("#parent_id").val();
			if ($id_branch) {
				$.post('/questionmaster/Branches/deleteBranch/', {
					id_branch:	$id_branch
				}, function($return) {
					if ($return == 'ok') {
						alert("Branch and related information were succefully deleted!");
						document.body.style.cursor	= 'default';
						parent.$.fancybox.close();
					} else {
						alert("Branch could NOT be deleted!\n\n"+$return);
						document.body.style.cursor	= 'default';
					}
					return false;
				});
			}
		}
		document.body.style.cursor	= 'default';
	});

	// What happens when user tries to delete a field
	$(".delete_field").live("click", function () {
		$res					= confirm("ATENTION,\n\nThis will also erase:\n\n- courses\n- questions\n\nthat are associated with this Field.\n\nAre you sure you want to continue??\n(this process may take some seconds)");
		if ($res) {
			document.body.style.cursor	= 'wait';
			$id_field			= $("#id_field").val();
			if ($id_field) {
				$.post('/questionmaster/Branches/deleteField/', {
					id_field:	$id_field
				}, function($return) {
					if ($return == 'ok') {
						alert("Field and related information were succefully deleted!");
						document.body.style.cursor	= 'default';
						parent.$.fancybox.close();
					} else {
						alert("Field could NOT be deleted!\n\n"+$return);
						document.body.style.cursor	= 'default';
					}
					return false;
				});
			}
		}
		document.body.style.cursor	= 'default';
	});

	// What happens when user adds a field to existing branch
	$("#new_field").live("keypress", function(e) {
		if (e.keyCode == 13) {
			document.body.style.cursor	= 'wait';
			$new_field	= $(this).val();
			$id_branch	= $("#parent_id").val();
			if (($new_field) && ($id_branch)) {
				$.post('/questionmaster/Branches/addField/', {
					id_branch:	$id_branch,
					vc_field:	$new_field
				}, function($data) {
					$return		= $data.trim();
					if ($return	== 'ok') {
						alert('Field successfully inserted!');
						reloadFieldList();
						$(this).val();
					} else {
						alert('deu erro');
					}
					document.body.style.cursor	= 'default';
					return false;
				});
			}
			document.body.style.cursor	= 'default';
			return false;
		}
	});

	// What happens when user changes field's status
	$("#field_status").live("change", function() {
		$status		= $(this).val();
		$id_field	= $("#id_field").val();
		if ($id_field) {
			$.post('/questionmaster/Branches/changeFieldStatus/', {
				id_field:	$id_field,
				boo_active:	$status
			}, function($data) {
				$return		= $data.trim();
				if ($return	== 'act') {
					alert('This field is now ACTIVE!');
					reloadFieldList();
				} else if ($return	== 'ina') {
					alert('This field is now INACTIVE!');
					reloadFieldList();
				} else {
					alert("Sorry,\n\nIt wasn't possible to change this field's status.\n\nError: " + $return);
				}
				document.body.style.cursor	= 'default';
				return false;
			});
		} else {
			alert("Sorry,\n\nIt wasn't possible to change this field's status");
		}
		return false;
	});

});

function reloadFieldList() {
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

