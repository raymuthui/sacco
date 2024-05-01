<?php
session_start();
ini_set('display_errors', 1);
class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	public function login()
	{
		extract($_POST);
		// Check in the users table
		$qry = $this->db->query("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			return 1;
		} else {
			// Check in the members table if not found in the users table
			$qry = $this->db->query("SELECT * FROM members WHERE email = '$username' AND password = '$password'");
			if ($qry->num_rows > 0) {
				$member = $qry->fetch_assoc();
				if ($member['status'] == 1) {
					foreach ($member as $key => $value) {
						if ($key != 'password' && !is_numeric($key)) {
							$_SESSION['login_' . $key] = $value;
						}
					}
					return 2;
				} else {
					return 3; // Member is not active
				}
			} else {
				return 3; // Invalid username/password
			}
		}
	}
	function login2()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '" . $email . "' and password = '" . md5($password) . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			return 1;
		} else {
			return 3;
		}
	}
	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", contact = '$contact'";
		$data .= ", username = '$username' ";
		$data .= ", password = '$password' ";
		$data .= ", type = '$type' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set " . $data);
		} else {
			$save = $this->db->query("UPDATE users set " . $data . " where id = " . $id);
		}
		if ($save) {
			return 1;
		}
	}
	function delete_user()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = " . $id);
		if ($delete)
			return 1;
	}
	function signup()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", contact = '$contact' ";
		$data .= ", address = '$address' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '" . md5($password) . "' ";
		$data .= ", type = 3";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if ($chk > 0) {
			return 2;
			exit;
		}
		$save = $this->db->query("INSERT INTO users set " . $data);
		if ($save) {
			$qry = $this->db->query("SELECT * FROM users where username = '" . $email . "' and password = '" . md5($password) . "' ");
			if ($qry->num_rows > 0) {
				foreach ($qry->fetch_array() as $key => $value) {
					if ($key != 'passwors' && !is_numeric($key))
						$_SESSION['login_' . $key] = $value;
				}
			}
			return 1;
		}
	}

	function save_news()
	{
		extract($_POST);
	
		// Handle picture upload
		$article_image_file = $_FILES['article_image']['name'];
		$article_image_temp = $_FILES['article_image']['tmp_name'];
	
		// Move uploaded picture to a directory on the server if a new file is uploaded
		if (!empty($article_image_file)) {
			$upload_dir = 'uploads/';
			move_uploaded_file($article_image_temp, $upload_dir . $article_image_file);
			$article_image_path = $upload_dir . $article_image_file;
		} else {
			// If no new file is uploaded, do not update article_image_path
			$article_image_path = null;
		}
	
		// Use prepared statements to insert or update news data into the database
		if (empty($id)) {
			$stmt = $this->db->prepare("INSERT INTO news (article_title, article_content, article_image_path) VALUES (?, ?, ?)");
			$stmt->bind_param("sss", $article_title, $article_content, $article_image_path);
		} else {
			$stmt = $this->db->prepare("UPDATE news SET article_title = ?, article_content = ?, article_image_path = ? WHERE id = ?");
			$stmt->bind_param("sssi", $article_title, $article_content, $article_image_path, $id);
		}
	
		// Execute the statement
		$save = $stmt->execute();
	
		if ($save) {
			// Return 2 if action is update
			if (!empty($id)) {
				return 2;
			} else {
				return 1;
			}
		} else {
			return 0;
		}
	}
	
	
	
	function delete_news()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM news where id = " . $id);
		if ($delete) {
			return 1;
		} else {
			return 0;
		}
	}
	

	function save_settings()
	{
		extract($_POST);
		$data = " name = '" . str_replace("'", "&#x2019;", $name) . "' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '" . htmlentities(str_replace("'", "&#x2019;", $about)) . "' ";
		if ($_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], '../assets/img/' . $fname);
			$data .= ", cover_img = '$fname' ";
		}

		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set " . $data);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set " . $data);
		}
		if ($save) {
			$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
			foreach ($query as $key => $value) {
				if (!is_numeric($key))
					$_SESSION['setting_' . $key] = $value;
			}

			return 1;
		}
	}


	function save_loan_type()
	{
		extract($_POST);
		$data = " type_name = '$type_name' ";
		$data .= " , description = '$description' ";
		$data .= " , min_amount = '$min_amount' ";
		$data .= " , max_amount = '$max_amount' ";
		$data .= " , months = '$months' ";
		$data .= ", interest_percentage = '$interest_percentage' ";
		$data .= ", penalty_rate = '$penalty_rate' ";

		if (empty($id)) {
			$save = $this->db->query("INSERT INTO loan_types set " . $data);
		} else {
			$save = $this->db->query("UPDATE loan_types set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_loan_type()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM loan_types where id = " . $id);
		if ($delete)
			return 1;
	}
	// function save_plan()
	// {
	// 	extract($_POST);
	// 	$data = " months = '$months' ";
	// 	$data .= ", interest_percentage = '$interest_percentage' ";
	// 	$data .= ", penalty_rate = '$penalty_rate' ";

	// 	if (empty($id)) {
	// 		$save = $this->db->query("INSERT INTO loan_plan set " . $data);
	// 	} else {
	// 		$save = $this->db->query("UPDATE loan_plan set " . $data . " where id=" . $id);
	// 	}
	// 	if ($save)
	// 		return 1;
	// }
	// function delete_plan()
	// {
	// 	extract($_POST);
	// 	$delete = $this->db->query("DELETE FROM loan_plan where id = " . $id);
	// 	if ($delete)
	// 		return 1;
	// }
	// function save_borrower()
	// {
	// 	extract($_POST);
	
	// 	$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
	
	// 	if(empty($member_id)){
	// 		return 2; // Member not selected
	// 	}
	
	// 	// Use $_POST data directly instead of undefined variables
	// 	$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
	// 	$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '';
	// 	$middlename = isset($_POST['middlename']) ? $_POST['middlename'] : '';
	// 	$address = isset($_POST['address']) ? $_POST['address'] : '';
	// 	$contact_no = isset($_POST['contact_no']) ? $_POST['contact_no'] : '';
	// 	$email = isset($_POST['email']) ? $_POST['email'] : '';
	// 	$tax_id = isset($_POST['tax_id']) ? $_POST['tax_id'] : '';
	
	// 	// Insert borrower data into the database
	// 	$data = " lastname = '$lastname' ";
	// 	$data .= ", firstname = '$firstname' ";
	// 	$data .= ", middlename = '$middlename' ";
	// 	$data .= ", address = '$address' ";
	// 	$data .= ", contact_no = '$contact_no' ";
	// 	$data .= ", email = '$email' ";
	// 	$data .= ", tax_id = '$tax_id' ";
	// 	$data .= ", member_id = $member_id ";
	
	// 	$save = $this->db->query("INSERT INTO borrowers SET " . $data);
	
	// 	if ($save) {
	// 		return 1; // Success
	// 	} else {
	// 		return 0; // Failed to save borrower data
	// 	}
	// }
	

	// function delete_borrower()
	// {
	// 	extract($_POST);
	// 	$delete = $this->db->query("DELETE FROM borrowers where id = " . $id);
	// 	if ($delete)
	// 		return 1;
	// }
	function fetch_member_details()
	{
		extract($_POST);

		// Check if member_id is set and not empty
		if (isset($member_id) && !empty($member_id)) {
			// Fetch member details from the members table
			$qry = $this->db->query("SELECT * FROM members WHERE id = $member_id");
			if ($qry->num_rows > 0) {
				$member_details = $qry->fetch_assoc();
				return $member_details;
			} else {
				return null; // Member not found
			}
		} else {
			return null; // No member_id provided
		}
	}
	function save_loan()
	{
		// Check if all required fields are present in $_POST
		// if (
		// 	empty($_POST['member_id']) ||
		// 	!isset($_POST['loan_type_id']) || empty($_POST['loan_type_id']) ||
		// 	!isset($_POST['amount']) || empty($_POST['amount']) ||
		// 	!isset($_POST['purpose']) || empty($_POST['purpose']) ||
		// 	empty($_POST['status'])
		// ) {
		// 	return "Error: Required data missing.";
		// }

		// Extract POST data
		extract($_POST);

		// Assign member_id from POST data
		$member_id = $_POST['member_id'];

		// Fetch loan type details including the months value
		$loan_type_qry = $this->db->query("SELECT * FROM loan_types WHERE id = $loan_type_id");
		if ($loan_type_qry->num_rows > 0) {
			$loan_type = $loan_type_qry->fetch_assoc();
			$months = $loan_type['months'];

			// Proceed with other operations...
			$data = " member_id = $member_id ";
			$data .= ", loan_type_id = '$loan_type_id' ";
			$data .= ", amount = '$amount' ";
			// $data .= ", installment_amount = '$installment_amount' ";
			$data .= ", purpose = '$purpose' ";
			$data .= ", status = '$status' ";

			// Check if date_created is empty before setting it
			if (empty($id)) {
				$data .= ", date_created = NOW() ";
			}

			// Check if status is 2 (Released) to update date_released
			if ($status == 2) {
				// Assuming 'id' is the correct field name for the loan ID
				$plan = $this->db->query("SELECT * FROM loan_list WHERE id = $id")->fetch_assoc();
				if ($plan) {
					for ($i = 1; $i <= $months; $i++) {
						$date = date("Y-m-d", strtotime(date("Y-m-d") . " +" . $i . " months"));
						$chk = $this->db->query("SELECT * FROM loan_schedules WHERE loan_id = $id AND date(date_due) = '$date'");
						if ($chk->num_rows > 0) {
							$ls_id = $chk->fetch_assoc()['id'];
							$this->db->query("UPDATE loan_schedules SET loan_id = $id, date_due = '$date' WHERE id = $ls_id");
						} else {
							$this->db->query("INSERT INTO loan_schedules SET loan_id = $id, date_due = '$date'");
							$ls_id = $this->db->insert_id;
						}
						$sid[] = $ls_id;
					}
					if (!empty($sid)) {
						$sid_str = implode(",", $sid);
						$this->db->query("DELETE FROM loan_schedules WHERE loan_id = $id AND id NOT IN ($sid_str)");
						// Only update date_released if it's not already set
						if (empty($plan['date_released'])) {
							$data .= ", date_released = NOW() ";
						}
					}
				}
			} else {
				//TODO: Revert here
				//$this->db->query("DELETE FROM loan_schedules WHERE loan_id = $id");
			}

			// Generate unique ref_no if creating a new loan
			if (empty($id)) {
				$ref_no = mt_rand(1, 99999999);
				do {
					$check = $this->db->query("SELECT * FROM loan_list WHERE ref_no = '$ref_no'")->num_rows;
					if ($check > 0) {
						$ref_no = mt_rand(1, 99999999);
					} else {
						break;
					}
				} while (true);
				$data .= ", ref_no = '$ref_no' ";
			}

			// Insert or update loan data
			if (empty($id)) {
				$save = $this->db->query("INSERT INTO loan_list SET " . $data);
			} else {
				$save = $this->db->query("UPDATE loan_list SET " . $data . " WHERE id = $id");
			}

			if ($save) {
				return 1;
			} else {
				return "Error: Failed to save loan. " . $this->db->error;
			}
		} else {
			return "Error: Loan type details not found";
		}
	}

	function delete_loan()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM loan_list where id = " . $id);
		if ($delete)
			return 1;
	}
	function save_payment()
	{
		extract($_POST);
		$data = " loan_id = $loan_id ";
		$data .= " , payment_method = '$payment_method' ";
		$data .= " , amount = '$amount' ";
		$data .= " , penalty_amount = '$penalty_amount' ";
		$data .= " , overdue = '$overdue' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO payments set " . $data);
		} else {
			$save = $this->db->query("UPDATE payments set " . $data . " where id = " . $id);
		}
		if ($save)
			return 1;
	}
	function delete_payment()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM payments where id = " . $id);
		if ($delete)
			return 1;
	}

	function save_member()
	{
		extract($_POST);

		// Handle ID picture uploads
		$id_front_file = $_FILES['id_front']['name'];
		$id_front_temp = $_FILES['id_front']['tmp_name'];
		$id_back_file = $_FILES['id_back']['name'];
		$id_back_temp = $_FILES['id_back']['tmp_name'];
		$profile_pic_file = $_FILES['profile_pic']['name'];
		$profile_pic_temp = $_FILES['profile_pic']['tmp_name'];

		// Move uploaded ID pictures to a directory on the server
		$upload_dir = 'uploads/'; // Adjust the directory path as needed
		move_uploaded_file($id_front_temp, $upload_dir . $id_front_file);
		move_uploaded_file($id_back_temp, $upload_dir . $id_back_file);
		move_uploaded_file($profile_pic_temp, $upload_dir . $id_back_file);

		// Insert user data into the database
		$data = "lastname = '$lastname' ";
		$data .= ", firstname = '$firstname' ";
		$data .= ", middlename = '$middlename' ";
		$data .= ", address = '$address' ";
		$data .= ", contact_no = '$contact_no' ";
		$data .= ", email = '$email' ";
		$data .= ", password = '$password' ";
		$data .= ", tax_id = '$tax_id' ";
		$data .= ", id_front_path = '$upload_dir$id_front_file' "; // Store file paths in the database
		$data .= ", id_back_path = '$upload_dir$id_back_file' "; // Store file paths in the database
		$data .= ", profile_pic_path = '$upload_dir$profile_pic_file' "; // Store file paths in the database

		if (empty($id)) {
			$save = $this->db->query("INSERT INTO members SET " . $data);
		} else {
			$save = $this->db->query("UPDATE members SET " . $data . " WHERE id=" . $id);
		}

		if ($save)
			return 1;
	}
	function save_member2()
	{
		extract($_POST);

		// Handle ID picture uploads
		$id_front_file = $_FILES['id_front']['name'];
		$id_front_temp = $_FILES['id_front']['tmp_name'];
		$id_back_file = $_FILES['id_back']['name'];
		$id_back_temp = $_FILES['id_back']['tmp_name'];
		$profile_pic_file = $_FILES['profile_pic']['name'];
		$profile_pic_temp = $_FILES['profile_pic']['tmp_name'];

		// Move uploaded ID pictures to a directory on the server
		$upload_dir = 'uploads/'; // Adjust the directory path as needed
		move_uploaded_file($id_front_temp, $upload_dir . $id_front_file);
		move_uploaded_file($id_back_temp, $upload_dir . $id_back_file);
		move_uploaded_file($profile_pic_temp, $upload_dir . $id_back_file);

		// Insert user data into the database
		$data = "lastname = '$lastname' ";
		$data .= ", firstname = '$firstname' ";
		$data .= ", middlename = '$middlename' ";
		$data .= ", address = '$address' ";
		$data .= ", contact_no = '$contact_no' ";
		$data .= ", email = '$email' ";
		$data .= ", password = '$password' ";
		$data .= ", tax_id = '$tax_id' ";
		$data .= ", id_front_path = '$upload_dir$id_front_file' "; // Store file paths in the database
		$data .= ", id_back_path = '$upload_dir$id_back_file' "; // Store file paths in the database
		$data .= ", profile_pic_path = '$upload_dir$profile_pic_file' "; // Store file paths in the database

		if (empty($id)) {
			$save = $this->db->query("INSERT INTO members SET " . $data);
		} else {
			$save = $this->db->query("UPDATE members SET " . $data . " WHERE id=" . $id);
		}

		if ($save)
			return 1;
	}
	function delete_member()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM members WHERE id = " . $id);
		if ($delete)
			return 1;
	}
	function approve_member()
	{
		extract($_POST);
		$update_query = "UPDATE members SET status = 1 WHERE id = $id";
		$update_result = $this->db->query($update_query);

		if ($update_result) {
			$random_number = mt_rand(100000, 999999);
			$account_number = 'SAV' . $random_number;
			$insert_account_query = "INSERT INTO savings_account (member_id, account_number) VALUES ('$id', '$account_number')";
			$insert_account_result = $this->db->query($insert_account_query);

			if ($insert_account_result) {
				return 1;
			}
			else {
				return 0;
			}
		} else {
			return 0; // Failed update
		}
	}

	public function get_investment_types()
    {
        // Fetch investment types from the database
        $investment_types_qry = $this->db->query("SELECT id, investment_name FROM investment_types");
        $investment_types = array();

        while ($row = $investment_types_qry->fetch_assoc()) {
            $investment_types[] = $row;
        }

        return $investment_types;
    }

	public function save_investment($data)
	{
		// Extract POST data
		extract($data);

		// Validate and sanitize input data as needed

		// Fetch investment type details including the min_amount
		$investment_type_qry = $this->db->query("SELECT min_amount FROM investment_types WHERE id = $investment_type_id");
		if ($investment_type_qry->num_rows > 0) {
			$investment_type = $investment_type_qry->fetch_assoc();
			$min_amount = $investment_type['min_amount'];

			// Check if amount is greater than or equal to min_amount
			if ($amount < $min_amount) {
				return "Error: Amount cannot be less than the minimum amount for this investment type.";
			}

			// Generate unique ref_no
			$ref_no = mt_rand(10000000, 99999999); // Example of generating an 8-digit random number
			$ref_exists = $this->db->query("SELECT * FROM investment_list WHERE ref_no = '$ref_no'");
			while ($ref_exists->num_rows > 0) {
				$ref_no = mt_rand(10000000, 99999999); // Regenerate if the generated ref_no already exists
				$ref_exists = $this->db->query("SELECT * FROM investment_list WHERE ref_no = '$ref_no'");
			}

			// Example of saving the new investment with the validated amount
			// Assuming you have proper validations and data sanitization in place
			$save = $this->db->query("INSERT INTO investment_list (member_id, ref_no, investment_type_id, amount) VALUES ('$member_id', '$ref_no', '$investment_type_id', '$amount')");

			if ($save) {
				return 1; // Success response
			} else {
				return "Error: Failed to save investment. " . $this->db->error;
			}
		} else {
			return "Error: Investment type details not found";
		}
	}

	
}
