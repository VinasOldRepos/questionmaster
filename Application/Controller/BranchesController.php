<?php
/************************************************************************************
* Name:				Branches Controller												*
* File:				Application\Controller\BranchesController.php 					*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This file controls Branches' related information.				*
*																					*
* Creation Date:	29/04/2013														*
* Version:			1.13.0429														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller;

	// Framework Classes
	use SaSeed\View;
	use SaSeed\Session;

	// Model Classes
	use Application\Model\Menu;
	use Application\Model\Pager;
	use Application\Model\Branch					as ModBranch;

	// Repository Classes
	use Application\Controller\Repository\Branch	as RepBranch;

	// Other Classes
	use Application\Controller\LogInController		as LogIn;

	class BranchesController {

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
				$GLOBALS['this_js']		= '<script type="text/javascript" src="/questionmaster/Application/View/js/scripts/branches.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_js']		.= '<script type="text/javascript" src="/questionmaster/Application/View/js/libs/jquery.fancybox-1.3.4.pack.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_css']	= '<link href="'.URL_PATH.'/Application/View/css/branches.css" rel="stylesheet">'.PHP_EOL;	// Se não houver, definir como vazio ''
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
			View::render('branches');
 		}

		/*
		Prints out new branch page - New()
			@return format	- print
		*/
		public function Insert() {
			// Define sub menu selection
			$GLOBALS['menu']['branches']['opt1_css'] = 'details_item_on';
			View::render('branchesInsert');
 		}

		/*
		Prints out search branch page - Search()
			@return format	- render view
		*/
		public function Search() {
			// Declare Classes
			$RepBranch			= new RepBranch();
			$ModBranch			= new ModBranch();
			// Define sub menu selection
			$GLOBALS['menu']['branches']['opt2_css'] = 'details_item_on';
			// Intialize variables
			$return				= '<br />(no branches found)';
			$search				= (isset($_POST['search'])) ? trim($_POST['search']) : false;
			$search				= ((!$search) && (isset($GLOBALS['params'][1]))) ? trim($GLOBALS['params'][1]) : false;
			// Get first 20 entries
			$result				= $RepBranch->getAll(20);
			// If there are entries
			if ($result) {
				// Separate returned data an paging info
				$rows			= $result[0];
				$paging_info	= $result[1];
				// Model Result
				$return			= $ModBranch->listBranches($rows, 'b.id', 'ASC');
			}
			// Define Pager info
			$pager				= Pager::pagerOptions($paging_info, 'branches', 'partialResult');
			// Prepare info to be displayed
			View::set('pager', $pager);
			View::set('return', $return);
			// render view
			View::render('branchesSearch');
 		}

		/*
		Prints partial results - partialResult()
			@return format	- render view
		*/
		public function partialResult() {
			// Declare Classes
			$RepBranch				= new RepBranch();
			$ModBranch				= new ModBranch();
			// Intialize variables
			$return					= '<br />(no branches found)';
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
					$result			= $RepBranch->getSearched($str_search, $limit, $num_page, $ordering, $direction);
				} else {
					$result			= $RepBranch->getAll($limit, $num_page, $ordering, $direction);
				}
				// If there are entries
				if ($result) {
					// Separate returned data an paging info
					$rows			= $result[0];
					$paging_info	= $result[1];
					// Model Result
					$pager			= Pager::pagerOptions($paging_info, 'branches', 'partialResult');
					$return			= $ModBranch->jqueryBranches($rows, $pager, $ordering, $direction);
				}
			}
			// Print out result
			echo $return;
 		}

		/*
		Prints fields' partial results - partialResultFields()
			@return format	- render view
		*/
		public function partialResultFields() {
			// Declare Classes
			$RepBranch				= new RepBranch();
			$ModBranch				= new ModBranch();
			// Intialize variables
			$return					= '<br />(no branches found)';
			$num_page				= (isset($_POST['num_page'])) ? trim($_POST['num_page']) : false;
			$ordering				= (isset($_POST['ordering'])) ? trim($_POST['ordering']) : false;
			$offset					= (isset($_POST['offset'])) ? trim($_POST['offset']) : false;
			$limit					= (isset($_POST['limit'])) ? trim($_POST['limit']) : false;
			$direction				= (isset($_POST['direction'])) ? trim($_POST['direction']) : false;
			$str_search				= (isset($_POST['str_search'])) ? trim($_POST['str_search']) : false;
			$id_branch				= (isset($_POST['parent_id'])) ? trim($_POST['parent_id']) : false;
			$pager					= '';
			// If data was sent
			//if (($num_page) && ($ordering) && ($offset) && ($limit) && ($direction)) {
			if (($num_page) && ($ordering) && ($limit) && ($direction) && ($id_branch)) {
				// Get branch Info
				$branch				= $RepBranch->getById($id_branch);
				// Get searched data
				if ($str_search) {
					$result			= $RepBranch->getSearchedFieldsBranchId($id_branch, $str_search, $limit, $num_page, $ordering, $direction);
				} else {
					$result			= $RepBranch->getFieldsBranchId($id_branch, $limit, $num_page, $ordering, $direction);
				}
				// If there are entries
				if ($result) {
					// Separate returned data an paging info
					$rows			= $result[0];
					$paging_info	= $result[1];
					// Define Pager info
					$pager			= Pager::pagerOptions($paging_info, 'fields', 'partialResultFields', 'details');
					// Model output
					$return			= $ModBranch->jqueryFields($rows, $pager, $ordering, $direction);
				}
			}
			// Print out result
			echo $return;
 		}

		/*
		Adds Branch and fields to database - addBranchFields()
			@return format	- print
		*/
		public function addBranchFields() {
			// Add Classes
			$RepBranch			= new RepBranch();
			// Initialize variables
			$return				= 'nok';
			$branch				= (isset($_POST['branch'])) ? trim($_POST['branch']) :  false;
			$fields				= (isset($_POST['fields'])) ? trim($_POST['fields']) :  false;
			// If data was sent
			if (($branch) && ($fields)) {
				// Format data
				$fields			= explode("|", $fields);
				// Save Branch into database
				$id_branch		= $RepBranch->insert($branch);
				// Save Fields into database
				if ($id_branch) {
					$return		= $RepBranch->insertFields($id_branch, $fields);
					// Prepare return
					if ($return) {
						$return	= 'ok';
					}
				}
			}
			// Print Return
			echo $return;
		}

		/*
		Adds Field to an existing Branch - addField()
			@return format	- print
		*/
		public function addField() {
			// Add Classes
			$RepBranch		= new RepBranch();
			// Initialize variables
			$return			= 'nok';
			$id_branch		= (isset($_POST['id_branch'])) ? trim($_POST['id_branch']) :  false;
			$vc_field		= (isset($_POST['vc_field'])) ? trim($_POST['vc_field']) :  false;
			// If data was sent
			if (($id_branch) && ($vc_field)) {
				$return		= $RepBranch->insertField($id_branch, $vc_field);
				// Prepare return
				if ($return) {
					$return	= 'ok';
				}
			}
			// Print Return
			echo $return;
		}

		/*
		Open branch details main page - details()
			@return format	- print
		*/
		public function details() {
			// Declare classes
			$RepBranch					= new RepBranch();
			$ModBranch					= new ModBranch();
			// Initialize variables
			$id_branch					= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			// If branch id was sent
			if ($id_branch) {
				// Get branch Info
				$branch					= $RepBranch->getById($id_branch);
				// If branch was found
				if ($branch) {
					// Get branch's associated fields
					$fields				= $RepBranch->getFieldsBranchId($id_branch);
					// If there are entries
					if ($fields) {
						// Separate returned data an paging info
						$rows			= $fields[0];
						$paging_info	= $fields[1];
						// Model Result
						$return			= $ModBranch->listFields($rows, 'id', 'ASC');
					}
					// Define Pager info
					$pager				= Pager::pagerOptions($paging_info, 'fields', 'partialResultFields', 'details');
					// Prepare data to be sent
					View::set('id_branch', $branch['id']);
					View::set('branch', $branch['vc_branch']);
					View::set('pager', $pager);
					View::set('return', $return);
					// Render page
					View::render('partial_branchDetails');
				}
			}
		}

		/*
		Load and show field's details ans statitics - fieldDetails()
			@return format	- print
		*/
		public function fieldDetails() {
			// Declare classes
			$RepBranch		= new RepBranch();
			$ModBranch		= new ModBranch();
			// Initialize variables
			$return			= false;
			$id_field		= (isset($_POST['id_field'])) ? trim(($_POST['id_field'])) : false;
			// If field's id was sent
			if ($id_field) {
				// Get field's info
				$field		= $RepBranch->getFieldInfoById($id_field);
			 	// If info was found
				if ($field) {
					// Model field's info
					$return	= $ModBranch->fieldDetails($field);
				}
			}
			// Return
			echo $return;
		}

		/*
		Updates branch info (so far, its name) - updateBranch()
			@return format	- print
		*/
		public function updateBranch() {
			// Declare classes
			$RepBranch		= new RepBranch();
			// Initialize variables
			$id_branch		= (isset($_POST['id_branch'])) ? trim($_POST['id_branch']) : false;
			$vc_branch		= (isset($_POST['vc_branch'])) ? trim($_POST['vc_branch']) : false;
			// If values were sent
			if (($id_branch) && ($vc_branch)) {
				// Update branch
				$RepBranch->update(array($id_branch, $vc_branch));
				// Prepare return
				$return		= $vc_branch;
			}
			// Return
			echo $return;
		}

		/*
		Updates field info (so far, its name) - updateField()
			@return format	- print
		*/
		public function updateField() {
			// Declare classes
			$RepBranch		= new RepBranch();
			// Initialize variables
			$id_field		= (isset($_POST['id_field'])) ? trim($_POST['id_field']) : false;
			$vc_field		= (isset($_POST['vc_field'])) ? trim($_POST['vc_field']) : false;
			// If values were sent
			if (($id_field) && ($vc_field)) {
				// Update field
				$RepBranch->updateField($id_field, $vc_field);
				// Prepare return
				$return		= $vc_field;
			}
			// Return
			echo $return;
		}

		/*
		Change Field's status - changeFieldStatus()
			@return format	- print
		*/
		public function changeFieldStatus() {
			// Declare classes
			$RepBranch		= new RepBranch();
			// Initialize variables
			$id_field		= (isset($_POST['id_field'])) ? trim($_POST['id_field']) : false;
			$boo_active		= (isset($_POST['boo_active'])) ? trim($_POST['boo_active']) : 0;
			// If values were sent
			if ($id_field) {
				// Update field
				$RepBranch->changeFieldStatus($id_field, $boo_active);
				// Prepare return
				if ($boo_active == 1) {
					$return	= 'act';
				} else {
					$return	= 'ina';
				}
			}
			// Return
			echo $return;
		}

		/*
		Delete branch and associated info - deleteBranch()
			@return format	- print
		*/
		public function deleteBranch() {
			// Declare classes
			$RepBranch		= new RepBranch();
			// Initialize variables
			$id_branch		= (isset($_POST['id_branch'])) ? trim($_POST['id_branch']) : false;
			// If values were sent
			if ($id_branch) {
				// Delete branch
				$return		= $RepBranch->deleteAllBranchInfo($id_branch);
			}
			// Return
			echo $return;
		}

		/*
		Delete field and associated info - deleteField()
			@return format	- print
		*/
		public function deleteField() {
			// Declare classes
			$RepBranch		= new RepBranch();
			// Initialize variables
			$return			= false;
			$id_field		= (isset($_POST['id_field'])) ? trim($_POST['id_field']) : false;
			// If values were sent
			if ($id_field) {
				// Delete branch
				$return		= $RepBranch->deleteAllFieldInfo($id_field);
			}
			// Return
			echo $return;
		}

	}