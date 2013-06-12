<?php
/************************************************************************************
* Name:				Course Repository												*
* File:				Application\Controller\Course.php 								*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This contains pre-written functions that execute Database tasks	*
*					related to login information.									*
*																					*
* Creation Date:	23/05/2013														*
* Version:			1.13.0523														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller\Repository;

	use Application\Controller\Repository\dbFunctions;

	class Course {
		
		/*
		Get Course by Id - getById($id)
			@param integer	- Course Id
			@return format	- Mixed array
		*/
		public function getById($id = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// if id was sent
			if ($id) {
				// Query set up
				$table			= 'tb_course AS c JOIN tb_field AS f ON (c.id_field = f.id) JOIN tb_branch AS b ON (f.id_branch = b.id)';
				$select_what	= 'c.*, f.vc_field, b.id as id_branch, b.vc_branch';
				$conditions		= "c.id = {$id}";
				$return			= $db->getRow($table, $conditions, $select_what);
			}
			// Return
			return $return;
		}

		/*
		Get All Courses - getAll($max)
			@param integer	- Max rows
			@return format	- Mixed array
		*/
		public function getAll($max = 20, $num_page = 1, $ordering = 'c.id', $direction = 'ASC') {
			$dbFunctions	= new dbFunctions();
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			$ordering		= $ordering;
			// Query set up
			$table			= 'tb_course AS c JOIN tb_field AS f ON (c.id_field = f.id) JOIN tb_branch AS b ON (f.id_branch = b.id)';
			$select_what	= 'c.*, f.vc_field, b.id as id_branch, b.vc_branch';
			$conditions		= "1";
			$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			// Return
			return $return;
		}

		/*
		Get Searched Courses - getSearched($vc_search, $max, $num_page, $ordering, $direction)
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
				$table			= 'tb_course AS c JOIN tb_field AS f ON (c.id_field = f.id) JOIN tb_branch AS b ON (f.id_branch = b.id)';
				$select_what	= 'c.*, f.vc_field, b.id as id_branch, b.vc_branch';
				$conditions		= "c.id LIKE '%{$vc_search}%' OR c.vc_course LIKE '%{$vc_search}%' OR f.vc_field LIKE '%{$vc_search}%' OR b.vc_branch LIKE '%{$vc_search}%'";
				$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			}
			// Return
			return $return;
		}

		/*
		Get All Courses In Alphabetical order - getAllBranches()
			@return format	- Mixed array
		*/
		public function getAllBranches() {
			$dbFunctions	= new dbFunctions();
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_branch';
			$select_what	= '*';
			$conditions		= "1 ORDER BY vc_branch";
			$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			// Return
			return $return;
		}

		/*
		Get All Fields In Alphabetical order - getAllFields()
			@return format	- Mixed array
		*/
		public function getAllFields($id_branch) {
			$dbFunctions	= new dbFunctions();
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			if ($id_branch) {
				$table			= 'tb_field';
				$select_what	= '*';
				$conditions		= "id_branch = {$id_branch} ORDER BY vc_field";
				$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			}
			// Return
			return $return;
		}

		/*
		Insert Course into Database - insert($data)
			@param array	- Mixed with course info (order like database w/ no id)
			@return boolean/int (id)
		*/
		public function insert($id_field = false, $vc_course = false, $level = false, $status = false) {
			// Initialize variables
			$return					= false;
			// Database Connection
			$db						= $GLOBALS['db'];
			// Validate sent information
			if (($id_field) && ($vc_course) && ($level) && ($status)) {
				// Prepare values
				$values[]		= $id_field;
				$values[]		= $level;
				$values[]		= $vc_course;
				$values[]		= $status;
				// Add Course to Database
				$res			= $db->insertRow('tb_course', $values, '');
				// Prepare ID as return
				if ($res) {
					$return		= $db->last_id();
				}
			}
			return $return;
		}

		/*
		Update course info - update($id_course, $id_field, $vc_course, $level, $status)
			@param integet	- Course Id
			@param integet	- Field Id
			@param integet	- Course's name
			@param integet	- Course's level
			@param integet	- Status
			@return boolean
		*/
		public function update($id_course = false, $id_field = false, $vc_course = false, $level = false, $status = false) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if (($id_course) && ($id_field) && ($vc_course) && ($level) && ($status)) {
				$table			= 'tb_course';
				$fields[]		= 'id_field';
				$fields[]		= 'int_level';
				$fields[]		= 'vc_course';
				$fields[]		= 'boo_active';
				$course_data[]	= $id_field;
				$course_data[]	= $level;
				$course_data[]	= $vc_course;
				$course_data[]	= $status;
				$conditions		= "id = {$id_course}";
				$return			= $db->updateRow($table, $fields, $course_data, $conditions);
			}
			return $return;
		}

		/*
		Delete Course - deleteAllCourseInfo($id_course)
			@param integer	- Course id
			@return boolean
		*/
		public function deleteAllCourseInfo($id_course = false) {
			// Initialize variables
			$return				= false;
			$question_ids		= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// If Course ID was sent
			if ($id_course) {
				// Delete all related Questions
				$ids					= $this->getQuestionsIdByCourseId($id_course);
				if ($ids) {
					foreach ($ids as $id) {
						if ($question_ids) {
							$question_ids	.= ', '.$id['id_question'];
						} else {
							$question_ids	= $id['id_question'];
						}
					}
					$db->deleteRow('tb_question', 'id IN ('.$question_ids.')');
				}
				// Delete Course
				$db->deleteRow('tb_course', 'id = '.$id_course);
				// Prepare return
				$return		= 'ok';
			}
			return $return;
		}

		/*
		Get All Questions Ids by Course Id - getQuestionsIdByCourseId($id_course)
			@param integer	- Course ID
			@return format	- Mixed array
		*/
		public function getQuestionsIdByCourseId($id_course = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// If branch id was sent
			if ($id_course) {
				// Query set up
				$table			= 'tb_question_course';
				$select_what	= 'id_question';
				$conditions		= "id_course = {$id_course}";
				$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			}
			// Return
			return $return;
		}




		/*
		Delete course - delete($id)
			@param integer	- Course id
			@return boolean
		*/
		public function delete($id = false) {
			// Initialize variables
			$return			= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			// If user ID was sent
			if ($id) {
				// Set up query
				$table		= 'tb_course';
				$conditions	= 'id = '.$id;
				$return		= $db->deleteRow($table, $conditions);
			}
			return $return;
		}

	}