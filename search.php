<?php 
	include 'functions.php';
	$result = searchUniversity($_POST);
	//p($result);
?>

<?php 
if(!empty($result)){
	$universityList = '';
	foreach ($result as $res) {
		
		$image = isset($res['u_image'])? htmlspecialchars($res['u_image']):'';
		$universityName = isset($res['u_name'])?$res['u_name']:'';
		$address = isset($res['u_address'])?$res['u_address']:'';
		$email = isset($res['u_email'])?$res['u_email']:'';
		$contact = isset($res['u_contact_no'])?$res['u_contact_no']:'';
		$city_name = isset($res['city_name'])?$res['city_name']:'';
		$state_name = isset($res['state_name'])?$res['state_name']:'';
		$u_course_duration = isset($res['u_course_duration'])?$res['u_course_duration']:'';
		$u_student_type = isset($res['u_student_type'])?$res['u_student_type']:'';
		$c_name = isset($res['c_name'])?$res['c_name']:'';
		
		$universityList.= '<div class="row">
			<div class="col-md-4 col-sm-4">
			  <img alt="" src="admin/assets/images/'.$image.'" width="350" height="200">
			</div>
			<div class="col-md-8 col-sm-8">
			  <h2><a href="javascript:;" style="text-decoration:none;">'.$universityName.'</a></h2><br>
			  <div class="row">
			  <div class="col-md-6">
				<p><strong>Address : </strong> '.$address.','.$city_name.','.$state_name.'</p>
				<p><strong>Email : </strong> '.$email.'</p>
				<p><strong>Contact : </strong> '.$contact.'</p>
			  </div>
			  <div class="col-md-6">
				<p><strong> Course : </strong> '.$c_name.'</p>
				<p><strong> Student Course Type : </strong> '.$u_student_type.'</p>
				<p><strong> Course Duration : </strong> '.$u_course_duration.'</p>
			  </div>
			  </div>
			</div>
		</div>
		<hr class="blog-post-sep">';
	}
	echo json_encode($universityList);
}else{

	$universityList = '<h2><a href="javascript:;" style="text-decoration:none;">No University Found For Your Search Result... </a></h2>';
	echo json_encode($universityList);
}
?>
