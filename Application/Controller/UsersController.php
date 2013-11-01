<?php
/************************************************************************************
* Name:				Users Controller												*
* File:				Application\Controller\UsersController.php 						*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This file controls Courses' related information.				*
*																					*
* Creation Date:	06/06/2013														*
* Version:			1.13.0606														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller;

	// Framework Classes
	use SaSeed\View;
	use SaSeed\Session;

	// Model Classes
	use Application\Model\Menu;
	use Application\Model\Pager;
	use Application\Model\User						as ModUser;

	// Repository Classes
	use Application\Controller\Repository\User		as RepUser;

	// Other Classes
	use Application\Controller\LogInController		as LogIn;

	class UsersController {

		public function __construct() {
			// Start session
			Session::start();
			// Check if user is Logged
			$SesUser					= LogIn::checkLogin();
			if (!$SesUser) {
				// Redirect to login area when not
				header('location: '.URL_PATH.'/LogIn/');
			} else {
				// Define JSs e CSSs utilizados por este controller
				$GLOBALS['this_js']		= '<script type="text/javascript" src="/questionmaster/Application/View/js/scripts/users.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_js']		.= '<script type="text/javascript" src="/questionmaster/Application/View/js/libs/jquery.fancybox-1.3.4.pack.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_css']	= '<link href="'.URL_PATH.'/Application/View/css/users.css" rel="stylesheet">'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_css']	.= '<link href="'.URL_PATH.'/Application/View/css/jquery.fancybox-1.3.4.css" rel="stylesheet">'.PHP_EOL;	// Se não houver, definir como vazio ''
				// Define Menu selection
				Menu::defineSelected($GLOBALS['controller_name']);
			}
		}

		/*
		Prints out main login page - start()
			@return format	- print
		*/
		public function index() {
			View::render('users');
 		}

		/*
		Prints out new course page - New()
			@return format	- print
		*/
		public function Insert() {
			// Declare classes
			$RepUser	= new RepUser();
			$ModUser	= new ModUser();
			// Initialize variables
			$branches	= false;
			// Get Profiles
			$profiles	= $RepUser->getAllProfiles();
			$profiles	= ($profiles) ? $ModUser->comboProfiles($profiles) : false;
			// Define sub menu selection
			$GLOBALS['menu']['users']['opt1_css'] = 'details_item_on';
			// Prepare return values
			View::set('profiles', $profiles);
			// Render view
			View::render('usersInsert');
 		}

		/*
		Prints out search course page - Search()
			@return format	- render view
		*/
		public function Search() {
			// Declare Classes
			$RepUser			= new RepUser();
			$ModUser			= new ModUser();
			// Define sub menu selection
			$GLOBALS['menu']['users']['opt2_css'] = 'details_item_on';
			// Intialize variables
			$return				= '<br />(no users found)';
			$search				= (isset($_POST['search'])) ? trim($_POST['search']) : false;
			$search				= ((!$search) && (isset($GLOBALS['params'][1]))) ? trim($GLOBALS['params'][1]) : false;
			// Get first 20 entries
			$result				= $RepUser->getAll(20);
			// If there are entries
			if ($result) {
				// Separate returned data an paging info
				$rows			= $result[0];
				$paging_info	= $result[1];
				// Model Result
				$return			= $ModUser->listUsers($rows, 'u.id', 'ASC');
				// Define Pager info
				$pager				= Pager::pagerOptions($paging_info, 'users', 'partialResult');
			}
			// Prepare info to be displayed
			View::set('pager', $pager);
			View::set('return', $return);
			// render view
			View::render('usersSearch');
 		}

		/*
		Prints partial results - partialResult()
			@return format	- render view
		*/
		public function partialResult() {
			// Declare Classes
			$RepUser				= new RepUser();
			$ModUser				= new ModUser();
			// Intialize variables
			$return					= '<br />(no users found)';
			$num_page				= (isset($_POST['num_page'])) ? trim($_POST['num_page']) : false;
			$ordering				= (isset($_POST['ordering'])) ? trim($_POST['ordering']) : false;
			$offset					= (isset($_POST['offset'])) ? trim($_POST['offset']) : false;
			$limit					= (isset($_POST['limit'])) ? trim($_POST['limit']) : false;
			$direction				= (isset($_POST['direction'])) ? trim($_POST['direction']) : false;
			$str_search				= (isset($_POST['str_search'])) ? trim($_POST['str_search']) : false;
			$pager					= '';
			// If data was sent
			//if (($num_page) && ($ordering) && ($offset) && ($limit) && ($direction)) {
			if (($num_page) && ($ordering) && ($limit) && ($direction)) {
				// Get searched data
				if ($str_search) {
					$result			= $RepUser->getSearched($str_search, $limit, $num_page, $ordering, $direction);
				} else {
					$result			= $RepUser->getAll($limit, $num_page, $ordering, $direction);
				}
				// If there are entries
				if ($result) {
					// Separate returned data and paging info
					$rows			= $result[0];
					$paging_info	= $result[1];
					// Model Result
					$pager			= Pager::pagerOptions($paging_info, 'users', 'partialResult');
					$return			= $ModUser->jqueryUsers($rows, $pager, $ordering, $direction);
				}
			}
			// Print out result
			echo $return;
 		}

		/*
		Open User details main page - details()
			@return format	- print
		*/
		public function details() {
			// Declare classes
			$RepUser						= new RepUser();
			$ModUser						= new ModUser();
			// Initialize variables
			$id_user						= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			// If user id was sent
			if ($id_user) {
				// Get branch Info
				$user						= $RepUser->getById($id_user);
				// If user was found
				if ($user) {
					// Get and model Profiles' combo
					$profiles				= $RepUser->getAllProfiles();
					$profiles				= ($profiles) ? $ModUser->comboProfiles($profiles, $user['id_profile']) : false;
					// Prepare data to be sent
					View::set('profiles',	$profiles);
					View::set('id_user',	$id_user);
					View::set('id_profile',	$user['id_profile']);
					View::set('vc_user',	$user['vc_user']);
					View::set('vc_email',	$user['vc_email']);
					View::set('boo_active',	$user['boo_active']);
					// Render page
					View::render('partial_userDetails');
				}
			}
		}

		/*
		Create a new user - addUsed()
			@return format	- print
		*/
		public function addUser() {
			// Declare classes
			$RepUser		= new RepUser();
			// Initialize variable
			$id_profile		= (isset($_POST['id_profile'])) ? trim($_POST['id_profile']) : false;
			$vc_user		= (isset($_POST['vc_user'])) ? trim($_POST['vc_user']) : false;
			$vc_email		= (isset($_POST['vc_email'])) ? trim($_POST['vc_email']) : false;
			$vc_password	= (isset($_POST['vc_password'])) ? md5(trim($_POST['vc_password'])) : false;
			$return			= false;
			// If values were sent
			if (($id_profile) && ($vc_user) && ($vc_email) && ($vc_password)) {
				// Create User				
				$return		= $RepUser->insert(array($id_profile, $vc_user, $vc_email, $vc_password));
				// Prepare return
				$return		= ($return) ? 'ok' : false;
			}
			// Return
			echo $return;
 		}

		/*
		Update User info - updateUser()
			@return format	- print
		*/
		public function updateUser() {
			// Declare classes
			$RepUser		= new RepUser();
			// Initialize variable
			$id_user		= (isset($_POST['id_user'])) ? trim($_POST['id_user']) : false;
			$id_profile		= (isset($_POST['id_profile'])) ? trim($_POST['id_profile']) : false;
			$vc_user		= (isset($_POST['vc_user'])) ? trim($_POST['vc_user']) : false;
			$vc_email		= (isset($_POST['vc_email'])) ? trim($_POST['vc_email']) : false;
			$vc_password	= ((isset($_POST['vc_password'])) && ($_POST['vc_password'] != '')) ? md5(trim($_POST['vc_password'])) : false;
			$return			= false;
			// If values were sent
			if (($id_user) && ($id_profile) && ($vc_user) && ($vc_email)) {
				// Update User				
				$return		= $RepUser->update($id_user, array($id_profile, $vc_user, $vc_email, $vc_password));
				// Prepare return
				$return		= ($return) ? 'ok' : false;
			}
			// Return
			echo $return;
 		}

		/*
		Delete User - deleteUser()
			@return format	- print
		*/
		public function deleteUser() {
			// Declare classes
			$RepUser		= new RepUser();
			// Initialize variables
			$return			= false;
			$id_user		= (isset($_POST['id_user'])) ? trim($_POST['id_user']) : false;
			// If values were sent
			if ($id_user) {
				// Delete branch
				$RepUser->delete($id_user);
				$return		= 'ok';
			}
			// Return
			echo $return;
		}
	}