<?php
/************************************************************************************
* Name:				User Model														*
* File:				Application\Model\User.php 										*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This is the User's model.										*
*																					*
* Creation Date:	15/11/2012														*
* Version:			1.12.1115														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Model;

	class User {

		public function listUsers($entries = false, $ordering = false, $direction = false) {
			$return				= false;
			if ($entries) {
				$return			= $this->resultHeader($ordering, $direction);
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$return		.= '<div class="return_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$return		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_user">'.$entries[$i]['vc_user'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_email">'.$entries[$i]['vc_email'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_profile">'.$entries[$i]['vc_profile'].'</div>'.PHP_EOL;
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

		public function jqueryUsers($entries = false, $pager = false, $ordering = 'id', $direction = 'ASC') {
			$return				= false;
			$rows				= '';
			if (($entries) && ($pager)) {
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$rows		.= '<div class="return_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$rows		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_user">'.$entries[$i]['vc_user'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_email">'.$entries[$i]['vc_email'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_profile">'.$entries[$i]['vc_profile'].'</div>'.PHP_EOL;
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

		public function comboProfiles($profiles = false, $id_profile = false) {
			$return				= false;
			if ($profiles) {
				//$return			.= '<option value="0">-</option>'.PHP_EOL;
				foreach ($profiles as $profile) {
					if ($id_profile == $profile['id']) {
						$return	.= '<option value="'.$profile['id'].'" selected="selected">'.$profile['vc_profile'].'</option>'.PHP_EOL;
					} else {
						$return	.= '<option value="'.$profile['id'].'">'.$profile['vc_profile'].'</option>'.PHP_EOL;
					}
				}
			}
			return $return;
		}

		private	function resultHeader($ordering = 'u.id', $direction = 'ASC') {
			$return		= '<div class="result_header">'.PHP_EOL;
			if (($ordering == 'u.id') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_id" key="u.id" direction="DESC"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else if (($ordering == 'u.id') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_id" key="u.id" direction="ASC"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_id" key="u.id" direction="ASC">ID</div>'.PHP_EOL;
			}
			if (($ordering == 'u.vc_user') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_user"  key="u.vc_user" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />User</div>'.PHP_EOL;
			} else if (($ordering == 'u.vc_user') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_user"  key="u.vc_user" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />User</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_user"  key="u.vc_user" direction="ASC" style="text-align: center;">User</div>'.PHP_EOL;
			}
			if (($ordering == 'u.vc_email') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_email"  key="u.vc_email" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Email</div>'.PHP_EOL;
			} else if (($ordering == 'u.vc_email') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_email"  key="u.vc_email" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Email</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_email"  key="u.vc_email" direction="ASC" style="text-align: center;">Email</div>'.PHP_EOL;
			}
			if (($ordering == 'p.vc_profile') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_profile"  key="p.vc_profile" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Profile</div>'.PHP_EOL;
			} else if (($ordering == 'p.vc_profile') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_profile"  key="p.vc_profile" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Profile</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_profile"  key="p.vc_profile" direction="ASC" style="text-align: center;">Profile</div>'.PHP_EOL;
			}
			if (($ordering == 'u.boo_active') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_field result_active"  key="u.boo_active" direction="DESC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Status</div>'.PHP_EOL;
			} else if (($ordering == 'u.boo_active') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_field result_active"  key="u.boo_active" direction="ASC" style="text-align: center;"><img src="/questionmaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Status</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_field result_active"  key="u.boo_active" direction="ASC" style="text-align: center;">Status</div>'.PHP_EOL;
			}
			$return	.= '</div><br />'.PHP_EOL;
			return $return;
		}

	}