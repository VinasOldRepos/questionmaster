<?php
/************************************************************************************
* Name:				Courses Controller												*
* File:				Application\Controller\CoursesController.php 					*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This file controls Courses' related information.				*
*																					*
* Creation Date:	23/05/2013														*
* Version:			1.13.0523														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller;

	// Framework Classes
	use SaSeed\View;
	use SaSeed\Session;

	// Model Classes
	use Application\Model\Menu;
	use Application\Model\Pager;
	use Application\Model\Course					as ModCourse;

	// Repository Classes
	use Application\Controller\Repository\Course	as RepCourse;

	// Other Classes
	use Application\Controller\LogInController		as LogIn;

	class CoursesController {

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
				$GLOBALS['this_js']		= '<script type="text/javascript" src="/questionmaster/Application/View/js/scripts/courses.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_js']		.= '<script type="text/javascript" src="/questionmaster/Application/View/js/libs/jquery.fancybox-1.3.4.pack.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_css']	= '<link href="'.URL_PATH.'/Application/View/css/courses.css" rel="stylesheet">'.PHP_EOL;	// Se não houver, definir como vazio ''
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
			View::render('courses');
 		}

		/*
		Prints out new course page - New()
			@return format	- print
		*/
		public function Insert() {
			// Declare classes
			$RepCourse	= new RepCourse();
			$ModCourse	= new ModCourse();
			// Initialize variables
			$branches	= false;
			// Get all Branches
			$branches	= $RepCourse->getAllBranches();
			$branches	= ($branches) ? $ModCourse->listBranchOptions($branches) : false;
			// Define sub menu selection
			$GLOBALS['menu']['courses']['opt1_css'] = 'details_item_on';
			// Prepare return values
			View::set('branches', $branches);
			// Render view
			View::render('coursesInsert');
 		}

		/*
		Prints out search course page - Search()
			@return format	- render view
		*/
		public function Search() {
			// Declare Classes
			$RepCourse			= new RepCourse();
			$ModCourse			= new ModCourse();
			// Define sub menu selection
			$GLOBALS['menu']['courses']['opt2_css'] = 'details_item_on';
			// Intialize variables
			$return				= '<br />(no courses found)';
			$search				= (isset($_POST['search'])) ? trim($_POST['search']) : false;
			$search				= ((!$search) && (isset($GLOBALS['params'][1]))) ? trim($GLOBALS['params'][1]) : false;
			// Get first 20 entries
			$result				= $RepCourse->getAll(20);
			// If there are entries
			if ($result) {
				// Separate returned data an paging info
				$rows			= $result[0];
				$paging_info	= $result[1];
				// Model Result
				$return			= $ModCourse->listCourses($rows, 'c.id', 'ASC');
			}
			// Define Pager info
			$pager				= Pager::pagerOptions($paging_info, 'courses', 'partialResult');
			// Prepare info to be displayed
			View::set('pager', $pager);
			View::set('return', $return);
			// render view
			View::render('coursesSearch');
 		}

		/*
		Open Course details main page - details()
			@return format	- print
		*/
		public function details() {
			// Declare classes
			$RepCourse			= new RepCourse();
			$ModCourse			= new ModCourse();
			// Initialize variables
			$id_course			= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			// If course id was sent
			if ($id_course) {
				// Get course Info
				$course			= $RepCourse->getById($id_course);
				// If course was found
				if ($course) {
					// Get and model all Branches
					$branches	= $RepCourse->getAllBranches();
					$branches	= ($branches) ? $ModCourse->listBranchOptions($branches, $course['id_branch']) : false;
					// Get and model selected Branch's info
					$fields		= $RepCourse->getAllFields($course['id_branch']);
					if ($fields) {
						$fields	= $ModCourse->listFieldOptions($fields, $course['id_field']);
					}
					// Model status select
					$status		= $ModCourse->listStatus($course['boo_active']);
					// Prepare data to be sent
					View::set('branches',		$branches);
					View::set('fields',			$fields);
					View::set('id_course',		$course['id']);
					View::set('id_field',		$course['id_field']);
					View::set('level',			$course['int_level']);
					View::set('course',			$course['vc_course']);
					View::set('tot_questions',	$course['total_questions']);
					View::set('status',			$status);
					// Render page
					View::render('partial_courseDetails');
				}
			}
		}

		/*
		Prints partial results - partialResult()
			@return format	- print
		*/
		public function partialResult() {
			// Declare Classes
			$RepCourse				= new RepCourse();
			$ModCourse				= new ModCourse();
			// Intialize variables
			$return					= '<br />(no courses found)';
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
					$result			= $RepCourse->getSearched($str_search, $limit, $num_page, $ordering, $direction);
				} else {
					$result			= $RepCourse->getAll($limit, $num_page, $ordering, $direction);
				}
				// If there are entries
				if ($result) {
					// Separate returned data an paging info
					$rows			= $result[0];
					$paging_info	= $result[1];
					// Define Pager info
					$pager			= Pager::pagerOptions($paging_info, 'courses', 'partialResult');
					// Model Result
					$return			= $ModCourse->jqueryCourses($rows, $pager, $ordering, $direction);
				}
			}
			// Print out result
			echo $return;
 		}

		/*
		Adds Course to database - addCourse()
			@return format	- print
		*/
		public function addCourse() {
			// Add Classes
			$RepCourse			= new RepCourse();
			// Initialize variables
			$return				= 'nok';
			$id_field			= (isset($_POST['id_field'])) ? trim($_POST['id_field']) :  false;
			$vc_course			= (isset($_POST['vc_course'])) ? trim($_POST['vc_course']) :  false;
			$level				= (isset($_POST['level'])) ? trim($_POST['level']) :  false;
			$status				= (isset($_POST['status'])) ? trim($_POST['status']) :  false;
			// If data was sent
			if (($id_field !== false) && ($vc_course !== false) && ($level !== false) && ($status !== false)) {
				// Insert Course into database
				$id_course		= $RepCourse->insert($id_field, $vc_course, $level, $status);
				// Save Fields into database
				if ($id_course) {
					$return		= 'ok';
				}
			}
			// Print Return
			echo $return;
		}

		/*
		Update Course on the database - updateCourse()
			@return format	- print
		*/
		public function updateCourse() {
			// Add Classes
			$RepCourse			= new RepCourse();
			// Initialize variables
			$return				= 'nok';
			$id_course			= (isset($_POST['id_course'])) ? trim($_POST['id_course']) :  false;
			$id_field			= (isset($_POST['id_field'])) ? trim($_POST['id_field']) :  false;
			$vc_course			= (isset($_POST['vc_course'])) ? trim($_POST['vc_course']) :  false;
			$level				= (isset($_POST['level'])) ? trim($_POST['level']) :  false;
			$status				= (isset($_POST['status'])) ? trim($_POST['status']) :  false;
			// If data was sent
			if (($id_course !== false) && ($id_field !== false) && ($vc_course !== false) && ($level !== false) && ($status !== false)) {
				// Insert Course into database
				$res			= $RepCourse->update($id_course, $id_field, $vc_course, $level, $status);
				// Save Fields into database
				if ($res) {
					$return		= 'ok';
				}
			}
			// Print Return
			echo $return;
		}

		/*
		Prints fields' partial results - loadFields()
			@return format	- print to screen
		*/
		public function loadFields() {
			// Declare classes
			$RepCourse		= new RepCourse();
			$ModCourse		= new ModCourse();
			// Initialize variables
			$return			= false;
			$id_branch		= (isset($_POST['id_branch'])) ? trim($_POST['id_branch']) : false;
			// If branch id was sent
			if ($id_branch) {
				// Get all fields
				$fields		= $RepCourse->getAllFields($id_branch);
				// If fields were found
				if ($fields) {
					// Model return
					$return	= $ModCourse->listFieldOptions($fields);
				}
			}
			// Return
			echo $return;
		}

		/*
		Delete Course and associated info - deleteCourse()
			@return format	- print
		*/
		public function deleteCourse() {
			// Declare classes
			$RepCourse		= new RepCourse();
			// Initialize variables
			$return			= false;
			$id_course		= (isset($_POST['id_course'])) ? trim($_POST['id_course']) : false;
			// If values were sent
			if ($id_course) {
				// Delete branch
				$return		= $RepCourse->deleteAllCourseInfo($id_course);
			}
			// Return
			echo $return;
		}

	}