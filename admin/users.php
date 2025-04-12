<?php
require_once 'php/db_connect.php';

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
	echo 'window.location.href = "../login.html";</script>';
}
else{
    $stmt2 = $db2->prepare("SELECT * FROM roles WHERE deleted = '0'");
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    $companies = $db2->query("SELECT * FROM companies WHERE deleted = '0'");
    $scales = $db->query("SELECT * FROM scales");
    $printers = $db->query("SELECT * FROM printers");
}
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">Members</h1>
			</div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
        <div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
                        <div class="row">
                            <div class="col-9"></div>
                            <div class="col-3">
                                <button type="button" class="btn btn-block bg-gradient-warning btn-sm" id="addMembers">Add Members</button>
                            </div>
                        </div>
                    </div>
					<div class="card-body">
						<table id="memberTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Username</th>
									<th>Name</th>
                                    <th>Company</th>
                                    <th>Indicator</th>
                                    <th>Printer</th>
									<th>Created <br>Date</th>
                                    <th>Activate <br>Date</th>
                                    <th>Expired <br>Date</th>
									<th>Actions</th>
								</tr>
							</thead>
						</table>
					</div><!-- /.card-body -->
				</div><!-- /.card -->
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</section><!-- /.content -->

<div class="modal fade" id="addModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form role="form" id="memberForm">
            <div class="modal-header">
              <h4 class="modal-title">Add Members</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="form-group">
    					<input type="hidden" class="form-control" id="id" name="id">
    				</div>
    				<div class="form-group">
    					<label for="username">Username *</label>
    					<input type="text" class="form-control" name="username" id="username" placeholder="Enter Username" required>
    				</div>
                    <div class="form-group">
    					<label for="username">Password *</label>
    					<input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required>
    				</div>
                    <div class="form-group">
    					<label for="name">Name *</label>
    					<input type="text" class="form-control" name="name" id="name" placeholder="Enter Full Name" required>
    				</div>
                    <div class="form-group">
						<label>Role *</label>
						<select class="form-control" id="userRole" name="userRole" required>
						    <option select="selected" value="">Please Select</option>
						    <?php while($row2 = $result2->fetch_assoc()){ ?>
    							<option value="<?= $row2['role_code'] ?>"><?= $row2['role_name'] ?></option>
							<?php } ?>
						</select>
					</div>
                    <div class="form-group">
						<label>Company *</label>
						<select class="form-control" id="customer" name="customer" required>
						    <option select="selected" value="">Please Select</option>
						    <?php while($rowCustomer2=mysqli_fetch_assoc($companies)){ ?>
    							<option value="<?= $rowCustomer2['id'] ?>"><?= $rowCustomer2['name'] ?></option>
							<?php } ?>
						</select>
					</div>
                    <div class="form-group">
                        <label>Activation Date *</label>
                        <div class='input-group date' id="activateDate" data-target-input="nearest">
                            <input type='text' class="form-control datetimepicker-input" data-target="#activateDate" id="activationDate" name="activateDate" required/>
                            <div class="input-group-append" data-target="#activateDate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Expired Date *</label>
                        <div class='input-group date' id="expiredDate" data-target-input="nearest">
                            <input type='text' class="form-control datetimepicker-input" data-target="#expiredDate" id="expiryDate" name="expiredDate" required/>
                            <div class="input-group-append" data-target="#expiredDate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
						<label>Indicators *</label>
						<select class="form-control" id="indicator" name="indicator" required>
						    <option select="selected" value="">Please Select</option>
						    <?php while($rowInd=mysqli_fetch_assoc($scales)){ ?>
    							<option value="<?= $rowInd['id'] ?>"><?= $rowInd['indicator_bluetooth'] ?></option>
							<?php } ?>
						</select>
					</div>
                    <div class="form-group">
						<label>Printers *</label>
						<select class="form-control" id="printer" name="printer" required>
						    <option select="selected" value="">Please Select</option>
						    <?php while($rowPrint=mysqli_fetch_assoc($printers)){ ?>
    							<option value="<?= $rowPrint['id'] ?>"><?= $rowPrint['printer_bluetooth'] ?></option>
							<?php } ?>
						</select>
					</div>
                    <div class="form-group">
						<label>Stamping *</label>
						<select class="form-control" id="stamping" name="stamping" required>
						    <option value="Y">Yes</option>
                            <option value="N">No</option>
						</select>
					</div>
                    <div class="form-group">
						<label>Stamping Weight</label>
                        <input type="text" class="form-control" name="stampWeight" id="stampWeight" placeholder="Enter Stamping WEight">
					</div>
    			</div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="submit" id="submitMember">Submit</button>
            </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
$(function () {
    let jsonData = "";
    const today = new Date();
    const tomorrow = new Date(today);
    const yesterday = new Date(today);
    tomorrow.setFullYear(tomorrow.getFullYear() + 1);
    yesterday.setDate(tomorrow.getDate() - 7);

    $("#memberTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'php/loadMembers.php'
        },
        'columns': [
            { data: 'username' },
            { data: 'name' },
            { data: 'company' },
            { data: 'indicator' },
            { data: 'printer' },
            { data: 'created_date' },
            { data: 'activated_date' },
            { data: 'expired_date' },
            { 
                data: 'id',
                render: function ( data, type, row ) {
                    return '<div class="row"><div class="col-3"><button type="button" id="edit'+data+'" onclick="edit('+data+')" class="btn btn-success btn-sm"><i class="fas fa-pen"></i></button></div><div class="col-3"><button type="button" id="deactivate'+data+'" onclick="deactivate('+data+')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></div></div>';
                }
            }
        ],
        "rowCallback": function( row, data, index ) {

            $('td', row).css('background-color', '#E6E6FA');
        },
    });

    $('#activateDate').datetimepicker({
        icons: { time: 'far fa-calendar' },
        format: 'DD/MM/YYYY',
        defaultDate: today
    });

    $('#expiredDate').datetimepicker({
        icons: { time: 'far fa-calendar' },
        format: 'DD/MM/YYYY',
        defaultDate: tomorrow
    });
    
    $.validator.setDefaults({
        submitHandler: function () {
            $('#spinnerLoading').show();
            $.post('php/users.php', $('#memberForm').serialize(), function(data){
                var obj = JSON.parse(data); 
                
                if(obj.status === 'success'){
                    $('#addModal').modal('hide');
                    toastr["success"](obj.message, "Success:");
                    $('#memberTable').DataTable().ajax.reload();
                    $('#spinnerLoading').hide();
                }
                else if(obj.status === 'failed'){
                    toastr["error"](obj.message, "Failed:");
                    $('#spinnerLoading').hide();
                }
                else{
                    toastr["error"]("Something wrong when edit", "Failed:");
                    $('#spinnerLoading').hide();
                }
            });
        }
    });

    $('#addMembers').on('click', function(){
        $('#addModal').find('#id').val("");
        $('#addModal').find('#username').val("");
        $('#addModal').find('#password').val("");
        $('#addModal').find('#name').val("");
        $('#addModal').find('#userRole').val("");
        $('#addModal').find('#customer').val("");
        $('#addModal').find('#indicator').val("");
        $('#addModal').find('#printer').val("");
        $('#addModal').find('#stamping').val("N");
        $('#addModal').find('#stampWeight').val("");
        $('#addModal').find('#activationDate').val(formatDate2(today));
        $('#addModal').find('#expiryDate').val(formatDate2(tomorrow));
        $('#addModal').modal('show');
        
        $('#memberForm').validate({
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

    document.getElementById('fileInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const reader = new FileReader();

        reader.onload = function (e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });

            const sheetName = workbook.SheetNames[0];
            const sheet = workbook.Sheets[sheetName];
            jsonData = XLSX.utils.sheet_to_json(sheet);
            console.log(jsonData);
        };
        reader.readAsArrayBuffer(file);
    });

    $('#importExcelbtn').on('click', function(){
        jsonData.forEach(function(row) {
            $.ajax({
                url: 'php/importExcelMember.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(row),
                success: function(response) {
                    debugger;
                    var obj = JSON.parse(response); 
                    
                    if(obj.status === 'success'){
                        $('#addModal').modal('hide');
                        toastr["success"](obj.message, "Success:");
                        $('#memberTable').DataTable().ajax.reload();
                        $('#spinnerLoading').hide();
                    }
                    else if(obj.status === 'failed'){
                        toastr["error"](obj.message, "Failed:");
                        $('#spinnerLoading').hide();
                    }
                    else{
                        toastr["error"]("Something wrong when import", "Failed:");
                        $('#spinnerLoading').hide();
                    }
                },
                error: function(error) {
                    toastr["error"](obj.message, "Failed:");
                    $('#spinnerLoading').hide();
                }
            })
        });
    });
});

function edit(id){
    $('#spinnerLoading').show();
    $.post('php/getUser.php', {userID: id}, function(data){
        var obj = JSON.parse(data);
        
        if(obj.status === 'success'){
            $('#addModal').find('#id').val(obj.message.id);
            $('#addModal').find('#username').val(obj.message.username);
            $('#addModal').find('#password').val(obj.message.username);
            $('#addModal').find('#name').val(obj.message.name);
            $('#addModal').find('#userRole').val(obj.message.role_code);
            $('#addModal').find('#customer').val(obj.message.customer);
            $('#addModal').find('#indicator').val(obj.message.indicator);
            $('#addModal').find('#printer').val(obj.message.printer);
            $('#addModal').find('#stamping').val(obj.message.include_stamping);
            $('#addModal').find('#stampWeight').val(obj.message.stamping_weight);
            $('#addModal').find('#activationDate').val(formatDate3(obj.message.activation_date));
            $('#addModal').find('#expiryDate').val(formatDate3(obj.message.expired_datetime));
            $('#addModal').modal('show');
            
            $('#memberForm').validate({
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
        }
        else if(obj.status === 'failed'){
            toastr["error"](obj.message, "Failed:");
        }
        else{
            toastr["error"]("Something wrong when activate", "Failed:");
        }
        $('#spinnerLoading').hide();
    });
}

function deactivate(id){
    if (confirm('Are you sure you want to delete this items?')) {
        $('#spinnerLoading').show();
        $.post('php/deleteUser.php', {userID: id}, function(data){
            var obj = JSON.parse(data);
            
            if(obj.status === 'success'){
                toastr["success"](obj.message, "Success:");
                $('#memberTable').DataTable().ajax.reload();
                $('#spinnerLoading').hide();
            }
            else if(obj.status === 'failed'){
                toastr["error"](obj.message, "Failed:");
                $('#spinnerLoading').hide();
            }
            else{
                toastr["error"]("Something wrong when activate", "Failed:");
                $('#spinnerLoading').hide();
            }
        });
    }
}
</script>