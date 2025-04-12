<?php
require_once 'php/languageSetting.php';

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "login.html";</script>';
}
else{
    $id = $_SESSION['userID'];
    $_SESSION['page']='myprofile';
    $stmt = $db->prepare("SELECT * from users where id = ?");
	$stmt->bind_param('s', $id);
	$stmt->execute();
	$result = $stmt->get_result();
    $fullName = '';
    $userName = '';
    $language = $_SESSION['language'];
	
	if(($row = $result->fetch_assoc()) !== null){
        $fullName = $row['name'];
        $userName = $row['username'];
        $language = $row['languages'];
    }
}
?>

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark"><?=$languageArray['profile_code'][$language] ?></h1>
			</div>
		</div>
	</div>
</section>

<section class="content" style="min-height:700px;">
	<div class="card">
		<form role="form" id="profileForm" novalidate="novalidate">
			<div class="card-body">
				<div class="form-group">
					<label for="name"><?=$languageArray['name_code'][$language] ?> *</label>
					<input type="text" class="form-control" id="userName" name="userName" value="<?=$fullName ?>" placeholder="Enter Full Name" required="">
				</div>
				<div class="form-group">
					<label for="name"><?=$languageArray['username_code'][$language] ?> *</label>
					<input type="text" class="form-control" id="userEmail" name="userEmail" value="<?=$userName ?>" placeholder="Enter Username" readonly="">
				</div>
                <div class="form-group">
                    <label><?=$languageArray['language_code'][$language] ?> *</label>
                    <select class="form-control" style="width: 100%;" id="language" name="language" required>
                        <option value="en" <?= ($language == 'en') ? 'selected' : '' ?>>English</option>
                        <option value="zh" <?= ($language == 'zh') ? 'selected' : '' ?>>Chinese</option>
                        <option value="my" <?= ($language == 'my') ? 'selected' : '' ?>>Bahasa Malaysia</option>
                        <option value="ne" <?= ($language == 'ne') ? 'selected' : '' ?>>नेपाली</option>
                    </select>
                </div>
			</div>
			
			<div class="card-footer">
				<button class="btn btn-success" id="saveProfile"><i class="fas fa-save"></i> <?=$languageArray['save_code'][$language] ?></button>
			</div>
		</form>
	</div>
</section>

<script>
$(function () {
    $.validator.setDefaults({
        submitHandler: function () {
            $('#spinnerLoading').show();
            $.post('php/updateProfile.php', $('#profileForm').serialize(), function(data){
                var obj = JSON.parse(data); 
                
                if(obj.status === 'success'){
                    toastr["success"](obj.message, "Success:");
                    
                    $.get('myprofile.php', function(data) {
                        $('#mainContents').html(data);
                        $('#spinnerLoading').hide();
                    });
        		}
        		else if(obj.status === 'failed'){
        		    toastr["error"](obj.message, "Failed:");
                    $('#spinnerLoading').hide();
                }
        		else{
        			toastr["error"]("Failed to update profile", "Failed:");
                    $('#spinnerLoading').hide();
        		}
            });
        }
    });
    
    $('#profileForm').validate({
        rules: {
            text: {
                required: true
            }
        },
        messages: {
            text: {
                required: "Please fill in this field"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>