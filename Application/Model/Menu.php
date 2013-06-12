<?php
/************************************************************************************
* Name:				Menu Model														*
* File:				Application\Model\Menu.php 									*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This is the Menu's model.										*
*																					*
* Creation Date:	03/05/2012														*
* Version:			1.12.1115														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Model;

	Final class Menu {

		public static function defineSelected ($controller = false) {
			if ($controller) {
				$controller						= str_replace('Controller', '', $controller);
				$GLOBALS['menu']['blank']['display']			= 'none';
				if ($controller == 'Branches') {
					$GLOBALS['menu']['branches']['css']			= 'item_on';
					$GLOBALS['menu']['branches']['display']		= 'block';
					$GLOBALS['menu']['branches']['opt1_css']	= 'details_item_off';
					$GLOBALS['menu']['branches']['opt2_css']	= 'details_item_off';

					$GLOBALS['menu']['courses']['css']			= 'item_off';
					$GLOBALS['menu']['courses']['display']		= 'none';
					$GLOBALS['menu']['courses']['opt1_css']		= 'details_item_off';
					$GLOBALS['menu']['courses']['opt2_css']		= 'details_item_off';

					$GLOBALS['menu']['questions']['css']		= 'item_off';
					$GLOBALS['menu']['questions']['display']	= 'none';
					$GLOBALS['menu']['questions']['opt1_css']	= 'details_item_off';
					$GLOBALS['menu']['questions']['opt2_css']	= 'details_item_off';

					$GLOBALS['menu']['users']['css']			= 'item_off';
					$GLOBALS['menu']['users']['display']		= 'none';
					$GLOBALS['menu']['users']['opt1_css']		= 'details_item_off';
					$GLOBALS['menu']['users']['opt2_css']		= 'details_item_off';
				} else if ($controller == 'Courses') {
					$GLOBALS['menu']['branches']['css']			= 'item_off';
					$GLOBALS['menu']['branches']['display']		= 'none';
					$GLOBALS['menu']['branches']['opt1_css']	= 'details_item_off';
					$GLOBALS['menu']['branches']['opt2_css']	= 'details_item_off';

					$GLOBALS['menu']['courses']['css']			= 'item_on';
					$GLOBALS['menu']['courses']['display']		= 'block';
					$GLOBALS['menu']['courses']['opt1_css']		= 'details_item_off';
					$GLOBALS['menu']['courses']['opt2_css']		= 'details_item_off';

					$GLOBALS['menu']['questions']['css']		= 'item_off';
					$GLOBALS['menu']['questions']['display']	= 'none';
					$GLOBALS['menu']['questions']['opt1_css']	= 'details_item_off';
					$GLOBALS['menu']['questions']['opt2_css']	= 'details_item_off';

					$GLOBALS['menu']['users']['css']			= 'item_off';
					$GLOBALS['menu']['users']['display']		= 'none';
					$GLOBALS['menu']['users']['opt1_css']		= 'details_item_off';
					$GLOBALS['menu']['users']['opt2_css']		= 'details_item_off';
				} else if ($controller == 'Questions') {
					$GLOBALS['menu']['branches']['css']			= 'item_off';
					$GLOBALS['menu']['branches']['display']		= 'none';
					$GLOBALS['menu']['branches']['opt1_css']	= 'details_item_off';
					$GLOBALS['menu']['branches']['opt2_css']	= 'details_item_off';

					$GLOBALS['menu']['courses']['css']			= 'item_off';
					$GLOBALS['menu']['courses']['display']		= 'none';
					$GLOBALS['menu']['courses']['opt1_css']		= 'details_item_off';
					$GLOBALS['menu']['courses']['opt2_css']		= 'details_item_off';

					$GLOBALS['menu']['questions']['css']		= 'item_on';
					$GLOBALS['menu']['questions']['display']	= 'block';
					$GLOBALS['menu']['questions']['opt1_css']	= 'details_item_off';
					$GLOBALS['menu']['questions']['opt2_css']	= 'details_item_off';

					$GLOBALS['menu']['users']['css']			= 'item_off';
					$GLOBALS['menu']['users']['display']		= 'none';
					$GLOBALS['menu']['users']['opt1_css']		= 'details_item_off';
					$GLOBALS['menu']['users']['opt2_css']		= 'details_item_off';
				} else if ($controller == 'Users') {
					$GLOBALS['menu']['branches']['css']			= 'item_off';
					$GLOBALS['menu']['branches']['display']		= 'none';
					$GLOBALS['menu']['branches']['opt1_css']	= 'details_item_off';
					$GLOBALS['menu']['branches']['opt2_css']	= 'details_item_off';

					$GLOBALS['menu']['courses']['css']			= 'item_off';
					$GLOBALS['menu']['courses']['display']		= 'none';
					$GLOBALS['menu']['courses']['opt1_css']		= 'details_item_off';
					$GLOBALS['menu']['courses']['opt2_css']		= 'details_item_off';

					$GLOBALS['menu']['questions']['css']		= 'item_off';
					$GLOBALS['menu']['questions']['display']	= 'none';
					$GLOBALS['menu']['questions']['opt1_css']	= 'details_item_off';
					$GLOBALS['menu']['questions']['opt2_css']	= 'details_item_off';

					$GLOBALS['menu']['users']['css']			= 'item_on';
					$GLOBALS['menu']['users']['display']		= 'block';
					$GLOBALS['menu']['users']['opt1_css']		= 'details_item_off';
					$GLOBALS['menu']['users']['opt2_css']		= 'details_item_off';
				} else {
					$GLOBALS['menu']['blank']['display']		= 'block';

					$GLOBALS['menu']['branches']['css']			= 'item_off';
					$GLOBALS['menu']['branches']['display']		= 'none';
					$GLOBALS['menu']['branches']['opt1_css']	= 'details_item_off';
					$GLOBALS['menu']['branches']['opt2_css']	= 'details_item_off';

					$GLOBALS['menu']['courses']['css']			= 'item_off';
					$GLOBALS['menu']['courses']['display']		= 'none';
					$GLOBALS['menu']['courses']['opt1_css']		= 'details_item_off';
					$GLOBALS['menu']['courses']['opt2_css']		= 'details_item_off';

					$GLOBALS['menu']['questions']['css']		= 'item_off';
					$GLOBALS['menu']['questions']['display']	= 'none';
					$GLOBALS['menu']['questions']['opt1_css']	= 'details_item_off';
					$GLOBALS['menu']['questions']['opt2_css']	= 'details_item_off';

					$GLOBALS['menu']['users']['css']			= 'item_off';
					$GLOBALS['menu']['users']['display']		= 'none';
					$GLOBALS['menu']['users']['opt1_css']		= 'details_item_off';
					$GLOBALS['menu']['users']['opt2_css']		= 'details_item_off';
				}
			}
		}

	}