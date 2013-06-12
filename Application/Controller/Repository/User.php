<?php
/************************************************************************************
* Name:				User Repository													*
* File:				Application\Controller\User.php 								*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This contains pre-written functions that execute Database tasks	*
*					related to login information.									*
*																					*
* Creation Date:	06/06/2013														*
* Version:			1.13.0606														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller\Repository;

	use Application\Controller\Repository\dbFunctions;

	class User {
		
		/*
		Get User by Id - getById($id)
			@param integer	- User Id
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
				$table			= 'tb_user';
				$select_what	= '*';
				$conditions		= "id = '{$id}'";
				$return			= $db->getRow($table, $conditions, $select_what);
			}
			// Return
			return $return;
		}

		/*
		Get User by Email - getByEmail($email)
			@param string	- User email
			@return format	- Mixed array
		*/
		public function getByEmail($email = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// if email was sent
			if ($email) {
				// Query set up
				$table			= 'tb_user';
				$select_what	= '*';
				$conditions		= "vc_email = '{$email}'";
				$return			= $db->getRow($table, $conditions, $select_what);
			}
			// Return
			return $return;
		}

		/*
		Get All Users - getAll($max, $num_page, $ordering, $direction)
			@param integer	- Max rows
			@param integer	- Page number
			@param integer	- Ordering
			@param integer	- Ordering direction
			@return format	- Mixed array
		*/
		public function getAll($max = 20, $num_page = 1, $ordering = 'u.id', $direction = 'ASC') {
			$dbFunctions	= new dbFunctions();
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_user AS u JOIN tb_profile AS p ON (u.id_profile = p.id)';
			$select_what	= 'u.*, p.vc_profile';
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
				$table			= 'tb_user AS u JOIN tb_profile AS p ON (u.id_profile = p.id)';
				$select_what	= 'u.*, p.vc_profile';
				$conditions		= "u.id LIKE '%{$vc_search}%' OR u.vc_user LIKE '%{$vc_search}%' OR u.vc_email LIKE '%{$vc_search}%'";
				$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			}
			// Return
			return $return;
		}

		/*
		Get All User Profiles - getAllProfiles()
			@return format	- Mixed array
		*/
		public function getAllProfiles() {
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_profile';
			$select_what	= '*';
			$conditions		= "1";
			$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			// Return
			return $return;
		}

		/*
		Check username - checkUser($user)
			@param string	- user name
			@return bool
		*/
		public function checkUser($user = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			$user				= false;
			// if username was sent
			if ($user) {
				// Query set up
				$table			= 'tb_user';
				$select_what	= 'id';
				$conditions		= "id <> 1 AND vc_user = '{$user}'";
				$user			= $db->getRow($table, $conditions, $select_what);
				if ($user) {
					$return		= true;
				}
			}
			// Return
			return $return;
		}

		/*
		Check email - checkEmail($email)
			@param string	- user email
			@return bool
		*/
		public function checkEmail($email = false) {
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			$user			= false;
			// if email was sent
			if ($email) {
				// Query set up
				$table			= 'tb_user';
				$select_what	= 'id';
				$conditions		= "id <> 1 AND vc_email = '{$email}'";
				$user			= $db->getRow($table, $conditions, $select_what);
				if ($user) {
					$return		= true;
				}
			}
			// Return
			return $return;
		}

		/*
		Insert user into Database - insert($user_data)
			@param array	- Mixed with user info (order like database w/ no id)
			@return boolean
		*/
		public function insert($user_data = false) {
			// Initialize variables
			$return						= false;
			// Database Connection
			$db							= $GLOBALS['db'];
			// Validate sent information
			if ($user_data) {
				$id_profile				= (isset($user_data[0])) ? $user_data[0] : false;
				$vc_user				= (isset($user_data[1])) ? $user_data[1] : false;
				$vc_email				= (isset($user_data[2])) ? $user_data[2] : false;
				$vc_password			= (isset($user_data[3])) ? $user_data[3] : false;
				if (($id_profile) && ($vc_user) && ($vc_email) && ($vc_password)) {
					// Check availability
					$check_user			= $this->checkUser($vc_user);
					$check_email		= $this->checkEmail($vc_email);
					if ($check_user) {
						$return			= 'QM: User already taken. Please choose another one.';
					} else if ($check_email) {
						$return			= 'QM: Email already taken. Please choose another one.';
					// If username and email are free to use
					} else {
						// Prepare values
						$user_data[]	= 1;
						// Add User to Database
						$db->insertRow('tb_user', $user_data, '');
						$return			= true;
					}
				}
			}
			return $return;
		}

		/*
		Update user info - update($user_data)
			@param array	- Mixed with user info (order like database w/ id)
			@return boolean
		*/
		public function update($id_user, $user_data = false) {
			// Initialize variables
			$return						= false;
			// Database Connection
			$db							= $GLOBALS['db'];
			// Validate sent information
			if ($user_data) {
				$id_profile				= (isset($user_data[0])) ? $user_data[0] : false;
				$vc_user				= (isset($user_data[1])) ? $user_data[1] : false;
				$vc_email				= (isset($user_data[2])) ? $user_data[2] : false;
				$vc_password			= (isset($user_data[3])) ? $user_data[3] : false;
				if (($id_user) && ($id_profile) && ($vc_user) && ($vc_email)) {
					$table				= 'tb_user';
					$fields[]			= 'id_profile';
					$fields[]			= 'vc_user';
					$fields[]			= 'vc_email';
					if ($vc_password) {
						$fields[]		= 'vc_password';
					} else {
						unset($user_data[3]);
					}
					$conditions			= "id = {$id_user}";
					$return				= $db->updateRow($table, $fields, $user_data, $conditions);
				}
			}
			return $return;
		}

		/*
		Delete user - delete($id)
			@param array	- Mixed with user info (order like database w/ id)
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
				$table		= 'tb_user';
				$conditions	= 'id = '.$id;
				$return		= $db->deleteRow($table, $conditions);
			}
			return $return;
		}

	}