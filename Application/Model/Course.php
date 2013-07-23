<?php
/************************************************************************************
* Name:				Course Model													*
* File:				Application\Model\Course.php 									*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This is the Course's model.										*
*																					*
* Creation Date:	23/05/2013														*
* Version:			1.13.0523														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Model;

	class Course {

		public function listCourses($entries = false, $ordering = false, $direction = false) {
			$return				= false;
			if ($entries) {
				$return			= $this->resultHeader($ordering, $direction);
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$return		.= '<div class="return_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$return		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_branch">'.$entries[$i]['vc_branch'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_vc_field">'.$entries[$i]['vc_field'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_course">'.$entries[$i]['vc_course'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_level">'.$entries[$i]['int_level'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_totquestions">'.$entries[$i]['total_questions'].'</div>'.PHP_EOL;
					if ($entries[$i]['boo_active'] == 1) {
						$return	.= '	<div class="result_field result_active" key="0"><span class="active_label">Active</span></div>'.PHP_EOL;
					} else {
						$return	.= '	<div class="result_field result_active inactive_label" key="1"><span class="inactive_label">Inactive</span></div>'.PHP_EOL;
					}
					$return		.= '</div><br />'.PHP_EOL;
				}
			}
			return $return;
		}

		private	function resultHeader($ordering = 'c.id', $direction = 'ASC') {
			$return		= '<div class="result_header">'.PHP_EOL;
			if (($ordering == 'c.id') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_id" key="c.id" direction="DESC"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else if (($ordering == 'c.id') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_id" key="c.id" direction="ASC"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_id" key="c.id" direction="ASC">ID</div>'.PHP_EOL;
			}
			if (($ordering == 'b.vc_branch') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_branch"  key="b.vc_branch" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Branch</div>'.PHP_EOL;
			} else if (($ordering == 'b.vc_branch') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_branch"  key="b.vc_branch" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Branch</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_branch"  key="b.vc_branch" direction="ASC" style="text-align: center;">Branch</div>'.PHP_EOL;
			}
			if (($ordering == 'f.vc_field') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_vc_field"  key="f.vc_field" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Field</div>'.PHP_EOL;
			} else if (($ordering == 'f.vc_field') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_vc_field"  key="f.vc_field" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Field</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_vc_field"  key="f.vc_field" direction="ASC" style="text-align: center;">Field</div>'.PHP_EOL;
			}
			if (($ordering == 'c.vc_course') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_course"  key="c.vc_course" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Course</div>'.PHP_EOL;
			} else if (($ordering == 'c.vc_course') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_course"  key="c.vc_course" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Course</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_course"  key="c.vc_course" direction="ASC" style="text-align: center;">Course</div>'.PHP_EOL;
			}
			if (($ordering == 'c.int_level') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_level"  key="c.int_level" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Level</div>'.PHP_EOL;
			} else if (($ordering == 'c.int_level') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_level"  key="c.int_level" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Level</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_level"  key="c.int_level" direction="ASC" style="text-align: center;">Level</div>'.PHP_EOL;
			}
			if (($ordering == 'total_questions') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_totquestions"  key="total_questions" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Questions</div>'.PHP_EOL;
			} else if (($ordering == 'total_questions') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_totquestions"  key="total_questions" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Questions</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_totquestions"  key="total_questions" direction="ASC" style="text-align: center;">Questions</div>'.PHP_EOL;
			}
			if (($ordering == 'c.boo_active') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_active"  key="c.boo_active" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Status</div>'.PHP_EOL;
			} else if (($ordering == 'c.boo_active') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_active"  key="c.boo_active" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Status</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_active"  key="c.boo_active" direction="ASC" style="text-align: center;">Status</div>'.PHP_EOL;
			}
			$return	.= '</div><br />'.PHP_EOL;
			return $return;
		}

		public function jqueryCourses($entries = false, $pager = false, $ordering = 'c.id', $direction = 'ASC') {
			$return				= false;
			$rows				= '';
			if (($entries) && ($pager)) {
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$rows		.= '<div class="return_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$rows		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_branch">'.$entries[$i]['vc_branch'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_vc_field">'.$entries[$i]['vc_field'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_course">'.$entries[$i]['vc_course'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_level">'.$entries[$i]['int_level'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_totquestions">'.$entries[$i]['total_questions'].'</div>'.PHP_EOL;
					if ($entries[$i]['boo_active'] == 1) {
						$rows	.= '	<div class="result_field result_active" key="0"><span class="active_label">Active</span></div>'.PHP_EOL;
					} else {
						$rows	.= '	<div class="result_field result_active inactive_label" key="1"><span class="inactive_label">Inactive</span></div>'.PHP_EOL;
					}
					$rows		.= '</div><br />'.PHP_EOL;
				}
				$return			.= '<div class="navigation_box" id="up_nav_box">'.PHP_EOL;
				$return			.= $pager.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
				$return			.= '<div class="result_box" id="result_box">'.PHP_EOL;
				$return			.= $this->resultHeader($ordering, $direction).PHP_EOL;
				$return			.= $rows.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
				$return			.= '<div class="navigation_box" id="down_nav_box">'.PHP_EOL;
				$return			.= $pager.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
			}
			return $return;
		}

		public function listBranchOptions($branches = false, $id_branch = false) {
			$return				= false;
			if ($branches) {
				foreach ($branches as $branch) {
					if ($id_branch == $branch['id']) {
						$return	.= '	<option value="'.$branch['id'].'" selected="selected">'.$branch['vc_branch'].'</option>'.PHP_EOL;
					} else {
						$return	.= '	<option value="'.$branch['id'].'">'.$branch['vc_branch'].'</option>'.PHP_EOL;
					}
				}
			} else {
				$return			= '	<option value="0">No branch found</option>'.PHP_EOL;
			}
			return $return;
		}

		public function listFieldOptions($fields = false, $id_field = false) {
			$return				= false;
			if ($fields) {
				$return			.= '<option value="0">-</option>'.PHP_EOL;
				foreach ($fields as $field) {
					if ($id_field == $field['id']) {
						$return	.= '	<option value="'.$field['id'].'" selected="selected">'.$field['vc_field'].'</option>'.PHP_EOL;
					} else {
						$return	.= '	<option value="'.$field['id'].'">'.$field['vc_field'].'</option>'.PHP_EOL;
					}
				}
			} else {
				$return			= '	<option value="0">No field found</option>'.PHP_EOL;
			}
			return $return;
		}

		public function listStatus($status = false) {
			$return 	= false;
			if ($status == 1) {
				$return	.= '<option value="1" selected="selected">Active</option>'.PHP_EOL;
				$return	.= '<option value="0">Inactive</option>'.PHP_EOL;
			} else {
				$return	.= '<option value="1">Active</option>'.PHP_EOL;
				$return	.= '<option value="0" selected="selected">Inactive</option>'.PHP_EOL;
			}
			return $return;
		}

	}