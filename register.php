<?php
require_once 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
}

// get posted data
$data = json_decode(file_get_contents("php://input", true));
$result = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$result = msg(0,404,'Failure: Page Not Found!');

	$sql = "INSERT INTO user(username, password) VALUES('" . mysqli_real_escape_string($dbConn, $data->username) . "', '" . mysqli_real_escape_string($dbConn, $data->password) . "')";
	
	$result = dbQuery($sql);

		$username = trim($data->username);
		$password = trim($data->password);
    
    	$strings = array($username);
    	foreach ($strings as $testcase) {
    	if (ctype_alpha($testcase)) {
        	echo json_encode(msg(1,200, 'The string $testcase consists of all letters.\n'));
    	}else{
    		echo json_encode(msg(1,203, 'Only characters allowed in username.\n'));
    	}
		if(strlen($password) < 6){
	    	echo json_encode(msg(0,201,'Failure: password should of length 6!'));
		}
			elseif(!preg_match("#[0-9]+#",$password)) {
				echo json_encode(msg(0,202,'Your Password Must Contain At Least 1 Number!'));
        	}
        		elseif(!preg_match("#[A-Za-z]+#",$password)) {
        			echo json_encode(msg(0,202,'our Password Must Contain 1 Letter!'));
        		}
	if($result) {
		echo json_encode(msg(1,200,'You have registered successfully'));
	} else {
		echo json_encode(array('error' => 'Something went wrong, please contact administrator'));
	}
}



}

//End of file