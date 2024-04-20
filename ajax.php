<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();

if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == 'save_news'){
	$save = $crud->save_news();
	if($save)
		echo $save;
}
if($action == 'delete_news'){
	$save = $crud->delete_news();
	if($save)
		echo $save;
}
if($action == "save_settings"){
	$save = $crud->save_settings();
	if($save)
		echo $save;
}
if($action == "save_loan_type"){
	$save = $crud->save_loan_type();
	if($save)
		echo $save;
}
if($action == "delete_loan_type"){
	$save = $crud->delete_loan_type();
	if($save)
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
if($action == "save_loan"){
	$save = $crud->save_loan();
	if($save)
		echo $save;
}
if ($_GET['action'] == 'get_loan_types') {
	include 'db_connect.php';
	$loan_types_query = $conn->query("SELECT * FROM loan_types"); 
	$loan_types = $loan_types_query->fetch_all(MYSQLI_ASSOC);
	echo json_encode($loan_types);
	exit;
}
if($action == "delete_loan"){
	$save = $crud->delete_loan();
	if($save)
		echo $save;
}

if($action == "save_payment"){
	$save = $crud->save_payment();
	if($save)
		echo $save;
}
if($action == "delete_payment"){
	$save = $crud->delete_payment();
	if($save)
		echo $save;
}

if($action == "save_member"){
	$save = $crud->save_member();
	if($save)
		echo $save;
}

if($action == "delete_member"){
	$save = $crud->delete_member();
	if($save)
		echo $save;
}

if($action == "approve_member"){
	$save = $crud->approve_member();
	if($save)
		echo $save;
}
