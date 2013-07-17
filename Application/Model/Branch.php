<?php
/************************************************************************************
* Name:				Branch Model													*
* File:				Application\Model\Branch.php 									*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This is the Branch's model.										*
*																					*
* Creation Date:	09/05/2013														*
* Version:			1.13.0710														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Model;

	class Branch {

		public function listBranches($entries = false, $ordering = false, $direction = false) {
			$return				= false;
			if ($entries) {
				$return			= $this->resultHeader($ordering, $direction);
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$return		.= '<div class="return_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$return		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_branch">'.$entries[$i]['vc_branch'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_id">'.$entries[$i]['tot_fields'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_courses">'.$entries[$i]['tot_courses'].'</div>'.PHP_EOL;
					$return		.= '</div><br />'.PHP_EOL;
				}
			}
			return $return;
		}

		public function listFields($entries = false, $ordering = false, $direction = false) {
			$return	= false;
			if ($entries) {
				$return			= $this->resultHeaderFields($ordering, $direction);
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$return		.= '<div class="details_return_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$return		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_vc_field">'.$entries[$i]['vc_field'].'</div>'.PHP_EOL;
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

		public function jqueryBranches($entries = false, $pager = false, $ordering = 'id', $direction = 'ASC') {
			$return				= false;
			$rows				= '';
			if (($entries) && ($pager)) {
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$rows		.= '<div class="return_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$rows		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_branch">'.$entries[$i]['vc_branch'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_id">'.$entries[$i]['tot_fields'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_courses">'.$entries[$i]['tot_courses'].'</div>'.PHP_EOL;
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

		public function jqueryFields($entries = false, $pager = false, $ordering = 'id', $direction = 'ASC') {
			$return				= false;
			$rows				= '';
			if (($entries) && ($pager)) {
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$rows		.= '<div class="details_return_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$rows		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_vc_field">'.$entries[$i]['vc_field'].'</div>'.PHP_EOL;
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
				$return			.= '<div class="details_result_box" id="result_box">'.PHP_EOL;
				$return			.= $this->resultHeaderFields($ordering, $direction).PHP_EOL;
				$return			.= $rows.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
				$return			.= '<div class="navigation_box" id="down_nav_box">'.PHP_EOL;
				$return			.= $pager.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
			}
			return $return;
		}

		public function fieldDetails($field = false) {
			$return				= false;
			$tot_questions		= 0;
			$graph				= false;
			if ($field) {
				if ($field[2]) {
					foreach ($field[2] as $course) {
						$tot_questions	= $tot_questions + $course['total'];
						$graph	.= '						'.$course['vc_course'].': '.$course['total'].' questions<br />'.PHP_EOL;
					}
				}
				$return			.= '<div class="details_text">'.PHP_EOL;
				$return			.= '						<input type="hidden" name="id_field" id="id_field" value="'.$field[0]['id'].'">'.PHP_EOL;
				$return			.= '						<div style="vertical-align: middle; height: 40px;">'.PHP_EOL;
				$return			.= '							<div style="float: left; margin-right: 10px;"><img src="/questionmaster/Application/View/img/bt_bullet.png" width="41" height="41" border="0" /></div>'.PHP_EOL;
				$return			.= '							<div class="title_04 edit_label" id="field_title" key="vc_field" style="padding-top: 6px;">'.$field[0]['vc_field'].'</div>'.PHP_EOL;
				$return			.= '							<div class="details_edit_field" id="details_edit_field" style="display: none;"><input type="text" name="vc_field" id="vc_field" class="vc_field" key="'.$field[0]['id'].'" value="'.$field[0]['vc_field'].'" /></div>'.PHP_EOL;
				$return			.= '							<div class="delete_field" id="delete_field"><img src="/questionmaster/Application/View/img/bt_trashcan.png" width="20" height="20" border="0" />&nbsp;Delete</div>'.PHP_EOL;
				$return			.= '							<div>'.PHP_EOL;
				$return			.= '								<select name="field_status" id="field_status" class="main_text_input" style="width: 100px;">'.PHP_EOL;
				if ($field[0]['boo_active'] == 1) {
					$return		.= '									<option value="1" selected="selected">Active</option>'.PHP_EOL;
					$return		.= '									<option value="0">Inactive</option>'.PHP_EOL;
				} else {
					$return		.= '									<option value="1">Active</option>'.PHP_EOL;
					$return		.= '									<option value="0" selected="selected">Inactive</option>'.PHP_EOL;
				}
				$return			.= '								</select>'.PHP_EOL;
				$return			.= '							</div>'.PHP_EOL;
				$return			.= '							<br />'.PHP_EOL;
				$return			.= '							<div class="text_01" style="width: 130px; float: left; text-align: right;">'.PHP_EOL;
				$return			.= '								Total courses:<br>'.PHP_EOL;
				$return			.= '								Total questions:'.PHP_EOL;
				$return			.= '							</div>'.PHP_EOL;
				$return			.= '							<div class="text_01" style="width: 130px; float: right; text-align: left;">'.PHP_EOL;
				$return			.= '								'.$field[1]['total'].'<br>'.PHP_EOL;
				$return			.= '								'.$tot_questions.PHP_EOL;
				$return			.= '							</div>'.PHP_EOL;
				$return			.= '						</div>'.PHP_EOL;
				$return			.= '					</div>'.PHP_EOL;
				if ($graph) {
					$return		.= '					<div class="details_graph">'.PHP_EOL;
					$return		.= $graph;
					$return		.= '					</div>'.PHP_EOL;
				}
			}
			return $return;
		}

		private	function resultHeader($ordering = 'b.id', $direction = 'ASC') {
			$return		= '<div class="result_header">'.PHP_EOL;
			if (($ordering == 'b.id') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_id" key="b.id" direction="DESC"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else if (($ordering == 'b.id') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_id" key="b.id" direction="ASC"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_id" key="b.id" direction="ASC">ID</div>'.PHP_EOL;
			}
			if (($ordering == 'b.vc_branch') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_branch"  key="b.vc_branch" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Branch</div>'.PHP_EOL;
			} else if (($ordering == 'b.vc_branch') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_branch"  key="b.vc_branch" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Branch</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_branch"  key="b.vc_branch" direction="ASC" style="text-align: center;">Branch</div>'.PHP_EOL;
			}
			if (($ordering == 'tot_fields') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_id"  key="tot_fields" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Fields</div>'.PHP_EOL;
			} else if (($ordering == 'tot_fields') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_id"  key="tot_fields" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Fields</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_id"  key="tot_fields" direction="ASC" style="text-align: center;">Fields</div>'.PHP_EOL;
			}
			if (($ordering == 'tot_courses') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_courses"  key="tot_courses" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Courses</div>'.PHP_EOL;
			} else if (($ordering == 'tot_courses') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_courses"  key="tot_courses" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Courses</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_courses"  key="tot_courses" direction="ASC" style="text-align: center;">Courses</div>'.PHP_EOL;
			}
			$return	.= '</div><br />'.PHP_EOL;
			return $return;
		}

		private	function resultHeaderFields($ordering = 'id', $direction = 'ASC') {
			$return		= '<div class="result_header">'.PHP_EOL;
			if (($ordering == 'id') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_id" key="id" direction="DESC"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else if (($ordering == 'id') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_id" key="id" direction="ASC"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_id" key="id" direction="ASC">ID</div>'.PHP_EOL;
			}
			if (($ordering == 'vc_field') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_branch"  key="vc_field" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Field</div>'.PHP_EOL;
			} else if (($ordering == 'vc_field') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_branch"  key="vc_field" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Field</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_branch"  key="vc_field" direction="ASC" style="text-align: center;">Field</div>'.PHP_EOL;
			}
			if (($ordering == 'boo_active') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_field result_active"  key="boo_active" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Status</div>'.PHP_EOL;
			} else if (($ordering == 'vc_field') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_field result_active"  key="boo_active" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Status</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_field result_active"  key="boo_active" direction="ASC" style="text-align: center;">Status</div>'.PHP_EOL;
			}
			$return	.= '</div><br />'.PHP_EOL;
			return $return;
		}

	}