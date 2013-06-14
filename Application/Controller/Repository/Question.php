<?php
/************************************************************************************
* Name:				Question Repository												*
* File:				Application\Controller\Question.php 							*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This contains pre-written functions that execute Database tasks	*
*					related to login information.									*
*																					*
* Creation Date:	29/05/2013														*
* Version:			1.12.0529														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller\Repository;

	use Application\Controller\Repository\dbFunctions;

	class Question {
		
		/*
		Get Question by Id - getById($id)
			@param integer	- Question Id
			@return format	- Mixed array
		*/
		public function getById($id = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// if email was sent
			if ($id) {
				// Query set up
				$table			= 'tb_question';
				$select_what	= '*';
				$conditions		= "id = '{$id}'";
				$return			= $db->getRow($table, $conditions, $select_what);
			}
			// Return
			return $return;
		}

		/*
		Get Course by Id - getCourseById($id)
			@param integer	- Question Id
			@return format	- Mixed array
		*/
		public function getCourseById($id = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// if id was sent
			if ($id) {
				// Query set up
				$table			= 'tb_course';
				$select_what	= '*';
				$conditions		= "id = '{$id}'";
				$return			= $db->getRow($table, $conditions, $select_what);
			}
			// Return
			return $return;
		}

		/*
		Get All Question Info by Id - getAllInfoById($id)
			@param integer	- Question Id
			@return format	- Mixed array
		*/
		public function getAllInfoById($id = false) {
			// Database Connection
			$db						= $GLOBALS['db'];
			// Initialize variables
			$return					= false;
			$courses_in				= false;
			// if email was sent
			if ($id) {
				// Get Question's info
				$table				= 'tb_question';
				$select_what		= '*';
				$conditions			= "id = '{$id}'";
				$return				= $db->getRow($table, $conditions, $select_what);
				$table				= 'tb_question_course AS qc JOIN tb_course AS c ON (qc.id_course = c.id) JOIN tb_field AS f ON (c.id_field = f.id) JOIN tb_branch AS b ON (f.id_branch = b.id)';
				$select_what		= 'c.id AS id_course, c.vc_course, f.id AS id_field, f.vc_field, b.id AS id_branch, b.vc_branch';
				$conditions			= "qc.id_question = '{$id}'";
				$return['courses']	= $db->getAllRows_Arr($table, $select_what, $conditions);
			}
			// Return
			return $return;
		}

		/*
		Get All Questions - getAll($max, $num_page, $ordering, $direction)
			@param integer	- Max rows
			@return format	- Mixed array
		*/
		public function getAll($max = 20, $num_page = 1, $ordering = 'q.id', $direction = 'ASC') {
			$dbFunctions	= new dbFunctions();
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			$ordering		= $ordering;
			// Query set up
			$table			= 'tb_question AS q JOIN tb_question_status AS s ON (q.id_status = s.id) LEFT JOIN tb_answer AS a ON (q.id = a.id_question)';
			$select_what	= 'q.id, q.id_status, s.vc_status, q.tx_question, COUNT(a.id) AS answers';
			$conditions		= "1 GROUP BY q.id";
			$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			// Return
			return $return;
		}

		/*
		Get All Question Status - getAllStatus()
			@return format	- Mixed array
		*/
		public function getAllStatus() {
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_question_status';
			$select_what	= '*';
			$conditions		= "1";
			$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			// Return
			return $return;
		}

		/*
		Get All Question Status - getAllBranches()
			@return format	- Mixed array
		*/
		public function getAllBranches() {
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_branch';
			$select_what	= '*';
			$conditions		= "1 ORDER BY vc_branch ASC";
			$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			// Return
			return $return;
		}

		/*
		Get All Courses By Field Id - getAllCoursesByFieldId($id_field)
			@param integer	- Field Id
			@return format	- Mixed array
		*/
		public function getAllCoursesByFieldId($id_field = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// If value was sent
			if ($id_field) {
				// Query set up
				$table			= 'tb_course';
				$select_what	= '*';
				$conditions		= "id_field = {$id_field} ORDER BY vc_course ASC";
				$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			}
			// Return
			return $return;
		}

		/*
		Get All Answers by Question Id - getAllAnswersByQuestionId($id_question, $max, $num_page, $ordering, $direction)
			@param integer	- Max rows
			@return format	- Mixed array
		*/
		public function getAllAnswersByQuestionId($id_question = false, $max = 10, $num_page = 1, $ordering = 'id', $direction = 'ASC') {
			$dbFunctions		= new dbFunctions();
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			$ordering			= $ordering;
			if ($id_question) {
				// Query set up
				$table			= 'tb_answer';
				$select_what	= '*';
				$conditions		= "id_question = {$id_question}";
				$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			}
			// Return
			return $return;
		}

		/*
		Get all courses that could be accepted by a given question  - getAllAssociatedCourses($id_question, $courses)
			@param integer	- Question Id
			@param array	- Courses that are already associated with the given question
			@return format	- Mixed array
		*/
		public function getAllAssociatedCourses($id_question = false, $courses = false) {
			// Database Connection
			$db						= $GLOBALS['db'];
			// Initialize variables
			$return						= false;
			$meout						= false;
			// if email was sent
			if ($id_question) {
				// Query set up
				$table					= 'tb_question_course AS qc JOIN tb_course AS c ON qc.id_course = c.id';
				$select_what			= 'c.id_field';
				$conditions				= "id_question = '{$id_question}'";
				$field					= $db->getRow($table, $conditions, $select_what);
				if (!empty($field['id_field'])) {
					$table				= 'tb_course';
					$select_what		= '*';
					$conditions			= 'id_field = '.$field['id_field'];
					if ($courses) {
						$conditions		.= ' AND id NOT IN (';
						foreach ($courses as $course) {
							if ($meout) {
								$meout	.= ', '.$course['id_course'];
							} else {
								$meout	.= $course['id_course'];
							}
						}
						$conditions	.= $meout.')';
					}
					$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
				}
			}
			// Return
			return $return;
		}

		/*
		Get Searched Questions - getSearched($vc_search, $max, $num_page, $ordering, $direction)
			@param string	- String to be searched
			@param integer	- Max rows
			@param integer	- Pager number
			@param string	- Order by
			@param string	- Ordering direction
			@return format	- Mixed array
		*/
		public function getSearched($vc_search = false, $max = 20, $num_page = 1, $ordering = 'c.id', $direction = 'ASC') {
			$dbFunctions		= new dbFunctions();
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			if ($vc_search) {
				// Query set up
				$table			= 'tb_question AS q JOIN tb_question_status AS s ON (q.id_status = s.id) LEFT JOIN tb_answer AS a ON (q.id = a.id_question)';
				$select_what	= 'q.id, q.id_status, s.vc_status, q.tx_question, q.tx_tutor, COUNT(a.id) AS answers';
				$conditions		= "q.tx_question LIKE '%{$vc_search}%' OR q.tx_tutor LIKE '%{$vc_search}%' GROUP BY q.id";
				$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			}
			// Return
			return $return;
		}

		/*
		Get Answer by Id - getAnswerById($id)
			@param integer	- Anwser Id
			@return format	- Mixed array
		*/
		public function getAnswerById($id = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// if id was sent
			if ($id) {
				// Query set up
				$table			= 'tb_answer';
				$select_what	= '*';
				$conditions		= "id = {$id}";
				$return			= $db->getRow($table, $conditions, $select_what);
			}
			// Return
			return $return;
		}

		/*
		Get Branch and Field Ids By Course id - getBranchFieldByCourseId($id)
			@param integer	- Course Id
			@return format	- Mixed array
		*/
		public function getBranchFieldByCourseId($id = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// if email was sent
			if ($id) {
				// Query set up
				$table			= 'tb_course AS c JOIN tb_field AS f ON c.id_field = f.id JOIN tb_branch AS b ON f.id_branch = b.id';
				$select_what	= 'f.id AS id_field, f.vc_field , b.id AS id_branch, b.vc_branch';
				$conditions		= "c.id = '{$id}'";
				$return			= $db->getRow($table, $conditions, $select_what);
			}
			// Return
			return $return;
		}

		/*
		Insert Question into Database - assocCourseQuestion($id_question, $id_course)
			@param integer	- Question ID
			@param integer	- Course ID
			@return integer	- Question's ID
		*/
		public function assocCourseQuestion($id_question = false, $id_course = false) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if (($id_question) && ($id_course)) {
				// Prepare values
				$values[]		= $id_course;
				$values[]		= $id_question;
				// Add Question to Database
				$db->insertRow('tb_question_course', $values, '');
				$return			= 'ok';
			}
			return $return;
		}

		/*
		Insert Question into Database - insertQuestion($courses, $id_status, $tx_question, $tx_tutor)
			@param array	- Corses' IDs
			@param integer	- Status ID
			@param text		- Question's text
			@param text		- Tutor's text
			@return integer	- Question's ID
		*/
		public function insertQuestion($courses, $id_status, $tx_question, $tx_tutor) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if (($courses) && ($id_status) && ($tx_question) && ($tx_tutor)) {
				// Prepare values
				$values[]		= $id_status;
				$values[]		= $tx_question;
				$values[]		= $tx_tutor;
				// Add Question to Database
				$db->insertRow('tb_question', $values, '');
				$question_id	= $db->last_id();
				foreach ($courses as $course) {
					$db->insertRow('tb_question_course', array($course, $question_id), '');
				}
				$return			= $question_id;
			}
			return $return;
		}

		/*
		Insert Answer into Database - insertAnswer($id_question, $vc_answer, $boo_correct)
			@param integer	- Question ID
			@param string	- Answer
			@param boolean	- If answer is correct
			@return boolean
		*/
		public function insertAnswer($id_question = false, $vc_answer = false, $boo_correct = 0) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if (($id_question) && ($vc_answer)) {
				if ($boo_correct == 1) {
					$db->updateRow('tb_answer', array('boo_correct'), array(0), 'id_question = '.$id_question);
				}
				// Prepare values
				$values[]		= $id_question;
				$values[]		= $vc_answer;
				$values[]		= $boo_correct;
				// Add Branch to Database
				$return			= $db->insertRow('tb_answer', $values, '');
			}
			return $return;
		}

		/*
		Update Question Text - updateQuestionText($id_question, $tx_question)
			@param integer	- Question ID
			@param string	- Question Text
			@return boolean
		*/
		public function updateQuestionText($id_question = false, $tx_question = false) {
			// Initialize variables
			$return			= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			// Validate sent information
			if (($id_question) && ($tx_question)) {
				$table		= 'tb_question';
				$data[]		= $tx_question;
				$fields[]	= 'tx_question';
				$conditions	= "id = {$id_question}";
				$return		= $db->updateRow($table, $fields, $data, $conditions);
			}
			return $return;
		}

		/*
		Update Answer Text - updateQuestionStatus($id_question, $boo_active)
			@param integer	- Question ID
			@param boolean	- Question status
			@return boolean
		*/
		public function updateQuestionStatus($id_question = false, $id_status = false) {
			// Initialize variables
			$return			= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			// Validate sent information
			if (($id_question) && ($id_status)) {
				$data[]		= $id_status;
				$fields[]	= 'id_status';
				$conditions	= "id = {$id_question}";
				$return		= $db->updateRow('tb_question', $fields, $data, $conditions);
			}
			return $return;
		}

		/*
		Update Answer Text - updateAnswer($id_answer, $vc_answer, $boo_correct)
			@param integer	- Answer ID
			@param string	- Answer Text
			@param boolean	- If answer is correct
			@return boolean
		*/
		public function updateAnswer($id_answer = false, $id_question = false, $vc_answer = false, $boo_correct = 0) {
			// Initialize variables
			$return			= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			// Validate sent information
			if (($id_answer) && ($id_question) && ($vc_answer)) {
				if ($boo_correct == 1) {
					$db->updateRow('tb_answer', array('boo_correct'), array(0), 'id_question = '.$id_question);
				}
				$db->updateRow('tb_answer', array('boo_correct'), array(0), 'id_question = '.$id_question);
				$data[]		= $vc_answer;
				$data[]		= $boo_correct;
				$fields[]	= 'vc_answer';
				$fields[]	= 'boo_correct';
				$conditions	= "id = {$id_answer}";
				$return		= $db->updateRow('tb_answer', $fields, $data, $conditions);
			}
			return $return;
		}

		/*
		Update Tutor Text - updateTutorText($id_question, $tx_tutor)
			@param integer	- Question ID
			@param string	- Tutor Text
			@return boolean
		*/
		public function updateTutorText($id_question = false, $tx_tutor = false) {
			// Initialize variables
			$return			= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			// Validate sent information
			if (($id_question) && ($tx_tutor)) {
				$table		= 'tb_question';
				$data[]		= $tx_tutor;
				$fields[]	= 'tx_tutor';
				$conditions	= "id = {$id_question}";
				$return		= $db->updateRow($table, $fields, $data, $conditions);
			}
			return $return;
		}

		/*
		Update Tutor Text - updateQuestionCourses($id_question, $courses)
			@param integer	- Question ID
			@param string	- Tutor Text
			@return boolean
		*/
		public function updateQuestionCourses($id_question = false, $courses = false) {
			// Initialize variables
			$return			= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			// Validate sent information
			if (($id_question) && ($courses)) {
				$db->deleteRow('tb_question_course', 'id_question = '.$id_question);
				foreach ($courses as $course) {
					$db->insertRow('tb_question_course', array($course, $id_question), '');
				}
				$return		= true;
			}
			return $return;
		}

		/*
		Delete Question - deleteAllQuestionInfo($id_question)
			@param integer	- Field id
			@return boolean
		*/
		public function deleteAllQuestionInfo($id_question = false) {
			// Initialize variables
			$return			= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			// If user ID was sent
			if ($id_question) {
				$db->deleteRow('tb_question', 'id = '.$id_question);
				$db->deleteRow('tb_answer', 'id_question = '.$id_question);
				// Prepare return
				$return		= 'ok';
			}
			return $return;
		}

		/*
		Delete Answer - deleteAnswer($id_answer)
			@param integer	- Field id
			@return boolean
		*/
		public function deleteAnswer($id_answer = false) {
			// Initialize variables
			$return			= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			// If user ID was sent
			if ($id_answer) {
				$db->deleteRow('tb_answer', 'id = '.$id_answer);
				// Prepare return
				$return		= 'ok';
			}
			return $return;
		}

	}