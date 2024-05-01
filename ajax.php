<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();

if ($action == 'login') {
	$login = $crud->login();
	if ($login)
		echo $login;
}
if ($action == 'login2') {
	$login = $crud->login2();
	if ($login)
		echo $login;
}
if ($action == 'logout') {
	$logout = $crud->logout();
	if ($logout)
		echo $logout;
}
if ($action == 'logout2') {
	$logout = $crud->logout2();
	if ($logout)
		echo $logout;
}
if ($action == 'save_user') {
	$save = $crud->save_user();
	if ($save)
		echo $save;
}
if ($action == 'delete_user') {
	$save = $crud->delete_user();
	if ($save)
		echo $save;
}
if ($action == 'signup') {
	$save = $crud->signup();
	if ($save)
		echo $save;
}
if ($action == 'save_news') {
	$save = $crud->save_news();
	if ($save)
		echo $save;
}
if ($action == 'delete_news') {
	$save = $crud->delete_news();
	if ($save)
		echo $save;
}
if ($action == "save_settings") {
	$save = $crud->save_settings();
	if ($save)
		echo $save;
}
if ($action == "save_loan_type") {
	$save = $crud->save_loan_type();
	if ($save)
		echo $save;
}
if ($action == "delete_loan_type") {
	$save = $crud->delete_loan_type();
	if ($save)
		echo $save;
}
// if($action == "save_plan"){
// 	$save = $crud->save_plan();
// 	if($save)
// 		echo $save;
// }
// if($action == "delete_plan"){
// 	$save = $crud->delete_plan();
// 	if($save)
// 		echo $save;
// }
// if($action == "save_borrower"){
// 	$save = $crud->save_borrower();
// 	if($save)
// 		echo $save;
// }
// if($action == "delete_borrower"){
// 	$save = $crud->delete_borrower();
// 	if($save)
// 		echo $save;
// }
if ($action == "fetch_member_details") {
	$fetch = $crud->fetch_member_details();
	if ($fetch) {
		echo json_encode($fetch);
	} else {
		echo json_encode(array());
	}
}
if ($action == "save_loan") {
	$save = $crud->save_loan();
	if ($save)
		echo $save;
}
if (isset($action) && $action == 'get_loan_type_months') {
	if (isset($_POST['type_name'])) {
		include 'db_connect.php';

		// Sanitize input
		$type_name = $_POST['type_name'];

		// Query the database to fetch the number of months for the specified loan type name
		$stmt = $conn->prepare("SELECT months FROM loan_types WHERE id = ?");
		$stmt->bind_param("i", $type_name);
		$stmt->execute();
		$stmt->store_result();

		// Check if any rows were returned
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($months);
			$stmt->fetch();
			$stmt->close();

			// Return the number of months as the response
			echo $months;
		} else {
			// No rows found for the specified loan type name
			echo '0';
		}
	} else {
		// Handle missing type_name parameter
		echo '0';
	}
}
if ($action == "delete_loan") {
	$save = $crud->delete_loan();
	if ($save)
		echo $save;
}

if ($action == "save_payment") {
	$save = $crud->save_payment();
	if ($save)
		echo $save;
}
if ($action == "delete_payment") {
	$save = $crud->delete_payment();
	if ($save)
		echo $save;
}

if ($action == "save_member") {
	$save = $crud->save_member();
	if ($save)
		echo $save;
}

if ($action == "delete_member") {
	$save = $crud->delete_member();
	if ($save)
		echo $save;
}

if ($action == "approve_member") {
	$save = $crud->approve_member();
	if ($save)
		echo $save;
}
