<?php
/************************************************************************************
* Name:				Question Model													*
* File:				Application\Model\Question.php 									*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This is the Question's model.									*
*																					*
* Creation Date:	29/05/2013														*
* Version:			1.13.0529														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Model;

	class Question {

		public function listQuestions($entries = false, $ordering = false, $direction = false) {
			$return				= false;
			if ($entries) {
				$return			= $this->resultHeader($ordering, $direction);
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$return		.= '<div class="return_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$return		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_status">'.$entries[$i]['vc_status'].'</div>'.PHP_EOL;
					$dots		= (strlen($entries[$i]['tx_question']) > 60) ? '...' : '';
					$return		.= '	<div class="result_field result_question">'.substr(str_replace("\n", "", $entries[$i]['tx_question']), 0, 60).$dots.'</div>'.PHP_EOL;
					//$return		.= '	<div class="result_field result_answers">'.$entries[$i]['answers'].'</div>'.PHP_EOL;
					$return		.= '</div><br />'.PHP_EOL;
				}
			}
			return $return;
		}

		public function listAnswers($entries = false, $ordering = false, $direction = false) {
			$return	= '<br />No answers found'.PHP_EOL;
			if ($entries) {
				$return			= $this->resultHeaderAnswers($ordering, $direction);
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$return		.= '<div class="details_return_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$return		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_answer">'.$entries[$i]['vc_answer'].'</div>'.PHP_EOL;
					if ($entries[$i]['boo_correct'] == 1) {
						$return	.= '	<div class="result_field result_correct"><span class="active_label">Correct</span></div>'.PHP_EOL;
					} else {
						$return	.= '	<div class="result_field result_correct inactive_label"><span class="inactive_label">Incorrect</span></div>'.PHP_EOL;
					}
					$return		.= '</div><br />'.PHP_EOL;
				}
			}
			return $return;
		}

		public function jqueryQuestions($entries = false, $pager = false, $ordering = 'q.id', $direction = 'ASC') {
			$return				= false;
			$rows				= '';
			if (($entries) && ($pager)) {
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$rows		.= '<div class="return_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$rows		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_status">'.$entries[$i]['vc_status'].'</div>'.PHP_EOL;
					$dots		= (strlen($entries[$i]['tx_question']) > 60) ? '...' : '';
					$rows		.= '	<div class="result_field result_question">'.substr(str_replace("\n", "", $entries[$i]['tx_question']), 0, 60).$dots.'</div>'.PHP_EOL;
					//$rows		.= '	<div class="result_field result_answers">'.$entries[$i]['answers'].'</div>'.PHP_EOL;
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

		public function jqueryAnswers($entries = false, $pager = false, $ordering = 'q.id', $direction = 'ASC') {
			$return				= false;
			$rows				= '';
			if (($entries) && ($pager)) {
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$rows		.= '<div class="details_return_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$rows		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_answer">'.$entries[$i]['vc_answer'].'</div>'.PHP_EOL;
					if ($entries[$i]['boo_correct'] == 1) {
						$rows	.= '	<div class="result_field result_correct"><span class="active_label">Correct</span></div>'.PHP_EOL;
					} else {
						$rows	.= '	<div class="result_field result_correct inactive_label"><span class="inactive_label">Incorrect</span></div>'.PHP_EOL;
					}
					$rows		.= '</div><br />'.PHP_EOL;
				}
				$return			.= '<div class="navigation_box" id="up_nav_box">'.PHP_EOL;
				$return			.= $pager.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
				$return			.= '<div class="details_result_box" id="result_box">'.PHP_EOL;
				$return			.= $this->resultHeaderAnswers($ordering, $direction).PHP_EOL;
				$return			.= $rows.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
				$return			.= '<div class="navigation_box" id="down_nav_box">'.PHP_EOL;
				$return			.= $pager.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
			}
			return $return;
		}

		public function answerDetails($answer = false) {
			$return				= false;
			$tot_questions		= 0;
			if ($answer) {
				$return			.= '<input type="hidden" name="id_answer" id="id_answer" value="'.$answer['id'].'">'.PHP_EOL;
				$return			.= '					<input type="hidden" name="old_correct" id="old_correct" value="'.$answer['boo_correct'].'">'.PHP_EOL;
				$return			.= '					<div class="details_text">'.PHP_EOL;
				$return			.= '						<div style="float: left;">'.PHP_EOL;
				$return			.= '							<div>'.PHP_EOL;
				$return			.= '								<span class="title_04">Answer:</span>'.PHP_EOL;
				$return			.= '								<input type="text" name="vc_answer" id="vc_answer" class="thi_text_input" value="'.$answer['vc_answer'].'">'.PHP_EOL;
				$return			.= '							</div>'.PHP_EOL;
				$return			.= '							<br />'.PHP_EOL;
				$return			.= '							<div>'.PHP_EOL;
				$return			.= '								<span class="title_04">This is:</span>'.PHP_EOL;
				$return			.= '							<select name="boo_correct" id="boo_correct" class="tiny_text_input">'.PHP_EOL;
				if ($answer['boo_correct'] == 1) {
					$return		.= '								<option value="1" selected="selected">Correct</option>'.PHP_EOL;
					$return		.= '								<option value="0">Incorrect</option>'.PHP_EOL;
				} else {
					$return		.= '								<option value="1">Correct</option>'.PHP_EOL;
					$return		.= '								<option value="0" selected="selected">Incorrect</option>'.PHP_EOL;
				}
				$return			.= '							</select>'.PHP_EOL;
				$return			.= '							</div>'.PHP_EOL;
				$return			.= '						</div>'.PHP_EOL;
				$return			.= '						<div class="updt_answer">'.PHP_EOL;
				$return			.= '							<img src="/questionmaster/Application/View/img/bt_ok.png" width="41" height="41" border="0" />'.PHP_EOL;
				$return			.= '							<div class="delete_answer">'.PHP_EOL;
				$return			.= '								<img src="/questionmaster/Application/View/img/bt_trashcan.png" width="20" height="20" border="0" />&nbsp;Delete'.PHP_EOL;
				$return			.= '							</div>'.PHP_EOL;
				$return			.= '						</div>'.PHP_EOL;
			}
			return $return;
		}

		public function comboSelect($value = false, $caption = false) {
			$return	= false;
			if (($value) && ($caption)) {
				$return	.= '<option value="'.$value.'" selected="selected">'.$caption.'</option>'.PHP_EOL;
			}
			return $return;
		}

		public function statusCombo($all_status = false, $id_status = false) {
			$return	= false;
			if ($all_status) {
				$return			.= '<option value="0">Select...</option>'.PHP_EOL;
				foreach ($all_status as $status) {
					if ($id_status == $status['id']) {
						$return	.= '<option value="'.$status['id'].'" selected="selected">'.$status['vc_status'].'</option>'.PHP_EOL;
					} else {
						$return	.= '<option value="'.$status['id'].'">'.$status['vc_status'].'</option>'.PHP_EOL;
					}
				}
			}
			return $return;
		}

		public function branchesCombo($all_branches = false, $id_branch = false) {
			$return	= false;
			if ($all_branches) {
				$return			.= '<option value="0">-</option>'.PHP_EOL;
				foreach ($all_branches as $branch) {
					if ($id_branch == $branch['id']) {
						$return	.= '<option value="'.$branch['id'].'" selected="selected">'.$branch['vc_branch'].'</option>'.PHP_EOL;
					} else {
						$return	.= '<option value="'.$branch['id'].'">'.$branch['vc_branch'].'</option>'.PHP_EOL;
					}
				}
			}
			return $return;
		}

		public function coursesCombo($all_courses = false, $id_course = false) {
			$return				= false;
			if ($all_courses) {
				$return			.= '<option value="0">-</option>'.PHP_EOL;
				foreach ($all_courses as $course) {
					if ($id_course == $course['id']) {
						$return	.= '<option value="'.$course['id'].'" label="'.$course['vc_course'].'" selected="selected">'.$course['vc_course'].'</option>'.PHP_EOL;
					} else {
						$return	.= '<option value="'.$course['id'].'" label="'.$course['vc_course'].'">'.$course['vc_course'].'</option>'.PHP_EOL;
					}
				}
			}
			return $return;
		}

		public function listCourses($courses = false) {
			$return			= ' ';
			if ($courses) {
				foreach ($courses as $course) {
					$return	.= '<div id="'.$course['id_course'].'">&bull;&nbsp;<a href="#" class="text_01">'.$course['vc_course'].'</a> (<a href="#" class="text_01 remove_field_det" key="'.$course['id_course'].'">X</a>)</div>';
				}
			}
			return $return;
		}

		private	function resultHeader($ordering = 'q.id', $direction = 'ASC') {
			$return		= '<div class="result_header">'.PHP_EOL;
			if (($ordering == 'q.id') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_id" key="q.id" direction="DESC"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else if (($ordering == 'q.id') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_id" key="q.id" direction="ASC"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_id" key="q.id" direction="ASC">ID</div>'.PHP_EOL;
			}
			if (($ordering == 's.vc_status') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_status"  key="s.vc_status" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Status</div>'.PHP_EOL;
			} else if (($ordering == 's.vc_status') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_status"  key="s.vc_status" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Status</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_status"  key="s.vc_status" direction="ASC" style="text-align: center;">Status</div>'.PHP_EOL;
			}
			if (($ordering == 'q.tx_question') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_question"  key="q.tx_question" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Question</div>'.PHP_EOL;
			} else if (($ordering == 'q.tx_question') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_question"  key="q.tx_question" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Question</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_question"  key="q.tx_question" direction="ASC" style="text-align: center;">Question</div>'.PHP_EOL;
			}
			/* if (($ordering == 'answers') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_answers"  key="answers" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Answers</div>'.PHP_EOL;
			} else if (($ordering == 'answers') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_answers"  key="answers" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Answers</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_answers"  key="answers" direction="ASC" style="text-align: center;">Answers</div>'.PHP_EOL;
			} */
			$return	.= '</div><br />'.PHP_EOL;
			return $return;
		}

		private	function resultHeaderAnswers($ordering = 'id', $direction = 'ASC') {
			$return		= '<div class="result_header">'.PHP_EOL;
			if (($ordering == 'id') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_id" key="id" direction="DESC"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else if (($ordering == 'id') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_id" key="id" direction="ASC"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_id" key="id" direction="ASC">ID</div>'.PHP_EOL;
			}
			if (($ordering == 'vc_answer') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_answer"  key="vc_answer" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Answer</div>'.PHP_EOL;
			} else if (($ordering == 'vc_answer') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_answer"  key="vc_answer" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Answer</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_answer"  key="vc_answer" direction="ASC" style="text-align: center;">Answer</div>'.PHP_EOL;
			}
			if (($ordering == 'boo_correct') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_field result_correct"  key="boo_correct" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />This is</div>'.PHP_EOL;
			} else if (($ordering == 'boo_correct') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_field result_correct"  key="boo_correct" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />This is</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_field result_correct"  key="boo_correct" direction="ASC" style="text-align: center;">This is</div>'.PHP_EOL;
			}
			$return	.= '</div><br />'.PHP_EOL;
			return $return;
		}

	}