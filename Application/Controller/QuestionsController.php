<?php
/************************************************************************************
* Name:				Questions Controller											*
* File:				Application\Controller\QuestionsController.php 					*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This file controls Questions' related information.				*
*																					*
* Creation Date:	29/05/2013														*
* Version:			1.13.0529														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller;

	// Framework Classes
	use SaSeed\View;
	use SaSeed\Session;

	// Model Classes
	use Application\Model\Menu;
	use Application\Model\Pager;
	use Application\Model\Question					as ModQuestion;

	// Repository Classes
	use Application\Controller\Repository\Question	as RepQuestion;

	// Other Classes
	use Application\Controller\LogInController		as LogIn;

	class QuestionsController {

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
				$GLOBALS['this_js']		= '<script type="text/javascript" src="/questionmaster/Application/View/js/scripts/questions.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_js']		.= '<script type="text/javascript" src="/questionmaster/Application/View/js/libs/jquery.fancybox-1.3.4.pack.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_css']	= '<link href="'.URL_PATH.'/Application/View/css/questions.css" rel="stylesheet">'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_css']	.= '<link href="'.URL_PATH.'/Application/View/css/jquery.fancybox-1.3.4.css" rel="stylesheet">'.PHP_EOL;	// Se não houver, definir como vazio ''
				// Define Menu selection
				Menu::defineSelected($GLOBALS['controller_name']);
			}
		}

		/*
		Renders main login page - index()
			@return format	- render view
		*/
		public function index() {
			View::render('questions');
 		}

		/*
		Renders search question page - Search()
			@return format	- render view
		*/
		public function Search() {
			// Declare Classes
			$RepQuestion		= new RepQuestion();
			$ModQuestion		= new ModQuestion();
			// Define sub menu selection
			$GLOBALS['menu']['questions']['opt2_css'] = 'details_item_on';
			// Intialize variables
			$return				= '<br />(no questions found)';
			$search				= (isset($_POST['search'])) ? trim($_POST['search']) : false;
			$search				= ((!$search) && (isset($GLOBALS['params'][1]))) ? trim($GLOBALS['params'][1]) : false;
			// Get first 20 entries
			$result				= $RepQuestion->getAll(20);
			// If there are entries
			if ($result) {
				// Separate returned data an paging info
				$rows			= $result[0];
				$paging_info	= $result[1];
				// Model Result
				$return			= $ModQuestion->listQuestions($rows, 'q.id', 'ASC');
			}
			// Define Pager info
			$pager				= Pager::pagerOptions($paging_info, 'questions', 'partialResult');
			// Prepare info to be displayed
			View::set('pager', $pager);
			View::set('return', $return);
			// render view
			View::render('questionsSearch');
 		}

		/*
		Renders Inser Question page - insert()
			@return format	- print
		*/
		public function insert() {
			// Declare classes
			$RepQuestion	= new RepQuestion();
			$ModQuestion	= new ModQuestion();
			// Initialize variables
			$id_course		= (isset($GLOBALS['params'][1])) ? trim($GLOBALS['params'][1]) : false;
			// Get All Statuses
			$status			= $RepQuestion->getAllStatus();
			// Get All Branches
			$branches		= $RepQuestion->getAllBranches();
			// If Course was informed
			if ($id_course) {
				// Get selected branch and field
				$res		= $RepQuestion->getBranchFieldByCourseId($id_course);
				// If There was data
				if ($res) {
					$id_branch	= $res['id_branch'];
					$id_field	= $res['id_field'];
				}
			}
			// Model Results
			$status			= $ModQuestion->statusCombo($status);
			$branches		= $ModQuestion->branchesCombo($branches);
			// Prepare values to be sent
			View::set('status', $status);
			View::set('branches', $branches);
			// Render View
			View::render('questionsInsert');
 		}

		/*
		Prints partial results - partialResult()
			@return format	- print
		*/
		public function partialResult() {
			// Declare Classes
			$RepQuestion			= new RepQuestion();
			$ModQuestion			= new ModQuestion();
			// Intialize variables
			$return					= '<br />(no questions found)';
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
					$result			= $RepQuestion->getSearched($str_search, $limit, $num_page, $ordering, $direction);
				} else {
					$result			= $RepQuestion->getAll($limit, $num_page, $ordering, $direction);
				}
				// If there are entries
				if ($result) {
					// Separate returned data an paging info
					$rows			= $result[0];
					$paging_info	= $result[1];
					// Define Pager info
					$pager			= Pager::pagerOptions($paging_info, 'questions', 'partialResult');
					// Model Result
					$return			= $ModQuestion->jqueryQuestions($rows, $pager, $ordering, $direction);
				}
			}
			// Print out result
			echo $return;
 		}

		/*
		Prints answers' partial results - partialResultAnswers()
			@return format	- render view
		*/
		public function partialResultAnswers() {
			// Declare Classes
			$RepQuestion			= new RepQuestion();
			$ModQuestion			= new ModQuestion();
			// Intialize variables
			$return					= '<br />(no answers found)';
			$num_page				= (isset($_POST['num_page'])) ? trim($_POST['num_page']) : false;
			$ordering				= (isset($_POST['ordering'])) ? trim($_POST['ordering']) : false;
			$offset					= (isset($_POST['offset'])) ? trim($_POST['offset']) : false;
			$limit					= (isset($_POST['limit'])) ? trim($_POST['limit']) : false;
			$direction				= (isset($_POST['direction'])) ? trim($_POST['direction']) : false;
			$str_search				= (isset($_POST['str_search'])) ? trim($_POST['str_search']) : false;
			$id_question			= (isset($_POST['parent_id'])) ? trim($_POST['parent_id']) : false;
			$pager					= '';
			// If data was sent
			//if (($num_page) && ($ordering) && ($offset) && ($limit) && ($direction)) {
			if (($num_page) && ($ordering) && ($limit) && ($direction) && ($id_question)) {
				// Get question's associated answers
				$answers			= $RepQuestion->getAllAnswersByQuestionId($id_question, $limit, $num_page, $ordering, $direction);
				// If there are entries
				if ($answers) {
					// Separate returned data an paging info
					$rows			= $answers[0];
					$paging_info	= $answers[1];
					// Define Pager info
					$pager			= Pager::pagerOptions($paging_info, 'answers', 'partialResultAnswers', 'details');
					// Model output
					$return			= $ModQuestion->jqueryAnswers($rows, $pager, $ordering, $direction);
				}
			}
			// Print out result
			echo $return;
 		}

		/*
		Open Question details main page - details()
			@return format	- print
		*/
		public function details() {
			// Declare classes
			$RepQuestion				= new RepQuestion();
			$ModQuestion				= new ModQuestion();
			// Initialize variables
			$id_question				= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			$courses_arr				= false;
			// If question id was sent
			if ($id_question) {
				// Get question Info
				$question				= $RepQuestion->getAllInfoById($id_question);
				// If question was found
				if ($question) {
					// Get all question status
					$all_status			= $RepQuestion->getAllStatus();
					// Get all possible courses (but the ones it has already
					$all_courses		= $RepQuestion->getAllAssociatedCourses($id_question, $question['courses']);
					// Get question's associated answers
					$answers			= $RepQuestion->getAllAnswersByQuestionId($id_question);
					// If there are entries
					if ($answers) {
						// Separate returned data an paging info
						$rows			= $answers[0];
						$paging_info	= $answers[1];
						// Model Answers
						$return			= $ModQuestion->listAnswers($rows, 'id', 'ASC');
					}
					// Model results
					$status				= $ModQuestion->statusCombo($all_status, $question['id_status']);
					$combo_courses		= $ModQuestion->coursesCombo($all_courses);
					$courses			= $ModQuestion->listCourses($question['courses']);
					// Define Pager info
					$pager				= Pager::pagerOptions($paging_info, 'answers', 'partialResultAnswers', 'details');
					// Prepare data to be sent
					if ($question['courses']) {
						foreach ($question['courses'] as $course) {
							$courses_arr	= (!$courses_arr)  ? $course['id_course'] : $courses_arr.'|'.$course['id_course'];
						}
					}
					View::set('id_question',	$question['id']);
					View::set('question',		$question['tx_question']);
					View::set('tutor',			$question['tx_tutor']);
					View::set('pager',			$pager);
					View::set('status',			$status);
					View::set('courses',		$courses);
					View::set('combo_courses',	$combo_courses);
					View::set('courses_arr',	$courses_arr);
					View::set('return',			$return);
					// Render page
					View::render('partial_questionDetails');
				}
			}
		}

		/*
		Load and show answer's details - answerDetails()
			@return format	- print
		*/
		public function answerDetails() {
			// Declare classes
			$RepQuestion	= new RepQuestion();
			$ModQuestion	= new ModQuestion();
			// Initialize variables
			$return			= false;
			$id_answer		= (isset($_POST['id_answer'])) ? trim(($_POST['id_answer'])) : false;
			// If field's id was sent
			if ($id_answer) {
				// Get field's info
				$answer		= $RepQuestion->getAnswerById($id_answer);
				// If info was found
				if ($answer) {
					// Model answer's info
					$return	= $ModQuestion->answerDetails($answer);
				}
			}
			// Return
			echo $return;
		}

		/*
		Updates Question text - updateQuestion()
			@return format	- print
		*/
		public function updateQuestion() {
			// Declare classes
			$RepQuestion	= new RepQuestion();
			// Initialize variables
			$id_question	= (isset($_POST['id_question'])) ? trim($_POST['id_question']) : false;
			$tx_question	= (isset($_POST['tx_question'])) ? trim($_POST['tx_question']) : false;
			$return			= false;
			// If data were sent
			if (($id_question) && ($tx_question)) {
				// Update Question
				$RepQuestion->updateQuestionText($id_question, $tx_question);
				// Prepare return
				$return		= $tx_question;
			}
			// Return
			echo $return;
		}

		/*
		Updates Answer text - updateAnswer()
			@return format	- print
		*/
		public function updateAnswer() {
			// Declare classes
			$RepQuestion	= new RepQuestion();
			// Initialize variables
			$id_answer		= (isset($_POST['id_answer'])) ? trim($_POST['id_answer']) : false;
			$id_question	= (isset($_POST['id_question'])) ? trim($_POST['id_question']) : false;
			$vc_answer		= (isset($_POST['vc_answer'])) ? trim($_POST['vc_answer']) : false;
			$boo_correct	= (isset($_POST['boo_correct'])) ? trim($_POST['boo_correct']) : 0;
			$return			= false;
			// If data was sent
			if (($id_answer) && ($id_question) && ($vc_answer)) {
				// Update Question
				$RepQuestion->updateAnswer($id_answer, $id_question, $vc_answer, $boo_correct);
				// Prepare return
				$return		= 'ok';
			}
			// Return
			echo $return;
		}

		/*
		Updates Tutor text - updateTutor()
			@return format	- print
		*/
		public function updateTutor() {
			// Declare classes
			$RepQuestion	= new RepQuestion();
			// Initialize variables
			$id_question	= (isset($_POST['id_question'])) ? trim($_POST['id_question']) : false;
			$tx_tutor		= (isset($_POST['tx_tutor'])) ? trim($_POST['tx_tutor']) : false;
			$return			= false;
			// If data were sent
			if (($id_question) && ($tx_tutor)) {
				// Update Question
				$RepQuestion->updateTutorText($id_question, $tx_tutor);
				// Prepare return
				$return		= $tx_tutor;
			}
			// Return
			echo $return;
		}

		/*
		Updates Course list - updateCourses()
			@return format	- print
		*/
		public function updateCourses() {
			// Declare classes
			$RepQuestion	= new RepQuestion();
			// Initialize variables
			$id_question	= (isset($_POST['id_question'])) ? trim($_POST['id_question']) : false;
			$courses		= explode('|', (isset($_POST['courses_arr'])) ? trim($_POST['courses_arr']) : false);
			$return			= false;
			// If data were sent
			if (($id_question) && ($courses)) {
				// Update Question
				$res		= $RepQuestion->updateQuestionCourses($id_question, $courses);
				// Prepare return
				if ($res) {
					$return	= 'ok';
				}
			}
			// Return
			echo $return;
		}

		/*
		Delete Question and associated info - deleteQuestion()
			@return format	- print
		*/
		public function deleteQuestion() {
			// Declare classes
			$RepQuestion	= new RepQuestion();
			// Initialize variables
			$return			= false;
			$id_question	= (isset($_POST['id_question'])) ? trim($_POST['id_question']) : false;
			// If values were sent
			if ($id_question) {
				// Delete question
				$return		= $RepQuestion->deleteAllQuestionInfo($id_question);
			}
			// Return
			echo $return;
		}

		/*
		Delete Answer - deleteAnswer()
			@return format	- print
		*/
		public function deleteAnswer() {
			// Declare classes
			$RepQuestion	= new RepQuestion();
			// Initialize variables
			$return			= false;
			$id_answer		= (isset($_POST['id_answer'])) ? trim($_POST['id_answer']) : false;
			// If values were sent
			if ($id_answer) {
				// Delete answer
				$return		= $RepQuestion->deleteAnswer($id_answer);
			}
			// Return
			echo $return;
		}

		/*
		Adds Question - addQuestion()
			@return format	- print
		*/
		public function addQuestion() {
			// Add Classes
			$RepQuestion	= new RepQuestion();
			// Initialize variables
			$return			= 'nok';
			$courses		= explode('|', (isset($_POST['courses'])) ? trim($_POST['courses']) :  false);
			$id_status		= (isset($_POST['id_status'])) ? trim($_POST['id_status']) :  false;
			$tx_question	= (isset($_POST['tx_question'])) ? trim($_POST['tx_question']) :  false;
			$tx_tutor		= (isset($_POST['tx_tutor'])) ? trim($_POST['tx_tutor']) :  false;
			// If data was sent
			if (($courses) && ($id_status) && ($tx_question) && ($tx_tutor)) {
				$return		= $RepQuestion->insertQuestion($courses, $id_status, $tx_question, $tx_tutor);
			}
			// Print Return
			echo $return;
		}

		/*
		Associate a course to a question - addCourse()
			@return format	- print
		*/
		public function addCourse() {
			// Add Classes
			$RepQuestion	= new RepQuestion();
			// Initialize variables
			$return			= 'nok';
			$id_question	= (isset($_POST['id_question'])) ? trim($_POST['id_question']) :  false;
			$id_course		= (isset($_POST['id_course'])) ? trim($_POST['id_course']) :  false;
			// If data was sent
			if (($id_question) && ($id_course)) {
				$return		= $RepQuestion->assocCourseQuestion($id_question, $id_course);
			}
			// Print Return
			echo $return;
		}

		/*
		Adds Answer to an existing Question - addAnswer()
			@return format	- print
		*/
		public function addAnswer() {
			// Add Classes
			$RepQuestion	= new RepQuestion();
			// Initialize variables
			$return			= 'nok';
			$id_question	= (isset($_POST['id_question'])) ? trim($_POST['id_question']) :  false;
			$vc_answer		= (isset($_POST['vc_answer'])) ? trim($_POST['vc_answer']) :  false;
			$boo_correct	= (isset($_POST['boo_correct'])) ? trim($_POST['boo_correct']) :  0;
			// If data was sent
			if (($id_question) && ($vc_answer)) {
				$return		= $RepQuestion->insertAnswer($id_question, $vc_answer, $boo_correct);
				// Prepare return
				if ($return) {
					$return	= 'ok';
				}
			}
			// Print Return
			echo $return;
		}

		/*
		Change Question Status - changeQuestionStatus()
			@return format	- print
		*/
		public function changeQuestionStatus() {
			// Declare classes
			$RepQuestion	= new RepQuestion();
			// Initialize variables
			$id_question	= (isset($_POST['id_question'])) ? trim($_POST['id_question']) : false;
			$id_status		= (isset($_POST['id_status'])) ? trim($_POST['id_status']) : false;
			$return			= false;
			// If data were sent
			if ($id_question) {
				// Update Question
				$return		= $RepQuestion->updateQuestionStatus($id_question, $id_status);
				if ($return) {
					$return	= 'ok';
				}
			}
			// Return
			echo $return;
		}

		/*
		Prints courses' partial results - loadCourses()
			@return format	- print to screen
		*/
		public function loadCourses() {
			// Declare classes
			$RepQuestion	= new RepQuestion();
			$ModQuestion	= new ModQuestion();
			// Initialize variables
			$return			= false;
			$id_field		= (isset($_POST['id_field'])) ? trim($_POST['id_field']) : false;
			// If Field id was sent
			if ($id_field) {
				// Get all fields
				$courses	= $RepQuestion->getAllCoursesByFieldId($id_field);
				// If fields were found
				if ($courses) {
					// Model return
					$return	= $ModQuestion->coursesCombo($courses);
				}
			}
			// Return
			echo $return;
		}

		/*
		Prints combo list for courses - comboCourses()
			@return format	- print to screen
		*/
		public function comboCourses() {
			// Declare classes
			$RepQuestion			= new RepQuestion();
			$ModQuestion			= new ModQuestion();
			// Initialize variables
			$return					= false;
			$id_question			= (isset($_POST['id_question'])) ? trim($_POST['id_question']) : false;
			// If Question id was sent
			if ($id_question) {
				// Get question Info
				$question			= $RepQuestion->getAllInfoById($id_question);
				if ($question) {
					// Get all possible courses (but the ones it has already
					$all_courses	= $RepQuestion->getAllAssociatedCourses($id_question, $question['courses']);
					$return			= $ModQuestion->coursesCombo($all_courses);
				}
			}
			// Return
			echo $return;
		}

	}