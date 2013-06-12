<?php
/************************************************************************************
* Name:				Branch Repository												*
* File:				Application\Controller\Branch.php 								*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This contains pre-written functions that execute Database tasks	*
*					related to login information.									*
*																					*
* Creation Date:	06/05/2013														*
* Version:			1.13.0506														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller\Repository;

	use Application\Controller\Repository\dbFunctions;

	class Branch {
		
		/*
		Get Branch by Id - getById($id)
			@param integer	- Branch Id
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
				$table			= 'tb_branch';
				$select_what	= '*';
				$conditions		= "id = {$id}";
				$return			= $db->getRow($table, $conditions, $select_what);
			}
			// Return
			return $return;
		}

		/*
		Get Field by Id - getFieldById($id)
			@param integer	- Field Id
			@return format	- Mixed array
		*/
		public function getFieldById($id = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// if id was sent
			if ($id) {
				// Query set up
				$table			= 'tb_field';
				$select_what	= '*';
				$conditions		= "id = {$id}";
				$return			= $db->getRow($table, $conditions, $select_what);
			}
			// Return
			return $return;
		}

		/*
		Get Field Info by Id - getFieldById($id)
			@param integer	- Field Id
			@return format	- Mixed array
		*/
		public function getFieldInfoById($id = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// if id was sent
			if ($id) {
				// Get field's basic info
				$field			= $this->getFieldById($id);
				// Get number of courses
				$tot_courses	= $this->countCoursesByFieldId($id);
				// Gets number of questions per course
				$questions		= $this->countQuestionsByCourseByFieldId($id);
				// Prepare return
				$return[]		= $field;
				$return[]		= $tot_courses;
				$return[]		= $questions;
			}
			// Return
			return $return;
		}

		/*
		Get All Branches - getAll($max)
			@param integer	- Max rows
			@return format	- Mixed array
		*/
		public function getAll($max = 20, $num_page = 1, $ordering = 'b.id', $direction = 'ASC') {
			$dbFunctions	= new dbFunctions();
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_branch AS b LEFT JOIN tb_field AS f ON (b.id = f.id_branch) LEFT JOIN tb_course AS c ON (f.id = c.id_field)';
			$select_what	= 'b.*, COUNT(f.id) AS tot_fields, COUNT(c.id) AS tot_courses';
			$conditions		= "1 GROUP BY b.id";
			$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			// Return
			return $return;
		}

		/*
		Get Searched Branches - getSearched($vc_search, $mas)
			@param string	- String to be searched
			@param integer	- Max rows
			@return format	- Mixed array
		*/
		public function getSearched($vc_search = false, $max = 20, $num_page = 1, $ordering = 'id', $direction = 'ASC') {
			$dbFunctions		= new dbFunctions();
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			if ($vc_search) {
				// Query set up
				$table			= 'tb_branch AS b LEFT JOIN tb_field AS f ON (b.id = f.id_branch) LEFT JOIN tb_course AS c ON (f.id = c.id_field)';
				$select_what	= 'b.*, COUNT(f.id) AS tot_fields, COUNT(c.id) AS tot_courses';
				$conditions		= "b.id LIKE '%{$vc_search}%' OR b.vc_branch LIKE '%{$vc_search}%'";
				$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			}
			// Return
			return $return;
		}

		/*
		Get Searched Fields - getSearchedFieldsBranchId($id_branch, $vc_search, $mas)
			@param string	- String to be searched
			@param integer	- Max rows
			@return format	- Mixed array
		*/
		public function getSearchedFieldsBranchId($id_branch = false, $vc_search = false, $max = 20, $num_page = 1, $ordering = 'id', $direction = 'ASC') {
			$dbFunctions	= new dbFunctions();
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			if ($vc_search) {
				// Query set up
				$table			= 'tb_field';
				$select_what	= '*';
				$conditions		= "(id LIKE '%{$vc_search}%' OR vc_field LIKE '%{$vc_search}%') AND id_branch = {$id_branch}";
				$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			}
			// Return
			return $return;
		}

		/*
		Get All Fields by Branch Id - getFieldsBranchId($id_branch, $max, $num_page, $ordering, $direction)
			@param integer	- Max rows
			@return format	- Mixed array
		*/
		public function getFieldsBranchId($id_branch = false, $max = 10, $num_page = 1, $ordering = 'id', $direction = 'ASC') {
			// Declare classes
			$dbFunctions		= new dbFunctions();
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// If branch id was sent
			if ($id_branch) {
				// Query set up
				$table			= 'tb_field';
				$select_what	= '*';
				$conditions		= "id_branch = {$id_branch}";
				$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			}
			// Return
			return $return;
		}

		/*
		Get All Fields IDs by Branch Id - getFieldsIdbyBranchId($id_branch)
			@param integer	- Branch ID
			@return format	- Mixed array
		*/
		public function getFieldsIdbyBranchId($id_branch = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// If branch id was sent
			if ($id_branch) {
				// Query set up
				$table			= 'tb_field';
				$select_what	= 'id';
				$conditions		= "id_branch = {$id_branch}";
				$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			}
			// Return
			return $return;
		}

		/*
		Get All Courses IDs by Field Id - getCoursesIdbyFieldId($id_field)
			@param integer	- Field ID
			@return format	- Mixed array
		*/
		public function getCoursesIdbyFieldId($id_field = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// If branch id was sent
			if ($id_field) {
				// Query set up
				$table			= 'tb_course';
				$select_what	= 'id';
				$conditions		= "id_field = {$id_field}";
				$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			}
			// Return
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
		Count how many courses are in a given field - countCoursesByFieldId($id_field)
			@param integer	- Field id
			@return format	- Integer
		*/
		public function countCoursesByFieldId($id_field = false) {
			$db				= $GLOBALS['db'];
			$return			= false;
			if ($id_field) {
				$table			= 'tb_course';
				$select_what	= 'COUNT(*) as total';
				$conditions		= "id_field = {$id_field}";
				$return			= $db->getRow($table, $conditions, $select_what);
			}
			return $return;
		}

		/*
		Count how many courses are in a given field - countCoursesByFieldId($id_field)
			@param integer	- Field id
			@return format	- Integer
		*/
		public function countQuestionsByCourseByFieldId($id_field = false) {
			$db					= $GLOBALS['db'];
			$return				= false;
			if ($id_field) {
				$table			= 'tb_field AS f JOIN tb_course AS c ON (f.id = c.id_field) JOIN tb_question_course AS q ON (c.id = q.id_course)';
				$select_what	= 'c.id, c.vc_course, count(q.id) as total';
				$conditions		= "f.id = {$id_field} GROUP BY c.id";
				$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			}
			return $return;
		}

		/*
		Count how many questions are in a given field - countCoursesByFieldId($id_field)
			@param integer	- Field id
			@return format	- Integer
		*/
		public function countQuestionsByFieldId($id_field = false) {
			$db					= $GLOBALS['db'];
			$return				= false;
			if ($id_field) {
				$table			= 'tb_field AS f JOIN tb_course AS c ON (f.id = c.id_field) JOIN tb_question_course AS q ON (c.id = q.id_course)';
				$select_what	= 'count(q.id_course) as total';
				$conditions		= "f.id = {$id_field} GROUP BY q.id_course";
				$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			}
			return $return;
		}

		/*
		Insert Branch into Database - insert($data)
			@param array	- Mixed with user info (order like database w/ no id)
			@return boolean/int (id)
		*/
		public function insert($branch = false) {
			// Initialize variables
			$return					= false;
			// Database Connection
			$db						= $GLOBALS['db'];
			// Validate sent information
			if ($branch) {
				// Check if branch is already created
				$table				= 'tb_branch';
				$conditions			= "vc_branch = '{$branch}'";
				$select_what		= 'id';
				$return				= $db->getRow($table, $conditions, $select_what);
				// If branch wasnt creatd so far
				if (!$return) {
					// Prepare values
					$values[]		= $branch;
					// Add Branch to Database
					$res			= $db->insertRow('tb_branch', $values, '');
					// Prepare ID as return
					if ($res) {
						$return		= $db->last_id();
					}
				}
			}
			return $return;
		}

		/*
		Insert Fields into Database - insertFields($id_branch, $fields)
			@param array	- Mixed with user info (order like database w/ no id)
			@return boolean
		*/
		public function insertFields($id_branch = false, $fields = false) {
			// Initialize variables
			$return					= false;
			// Database Connection
			$db						= $GLOBALS['db'];
			// Validate sent information
			if (($id_branch) && ($fields)) {
				$tot_fields			= count($fields);
				for ($i = 0; $i < $tot_fields; $i++) {
					// Prepare values
					$values[]		= $id_branch;
					$values[]		= $fields[$i];
					$values[]		= 1;
					// Add Branch to Database
					$return			= $db->insertRow('tb_field', $values, '');
					$values			= false;
				}
			}
			return $return;
		}

		/*
		Insert One Field into Database - insertField($id_branch, $vc_field)
			@param integer	- Branch ID
			@param string	- Field name
			@return boolean
		*/
		public function insertField($id_branch = false, $vc_field = false) {
			// Initialize variables
			$return					= false;
			// Database Connection
			$db						= $GLOBALS['db'];
			// Validate sent information
			if (($id_branch) && ($vc_field)) {
				// Prepare values
				$values[]		= $id_branch;
				$values[]		= $vc_field;
				$values[]		= 1;
				// Add Branch to Database
				$return			= $db->insertRow('tb_field', $values, '');
				$values			= false;
			}
			return $return;
		}

		/*
		Update branch info - update($branch_data)
			@param array	- Mixed with branch info (order like database w/ id)
			@return boolean
		*/
		public function update($branch_data = false) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if ($branch_data) {
				$id				= (isset($branch_data[0])) ? $branch_data[0] : false;
				$branch			= (isset($branch_data[1])) ? $branch_data[1] : false;
				if (($id) && ($branch)) {
					$table		= 'tb_branch';
					$fields[]	= 'id';
					$fields[]	= 'vc_branch';
					$conditions	= "id = {$id}";
					$return		= $db->updateRow($table, $fields, $branch_data, $conditions);
				}
			}
			return $return;
		}

		/*
		Update field info - updateField($id, $field)
			@param integer	- Field id
			@param string	- Field names
			@return boolean
		*/
		public function updateField($id = false, $field = false) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if (($id) && ($field)) {
				$field_data[]	= $id;
				$field_data[]	= $field;
				$table			= 'tb_field';
				$fields[]		= 'id';
				$fields[]		= 'vc_field';
				$conditions		= "id = {$id}";
				$return			= $db->updateRow($table, $fields, $field_data, $conditions);
			}
			return $return;
		}

		/*
		Change field status - changeFieldStatus($id, $status)
			@param integer	- Field id
			@param boolean	- Field status
			@return boolean
		*/
		public function changeFieldStatus($id = false, $status = 0) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if ($id) {
				$field_data[]	= $id;
				$field_data[]	= $status;
				$table			= 'tb_field';
				$fields[]		= 'id';
				$fields[]		= 'boo_active';
				$conditions		= "id = {$id}";
				$return			= $db->updateRow($table, $fields, $field_data, $conditions);
			}
			return $return;
		}

		/*
		Delete branch - delete($id)
			@param integer	- Branch id
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
				$table		= 'tb_branch';
				$conditions	= 'id = '.$id;
				$return		= $db->deleteRow($table, $conditions);
			}
			return $return;
		}

		/*
		Delete branch - deleteAllBranchInfo($id_branch)
			@param integer	- Branch id
			@return boolean
		*/
		public function deleteAllBranchInfo($id_branch = false) {
			// Initialize variables
			$return				= false;
			$question_ids		= false;
			$field_ids			= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// If user ID was sent
			if ($id_branch) {
				// Get all related fields
				$fields			= $this->getFieldsIdbyBranchId($id_branch, 0);
				if ($fields) {
					foreach ($fields as $field) {
						$courses_ids				= false;
						// Get all related courses
						$courses					= $this->getCoursesIdByFieldId($field['id']);
						if ($courses) {
							// Delete all related questions
							foreach ($courses as $course) {
								$question_ids			= false;
								$ids					= $this->getQuestionsIdByCourseId($course['id']);
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
								if ($courses_ids) {
									$courses_ids		.= ', '.$course['id'];
								} else {
									$courses_ids		= $course['id'];
								}
							}
							if ($courses_ids) {
								// Delete all related courses
								$db->deleteRow('tb_course', 'id IN ('.$courses_ids.')');
							}
						}
						if ($field_ids) {
							$field_ids				.= ', '.$field['id'];
						} else {
							$field_ids				= $field['id'];
						}
					}
					// Delete all related fields
					$db->deleteRow('tb_field', 'id IN ('.$field_ids.')');
				}
				// Delete branch
				$return		= $db->deleteRow('tb_branch', 'id = '.$id_branch);
				// Prepare return
				$return		= 'ok';
			}
			return $return;
		}

		/*
		Delete Field - deleteAllFieldInfo($id_field)
			@param integer	- Field id
			@return boolean
		*/
		public function deleteAllFieldInfo($id_field = false) {
			// Initialize variables
			$return				= false;
			$question_ids		= false;
			$courses_ids		= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// If user ID was sent
			if ($id_field) {
				// Get all related courses
				$courses					= $this->getCoursesIdByFieldId($id_field);
				if ($courses) {
					// Delete all related questions
					foreach ($courses as $course) {
						$question_ids			= false;
						$ids					= $this->getQuestionsIdByCourseId($course['id']);
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
						if ($courses_ids) {
							$courses_ids		.= ', '.$course['id'];
						} else {
							$courses_ids		= $course['id'];
						}
					}
					if ($courses_ids) {
						// Delete all related courses
						$db->deleteRow('tb_course', 'id IN ('.$courses_ids.')');
					}
				}
				// Delete all related fields
				$db->deleteRow('tb_field', 'id = '.$id_field);
				// Prepare return
				$return		= 'ok';
			}
			return $return;
		}

	}