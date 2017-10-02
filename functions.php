<?php
include 'common.php';
session_start();

function insert($tableName, $data) {
    global $conn;
    $fields = '';
    $values = '';
    end($data);
    $lastElement = key($data);
    foreach ($data as $key => $value) {
        if ($lastElement == $key) {
            $fields.='`' . $key . '`';
            $values.="'$value'";
        } else {
            $fields.='`' . $key . '`' . ',';
            $values.="'$value',";
        }
    }
    $checkFields = checkTableFields($tableName, $data);
    if (isset($checkFields['status']) && $checkFields['status'] == '0') {
        $insert = "INSERT INTO `$tableName` ($fields) VALUES($values)";
        mysqli_query($conn, $insert);
        return ['status' => 0, 'message' => 'record inserted', 'ID' => mysqli_insert_id($conn)];
    } else {
        return $checkFields;
    }
}

function update($tableName, $data, $params) {
    global $conn;
    $updateString = '';
    $condition = '';
    end($data);
    $lastElement = key($data);
    end($params);
    $lastParams = key($params);
    foreach ($data as $key => $value) {
        if ($lastElement == $key) {
            $updateString.='`' . "$key" . '`' . " = '$value'";
        } else {
            $updateString.='`' . "$key" . '`' . " = '$value' , ";
        }
    }
    foreach ($params as $key => $value) {
        if ($lastParams == $key) {
            $condition.='`' . "$key" . '`' . " = '$value'";
        } else {
            $condition.='`' . "$key" . '`' . " = '$value' AND ";
        }
    }

    $checkFields = checkTableFields($tableName, $data);
    if (isset($checkFields['status']) && $checkFields['status'] == '0') {
        $select = "SELECT * FROM $tableName WHERE $condition";
        $selectQuery = mysqli_query($conn, $select);
        $row = mysqli_fetch_assoc($selectQuery);
        if (!empty($row)) {
            $update = "UPDATE $tableName SET $updateString WHERE $condition";
            mysqli_query($conn, $update);
            return['status' => 0, 'message' => 'record updated'];
        } else {
            return['status' => 1, 'message' => 'invalid params'];
        }
    } else {
        return $checkFields;
    }
}

function queryOne($tableName, $params) {
    global $conn;
    //p($params);
    $checkFields = checkTableFields($tableName, $params);
    if (isset($checkFields['status']) && $checkFields['status'] == '0') {
        end($params);
        $lastParams = key($params);
        $condition = '';
        foreach ($params as $key => $value) {
            if ($lastParams == $key) {
                $condition.="$key = '$value'";
            } else {
                $condition.="$key = '$value' AND ";
            }
        }

        $select = "SELECT * FROM $tableName WHERE $condition";
        //p($select);
        $selectQuery = mysqli_query($conn, $select);
        $result = mysqli_fetch_assoc($selectQuery);
        return $result;
    } else {
        return $checkFields;
    }
}

function delete($tableName, $params) {
    global $conn;
    $checkFields = checkTableFields($tableName, $params);
    if (isset($checkFields['status']) && $checkFields['status'] == '0') {
        end($params);
        $lastParams = key($params);
        $condition = '';
        foreach ($params as $key => $value) {
            if ($lastParams == $key) {
                $condition.="$key = '$value'";
            } else {
                $condition.="$key = '$value' AND ";
            }
        }

        $select = "DELETE FROM $tableName WHERE $condition";
        $selectQuery = mysqli_query($conn, $select);
        return ['status' => 1];
    } else {
        return $checkFields;
    }
}

function queryAll($tableName, $params = '') {
    global $conn;
    $lastParams = '';
    $condition = '';
    if (!empty($params)) {
        $checkFields = checkTableFields($tableName, $params);
        end($params);
        $lastParams = key($params);
        foreach ($params as $key => $value) {
            if ($lastParams == $key) {
                $condition.="AND $key = '$value'";
            } else {
                $condition.="AND $key = '$value'";
            }
        }
    } else {
        $checkFields['status'] = '0';
    }
    $data1 = [];
    if (isset($checkFields['status']) && $checkFields['status'] == '0') {
        $select = "SELECT * FROM $tableName WHERE 1=1 $condition";
        $selectQuery = mysqli_query($conn, $select);
        $i = 0;
        while ($row = mysqli_fetch_assoc($selectQuery)) {
            $data1[$i] = $row;
            $i++;
        }
        return $data1;
    }
}

function checkTableFields($tableName, $data) {
    global $conn;

    $getColumnString = "SHOW COLUMNS FROM `" . $tableName . "`";
    $queryColumn = mysqli_query($conn, $getColumnString);
    $fields = [];
    $i = 0;
    while ($rowColumn = mysqli_fetch_assoc($queryColumn)) {
        $fields[$i] = $rowColumn;
        $i++;
    }

    $exactFields = [];
    foreach ($fields as $key => $value) {
        if (!in_array($value['Field'], $exactFields)) {
            $exactFields[] = $value['Field'];
        }
    }
    $fieldFlag = 0;
    foreach ($data as $key => $value) {
        if (in_array($key, $exactFields)) {
            $fieldFlag = 1;
        } else {
            return ['status' => 1, 'message' => 'Unknown filed ' . $key];
        }
    }
    if ($fieldFlag) {
        return ['status' => 0];
    }
}

function setFlash($data) {
    $flashArray = array();
    if (isset($data['status']) && $data['status'] == '0') {
        $flashArray['type'] = 'success';
        $flashArray['class'] = 'alert-success';
        $flashArray['title'] = 'Success';
        $message = isset($data['message']) ? $data['message'] : '';
        $msg = isset($data['msg']) ? $data['msg'] : '';
        $rmessage = $msg;
        if ($rmessage == '') {
            $rmessage = $message;
        }
        $flashArray['message'] = $rmessage;
    } else if (isset($data['status']) && $data['status'] != '0') {
        $flashArray['type'] = 'error';
        $flashArray['class'] = 'alert-danger';
        $flashArray['title'] = 'Error';
        $message = isset($data['message']) ? $data['message'] : '';
        $msg = isset($data['msg']) ? $data['msg'] : '';
        $rmessage = $msg;
        if ($rmessage == '') {
            $rmessage = $message;
        }
        $flashArray['message'] = $rmessage;
    }
    $_SESSION['flashMessage'] = $flashArray;
}

function showFlash() {
    ?>
    <?php if (isset($_SESSION['flashMessage']['message']) && $_SESSION['flashMessage']['message'] != '') { ?>
        <div class="alert <?php echo $_SESSION['flashMessage']['class']; ?>">
            <button class="close" data-close="alert"></button>
            <h4><?php echo $_SESSION['flashMessage']['title']; ?></h4>
            <span><?php echo $_SESSION['flashMessage']['message']; ?></span>
        </div>
        <div class="clear"></div>
        <?php
        unset($_SESSION['flashMessage']);
    }
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function setTemplate($file) {
    $headers = '';
    $headers = file_get_contents('template/eheader.php');
    $headers.= file_get_contents('template/' . $file);
    $headers.= file_get_contents('template/efooter.php');
    return $headers;
}

function sendMailCore($email, $subject, $message, $headers) {
    $sendMail = mail($email, $subject, $message, $headers);
    if ($sendMail) {
        return TRUE;
    } else {
        return FALSE;
    }
}
function loginAdmin($data) {
    global $conn;
    if (isset($data['username']) && $data['username'] != '') {
        $username = $data['username'];
    } else {
        return ['status' => 1, 'message' => 'Please enter username'];
    }
    if (isset($data['password']) && $data['password'] != '') {
        $password = $data['password'];
    } else {
        return ['status' => 1, 'message' => 'Please enter password'];
    }
    $params = [
        'email' => $username
    ];
    $userString = queryOne('admin', $params);
    $row = $userString;

    if (empty($row)) {
        return ['status' => 1, 'message' => 'Enter username is not registered'];
    } else {
        if ($row['password'] == md5($data['password'])) {
            if (session_id() == '') {
                session_start();
            }
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['firstname'] = $row['firstname'];
            $_SESSION['admin_profilepic'] = "images/" . $row['profilepic'];
            return ['status' => 0, 'message' => 'Login success'];
        } else {
            return ['status' => 1, 'message' => 'Please enter valid password'];
        }
    }
}
function loginUser($data) {
    global $conn;
	
    if (isset($data['username']) && $data['username'] != '') {
        $username = $data['username'];
    } else {
        return ['status' => 1, 'message' => 'Please enter emailid'];
    }
    if (isset($data['password']) && $data['password'] != '') {
        $password = $data['password'];
    } else {
        return ['status' => 1, 'message' => 'Please enter password'];
    }
    $params = [
        'user_email' => $username
    ];
    $userString = queryOne('users', $params);
    $row = $userString;

    if (empty($row)) {
        return ['status' => 1, 'message' => 'Enter email is not registered'];
    } else {
        if ($row['user_password'] == md5($data['password'])) {
            if (session_id() == '') {
                session_start();
            }
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['user_username'];
          
            return ['status' => 0, 'message' => 'Login success'];
        } else {
            return ['status' => 1, 'message' => 'Please enter valid password'];
        }
    }
}
function loginUniversity($data) {
    global $conn;
	
    if (isset($data['username']) && $data['username'] != '') {
        $username = $data['username'];
    } else {
        return ['status' => 1, 'message' => 'Please enter emailid'];
    }
    if (isset($data['password']) && $data['password'] != '') {
        $password = $data['password'];
    } else {
        return ['status' => 1, 'message' => 'Please enter password'];
    }
    $params = [
        'ui_email' => $username,
		'ui_status' => 1
    ];
    $userString = queryOne('university_info', $params);
    $row = $userString;

    if (empty($row)) {
        return ['status' => 1, 'message' => 'Please contact with admin for login'];
    } else {
        if ($row['ui_password'] == md5($data['password'])) {
            if (session_id() == '') {
                session_start();
            }
            $_SESSION['uni_id'] = $row['ui_id'];
            $_SESSION['uniname'] = $row['ui_username'];
          
            return ['status' => 0, 'message' => 'Login success'];
        } else {
            return ['status' => 1, 'message' => 'Please enter valid password'];
        }
    }
}
function getByAdminId($id) {
    global $conn;
    $params = [
        'id' => $id
    ];
    $result = queryOne('admin', $params);
    return $result;
}

function updateAdminProfile($data) {
    global $conn;
    $id = isset($data['id']) ? $data['id'] : 0;

    if (isset($data['username']) && $data['username'] != '') {
        $update_data['username'] = $data['username'];
    } else {
        return ['status' => 1, 'message' => 'Admin name is required'];
    }

    if (isset($data['firstname']) && $data['firstname'] != '') {
        $update_data['firstname'] = $data['firstname'];
    } else {
        return ['status' => 1, 'message' => 'Admin firstname is required'];
    }

    if (isset($data['lastname']) && $data['lastname'] != '') {
        $update_data['lastname'] = $data['lastname'];
    } else {
        return ['status' => 1, 'message' => 'Admin lastname is required'];
    }

    if (isset($data['email']) && $data['email'] != '') {
        $update_data['email'] = $data['email'];
    } else {
        return ['status' => 1, 'message' => 'Admin email is required'];
    }

    $params = [
        'id' => $id
    ];
    $update = update('admin', $update_data, $params);
    $_SESSION['admin_name'] = $admin_username;

    return ['status' => 0, 'message' => 'Profile Updated Success'];
}

function changeAvatarAdmin($data) {
    $profilepic = '';
    if (isset($_FILES['profilepic']['name']) && $_FILES['profilepic']['name'] != '') {
        if (isset($data['id']) && !empty($data['id'])) {
            $params = [
                'id' => $data['id']
            ];
            $getImage = queryOne('admin', $params);
            $oldImage = $getImage['profilepic'];
            $imagePath = "assets/images/" . $oldImage;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $file_type = $_FILES['profilepic']['type'];
        $pieces = explode("/", $file_type);
        $data['profilepic'] = time() . strtotime(date('Y-m-d')) . rand(0, 9) . '.' . $pieces['1'];
        $targetfolder = "assets/images/";
        $targetfolder = $targetfolder . basename($data['profilepic']);
        if (@move_uploaded_file($_FILES['profilepic']['tmp_name'], $targetfolder)) {
            basename($_FILES['profilepic']['name']) . " is uploaded";
        }
    }

    if (isset($data['profilepic']) && !empty($data['profilepic'])) {
        $profilepic = $data['profilepic'];
        $_SESSION['admin_logo'] = "assets/images/" . $data['profilepic'];
    } else {
        if (!isset($data['id'])) {
            return ['status' => 1, 'message' => 'Logo Image Required'];
        }
    }
    if ($profilepic != '') {
        $admin_id = isset($data['id']) ? $data['id'] : '';
        $params = [
            'id' => $admin_id
        ];
        $update['profilepic'] = $profilepic;
        update('admin', $update, $params);
        return ['status' => 0, 'message' => 'Profile Pic Changed Success'];
    }
}

function validatePassword($password) {
    global $conn;
    $password = md5($password);
    $selectUser = "SELECT * FROM admin WHERE password = '$password'";
    $query = mysqli_query($conn, $selectUser);
    $row = mysqli_fetch_assoc($query);
    if (!empty($row)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function changePasswordAdmin($data) {
    if (isset($data['old_password']) && $data['old_password'] != '') {
        $validate = validatePassword($data['old_password']);
    } else {
        return ['status' => 1, 'message' => 'Please Enter Old Password'];
    }
    if (isset($data['new_password']) && $data['new_password'] != '') {
        if ($data['new_password'] == $data['renew_password']) {
            $new_password = md5($data['new_password']);
        } else {
            return ['status' => 1, 'message' => 'New Password & Re-type Password Doesnot match'];
        }
    } else {
        return ['status' => 1, 'message' => 'Please Enter New Password'];
    }

    if ($validate) {
        $admin_id = isset($data['id']) ? $data['id'] : '';
        $params = [
            'id' => $admin_id
        ];
        $update['password'] = $new_password;
        update('admin', $update, $params);
        return ['status' => 0, 'message' => 'Password Change Success'];
    } else {
        return ['status' => 1, 'message' => 'Please Enter correct Old Password'];
    }
}


function forgotPassword($data,$id) {
    if (isset($data['email']) && $data['email'] != '') {
        $email = isset($data['email']) ? $data['email'] : '';
		$result=array();
		$result1=array();
		if($id==0){
			$params1 = ['user_email' => $email];
        $result1 = queryOne('users', $params1);
			}else{
				$params = ['ui_email' => $email];
        $result = queryOne('university_info', $params);
				}
       
        if (!empty($result) || !empty($result1)) {
			
            $string = generateRandomString(6);
			if(!empty($result)){
				$update['ui_password'] = md5($string);
            update('university_info', $update, $params);
			$subject = 'Forgot Password';
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <Psychological>' . "\r\n";

            $body = setTemplate('forgotpassword.php');
           $body = str_replace('_EMAIL_', $result['ui_email'], $body);
            $body = str_replace('_USERNAME_', $result['ui_username'], $body);
			$body = str_replace('_PASSWORD_', $string, $body);
				}
				if(!empty($result1)){
					
					$update['user_password'] = md5($string);
			
            update('users', $update, $params1);
			$subject = 'Forgot Password';
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <Psychological>' . "\r\n";

            $body = setTemplate('forgotpassword.php');
           $body = str_replace('_EMAIL_', $result1['user_email'], $body);
            $body = str_replace('_USERNAME_', $result1['user_username'], $body);
			$body = str_replace('_PASSWORD_', $string, $body);
					}
           
            sendMailCore($email, $subject, $body, $headers);
            return ['status' => 0, 'message' => 'New Password send to your email.'];
        } else {
            return ['status' => 1, 'message' => 'Please Enter the Valid Email Address'];
        }
    } else {
        return ['status' => 1, 'message' => 'please enter email address'];
    }
}

function resetPassword($data) {
    if (isset($data['newpassword']) && isset($data['confpassword']) && $data['newpassword'] == $data['confpassword']) {
        if (isset($data['code']) && $data['code'] != '') {
            $params = ['forgotpass_ref' => $data['code']];
            $result = queryOne('admin', $params);
            if (!empty($result)) {
                $update['password'] = md5($data['newpassword']);
                update('admin', $update, $params);
                return ['status' => 0, 'message' => 'Password is Reset Successfully'];
            } else {
                return ['status' => 1, 'message' => 'Your Reference Code Not Valid'];
            }
        }
    } else {
        return ['status' => 1, 'message' => 'Your New Password & ConfirmPassword Doesnot Match'];
    }
}

function checkCourse($course){
	$params = ['c_name'=>$course];
	$result = queryOne('course',$params);
	if (!empty($result)) {
        return FALSE;
    } else {
        return TRUE;
    }
}

function getCourseById($id){
	$params = ['c_id'=>$id];
	$result = queryOne('course',$params);
	return $result;
}

function userRegister($data) {

    global $conn;
	$insert_data = [];
	
	$checkEmail=queryAll('users');
	foreach($checkEmail as $email){
		if($email['user_email']==$data['emailsignup']){
			return ['status' => 1, 'message' => 'Email is already Exit'];
			}
		}
	
    if (isset($data['usernamesignup']) && !empty($data['usernamesignup'])) {
		$insert_data['user_username'] = $data['usernamesignup'];
    } else {
        return ['status' => 1, 'message' => 'Course Required'];
    }
    if (isset($data['emailsignup']) && !empty($data['emailsignup'])) {
		$insert_data['user_email'] = $data['emailsignup'];
    } else {
        return ['status' => 1, 'message' => 'Course Required'];
    }
	if (isset($data['passwordsignup']) && !empty($data['passwordsignup'])) {
		$insert_data['user_password'] = md5($data['passwordsignup']);
    } else {
        return ['status' => 1, 'message' => 'Course Required'];
    }
   

        $created = date('Y-m-d H:i:s');

        $insert_data['user_created'] = $created;
		 $result = insert('users', $insert_data);

        return ['status' => 0, 'message' => 'User Inserted successfully'];
    
}
function universityRegister($data) {

    global $conn;
	$insert_data = [];
	
	$checkEmail=queryAll('university_info');
	foreach($checkEmail as $email){
		if($email['ui_email']==$data['emailsignup']){
			return ['status' => 1, 'message' => 'Email is already Exit'];
			}
		}
	
    if (isset($data['usernamesignup']) && !empty($data['usernamesignup'])) {
		$insert_data['ui_username'] = $data['usernamesignup'];
    } else {
        return ['status' => 1, 'message' => 'username Required'];
    }
    if (isset($data['emailsignup']) && !empty($data['emailsignup'])) {
		$insert_data['ui_email'] = $data['emailsignup'];
    } else {
        return ['status' => 1, 'message' => 'email Required'];
    }
	if (isset($data['passwordsignup']) && !empty($data['passwordsignup'])) {
		$insert_data['ui_password'] = md5($data['passwordsignup']);
    } else {
        return ['status' => 1, 'message' => 'password Required'];
    }
   

        $created = date('Y-m-d H:i:s');

        $insert_data['ui__created'] = $created;
		 $result = insert('university_info', $insert_data);

        return ['status' => 0, 'message' => 'User Inserted successfully'];
    
}
function getAllcourseList(){
	$result = queryAll('course');
	return $result;
}

function addUniversity($data){
	
	$insert_data = [];
	if(isset($data['u_name']) && $data['u_name'] != ''){
		$insert_data['u_name'] = $data['u_name'];
	}else{
		return ['status'=>1,'message'=>"Please Enter the University Name"];
	}
	
	if(isset($data['u_address']) && $data['u_address'] != ''){
		$insert_data['u_address'] = $data['u_address'];
	}else{
		return ['status'=>1,'message'=>"Please Enter the University Address"];
	}
	
	if(isset($data['u_email']) && $data['u_email'] != ''){
		$insert_data['u_email'] = $data['u_email'];
	}else{
		return ['status'=>1,'message'=>"Please Enter the University Email"];
	}
	
	if(isset($data['u_website']) && $data['u_website'] != ''){
		$insert_data['u_website'] = $data['u_website'];
	}else{
		return ['status'=>1,'message'=>"Please Enter the University Website"];
	}
	
	if(isset($data['u_contact_no']) && $data['u_contact_no'] != ''){
		$insert_data['u_contact_no'] = $data['u_contact_no'];
	}else{
		return ['status'=>1,'message'=>"Please Enter the University Contact"];
	}
	if(isset($data['u_state_id']) && $data['u_state_id'] != ''){
		$insert_data['u_state_id'] = $data['u_state_id'];
	}else{
		return ['status'=>1,'message'=>"Please Select the State"];
	}
	if(isset($data['u_city_id']) && $data['u_city_id'] != ''){
		$insert_data['u_city_id'] = $data['u_city_id'];
	}else{
		return ['status'=>1,'message'=>"Please Select the City"];
	}
	if(isset($data['u_course_id']) && $data['u_course_id'] != ''){
		$insert_data['u_course_id'] = $data['u_course_id'];
	}else{
		return ['status'=>1,'message'=>"Please Select the Course"];
	}
	if(isset($data['u_course_duration']) && $data['u_course_duration'] != ''){
		$insert_data['u_course_duration'] = $data['u_course_duration'];
	}else{
		return ['status'=>1,'message'=>"Please Select the Course Duration"];
	}
	if(isset($data['u_student_type']) && $data['u_student_type'] != ''){
		$insert_data['u_student_type'] = $data['u_student_type'];
	}else{
		return ['status'=>1,'message'=>"Please Select the Course Type"];
	}
	if (isset($_FILES['u_image']['name']) && $_FILES['u_image']['name'] != '') {
        if (isset($data['u_id']) && !empty($data['u_id'])) {
            $params = [
                'u_id' => $data['u_id']
            ];
            $getImage = queryOne('university', $params);
            $oldImage = $getImage['u_image'];
            $imagePath = "assets/images/" . $oldImage;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $file_type = $_FILES['u_image']['type'];
        $pieces = explode("/", $file_type);
        $data['u_image'] = time() . strtotime(date('Y-m-d')) . rand(0, 9) . '.' . $pieces['1'];
        $targetfolder = "assets/images/";
        $targetfolder = $targetfolder . basename($data['u_image']);
        if (@move_uploaded_file($_FILES['u_image']['tmp_name'], $targetfolder)) {
            basename($_FILES['u_image']['name']) . " is uploaded";
        }
    }
	
	if (isset($data['u_image']) && !empty($data['u_image'])) {
        $insert_data['u_image'] = $data['u_image'];
    } else {
        if (!isset($data['u_id'])) {
            return ['status' => 1, 'message' => 'University Image Required'];
        }
    }
	
	if(isset($data['u_id']) && $data['u_id'] != ''){
		$params = ['u_id' => $data['u_id']];
		$insert_data['u_modified'] = date('Y-m-d H:i:s');
		update('university',$insert_data,$params);
		return ['status'=>0,'message'=>"University Data Updated Successfully"];
	}else{
		$insert_data['u_created'] = date('Y-m-d H:i:s');
		insert('university',$insert_data);
		return ['status'=>0,'message'=>"University Data Inserted Successfully"];
	}
	
}
function getByUniversityId($id){
	$params = ['u_id'=>$id];
	$result = queryOne('university',$params);
	return $result;
}
function getAllUniversity(){
	global $conn;
	$select = "Select * from university left join course on u_course_id = c_id";
	$query  = mysqli_query($conn,$select);
	$result = [];
	$i = 0;
	while($row = mysqli_fetch_assoc($query)){
		$state = queryOne('location',$params = ['location_id'=>$row['u_state_id']]);
		$row['state_name'] = $state['name'];
		$city = queryOne('location',$params = ['location_id'=>$row['u_city_id']]);
		$row['city_name'] = $city['name'];
		if($row['u_course_duration'] == '6'){
			$row['u_course_duration'] = "6 Months";
		}else if ($row['u_course_duration'] == '1'){
			$row['u_course_duration'] = "1 Year";
		}else if ($row['u_course_duration'] == '2'){
			$row['u_course_duration'] = "2 Year";
		}else if ($row['u_course_duration'] == '3'){
			$row['u_course_duration'] = "3 Year";
		}else if ($row['u_course_duration'] == '4'){
			$row['u_course_duration'] = "4 Year";
		}
		
		if($row['u_student_type'] == '0'){
			$row['u_student_type'] = "Internal";
		}else if($row['u_student_type'] == '1'){
			$row['u_student_type'] = "External";
		}else if($row['u_student_type'] == '2'){
			$row['u_student_type'] = "Both";
		}
		$result[$i] = $row;
		$i++;
	}
	
	return $result;
}
function getAllUserList(){
	global $conn;
	$select = "Select * from university_info";
	$query  = mysqli_query($conn,$select);
	$result = [];
	$i = 0;
	while($row = mysqli_fetch_assoc($query)){
	
		$result[$i] = $row;
		$i++;
	}
	
	return $result;
}
function getAllUniversityList(){
	global $conn;
	$select = "Select * from users";
	$query  = mysqli_query($conn,$select);
	$result = [];
	$i = 0;
	while($row = mysqli_fetch_assoc($query)){
	
		$result[$i] = $row;
		$i++;
	}
	
	return $result;
}
function searchUniversity($data){
	global $conn;
	$select = "SELECT * FROM university left join course on u_course_id = c_id WHERE u_state_id = '".$data['u_state_id']."' AND u_city_id = '".$data['u_city_id']."' AND u_course_id = '".$data['u_course_id']."' AND u_course_duration = '".$data['u_course_duration']."' AND u_student_type = '".$data['course_type']."'";
	$query = mysqli_query($conn,$select);
	$result = [];
	$i = 0;
	while($row = mysqli_fetch_assoc($query)){
		$state = queryOne('location',$params = ['location_id'=>$row['u_state_id']]);
		$row['state_name'] = $state['name'];
		$city = queryOne('location',$params = ['location_id'=>$row['u_city_id']]);
		$row['city_name'] = $city['name'];
		if($row['u_course_duration'] == '6'){
			$row['u_course_duration'] = "6 Months";
		}else if ($row['u_course_duration'] == '1'){
			$row['u_course_duration'] = "1 Year";
		}else if ($row['u_course_duration'] == '2'){
			$row['u_course_duration'] = "2 Year";
		}else if ($row['u_course_duration'] == '3'){
			$row['u_course_duration'] = "3 Year";
		}else if ($row['u_course_duration'] == '4'){
			$row['u_course_duration'] = "4 Year";
		}
		
		if($row['u_student_type'] == '0'){
			$row['u_student_type'] = "Internal";
		}else if($row['u_student_type'] == '1'){
			$row['u_student_type'] = "External";
		}else if($row['u_student_type'] == '2'){
			$row['u_student_type'] = "Both";
		}
		$result[$i] = $row;
		$i++;
	}
	return $result;
}    