<?php
$serial = "E1000";
$query = $this->db->query("SELECT * FROM tbl_employee order by Employee_ID desc limit 1");
$result = $query->row();
if (@$result->Employee_ID != null) {
	$serial = $result->Employee_ID;
}
$serial = explode("E", $serial);
if ($serial[1] >= 9) {
	$serial = $serial['1'];
	$autoserial = $serial + 1;
	$generateCode = "E" . $autoserial;
} else {
	$serial = $serial[1];
	$autoserial = $serial + 1;
	$generateCode = "E0" . $autoserial;
}
?>
<style>
	.add-button {
		padding: 2.5px;
		width: 100%;
		background-color: #298db4;
		display: block;
		text-align: center;
		color: white;
		cursor: pointer;
		border-radius: 3px;
	}

	.add-button:hover {
		background-color: #41add6;
		color: white;
	}
</style>
<div id="Edit_emloyee_form">
	<div class="row">
		<div class="col-md-6">
			<fieldset class="scheduler-border scheduler-search" style="height: 260px;">
				<legend class="scheduler-border">Job Information</legend>
				<div class="control-group">
					<div class="form-group">
						<label class="col-md-4 control-label" for="Product_id"> Employee ID </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-2">
							<input type="text" name="Employeer_id" id="Employeer_id" value="<?php echo $generateCode; ?>" class="form-control" readonly />
						</div>

						<label class="col-md-2 control-label" for="bio_id"> Bio ID: </label>
						<div class="col-md-2 no-padding-left">
							<input type="text" name="bio_id" id="bio_id" value="<?php echo set_value('bio_id'); ?>" autocomplete="off" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="em_name"> Employee Name </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-6">
							<input type="text" id="em_name" name="em_name" value="<?php echo set_value('em_name'); ?>" autocomplete="off" placeholder="Employee Name .." class="form-control" />
							<div id="em_name_" class="col-md-12"></div>
						</div>
						<label class="col-md-1 em_name" style="display:none;color: red;padding-left: 0px;margin-left: -8px;">required</label>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="em_Designation"> Designation </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-6" style="display: flex;align-items:center;">
							<div style="width: 88%;">
								<select class="chosen-select form-control" name="em_Designation" id="em_Designation">
									<option value=""> </option>
									<?php
									$query = $this->db->query("SELECT * FROM tbl_designation where status='a' order by Designation_Name asc");
									$row = $query->result();
									foreach ($row as $row) { ?>
										<option value="<?php echo $row->Designation_SlNo; ?>"><?php echo $row->Designation_Name; ?></option>
									<?php } ?>
								</select>
								<div id="em_Designation"></div>
							</div>
							<div style="width:12%;margin-left:2px;">
								<a href="<?= base_url('designation') ?>" title="Add New Designation" style="margin-top:-3px;" class="add-button" target="_blank"><i class="fa fa-plus" aria-hidden="true"></i></a>
							</div>
						</div>
						<label class="col-md-1 em_Designation" style="display:none;color: red;padding-left: 0px;margin-left: -8px;">required</label>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="em_Depertment"> Department </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-6" style="display: flex;align-items:center;">
							<div style="width: 88%;">
								<select class="chosen-select form-control" name="em_Depertment" id="em_Depertment">
									<option value=""> </option>
									<?php
									$dquery = $this->db->query("SELECT * FROM tbl_department order by Department_Name asc ");
									$drow = $dquery->result();
									foreach ($drow as $drow) { ?>
										<option value="<?php echo $drow->Department_SlNo; ?>"><?php echo $drow->Department_Name; ?></option>
									<?php } ?>
								</select>
								<div id="em_Depertment"></div>
							</div>
							<div style="width:12%;margin-left:2px;">
								<a href="<?= base_url('depertment') ?>" title="Add New Department" style="margin-top:-3px;" class="add-button" target="_blank"><i class="fa fa-plus" aria-hidden="true"></i></a>
							</div>
						</div>
						<label class="col-md-1 em_Depertment" style="display:none;color: red;padding-left: 0px;margin-left: -8px;">required</label>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="em_Joint_date">Joint Date</label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-6">
							<input class="form-control" style="margin-bottom:4px;border-radius: 5px !important;" name="em_Joint_date" id="em_Joint_date" type="date" data-date-format="yyyy-mm-dd" value="<?php echo date("Y-m-d") ?>" />
						</div>
						<label class="col-md-1 em_Joint_date" style="display:none;color: red;padding-left: 0px;margin-left: -8px;">required</label>
					</div>


					<div class="form-group">
						<label class="col-md-4 control-label" for="salary_range">Salary Range</label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-6">
							<input type="number" min="0" step="any" id="salary_range" name="salary_range" value="<?php echo set_value('salary_range'); ?>" placeholder="Salary Range .." class="form-control" />
							<div id="salary_range_" class="col-md-12"></div>
						</div>
						<label class="col-md-1 salary_range" style="display:none;color: red;padding-left: 0px;margin-left: -8px;">required</label>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="status"> Activation status</label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-6">
							<select class="form-control" name="status" id="status">
								<option value="a"> Active </option>
								<option value="p"> Deactive </option>
							</select>
						</div>
						<label class="col-md-1 status" style="display:none;color: red;padding-left: 0px;margin-left: -8px;">required</label>
					</div>
				</div>
			</fieldset>
		</div>



		<div class="col-md-6">
			<fieldset class="scheduler-border scheduler-search">
				<legend class="scheduler-border">Contact Information</legend>
				<div class="control-group">

					<div class="form-group">
						<label class="col-md-4 control-label" for="em_Present_address"> Present Address </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-6">
							<input type="text" id="em_Present_address" name="em_Present_address" placeholder="Present Address" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="em_Permanent_address"> Permanent Address </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-6">
							<input type="text" id="em_Permanent_address" name="em_Permanent_address" placeholder="Present Address" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="em_contact"> Contact No </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-6">
							<input type="text" id="em_contact" name="em_contact" value="<?php echo set_value('em_contact'); ?>" placeholder="Contact No" autocomplete="off" class="form-control" />
						</div>
						<label class="col-md-1 em_contact" style="display:none;color: red;padding-left: 0px;margin-left: -8px;">required</label>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="ec_email"> E-mail </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-6">
							<input type="text" id="ec_email" name="ec_email" placeholder="E-mail" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="nid"> NID </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-6">
							<input type="text" id="nid" name="nid" value="<?php echo set_value('nid'); ?>" placeholder="NID" autocomplete="off" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="em_reference"> Reference </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-6">
							<textarea id="em_reference" rows="3" name="em_reference" placeholder="Reference" class="form-control" autocomplete="off"></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for=""> </label>
						<label class="col-md-1 control-label"></label>
						<div class="col-md-6 text-right">
							<button type="button" onclick="Employee_submit()" name="btnSubmit" title="Save" class="btnSave">
								Save
								<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
							</button>
						</div>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="col-md-6">
			<fieldset class="scheduler-border scheduler-search">
				<legend class="scheduler-border">Personal Information</legend>
				<div class="control-group">

					<div class="form-group">
						<label class="col-md-4 control-label" for="form-field-1"> Father's Name </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-6">
							<input type="text" id="em_father" name="em_father" placeholder="Father's Name" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="form-field-1"> Mother's Name </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-6">
							<input type="text" id="mother_name" name="mother_name" placeholder="Mother's Name" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="form-field-1"> Gender </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-6">
							<select class="chosen-select form-control" name="Gender" id="Gender">
								<option value="Male">Male</option>
								<option value="Female">Female</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="em_dob">Date of Birth</label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-6">
							<input class="form-control" name="em_dob" id="em_dob" type="date" style="border-radius: 5px !important; margin-bottom:4px;" value="<?php echo date("Y-m-d") ?>" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label" for="Marital"> Marital status </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-6">
							<select class="chosen-select form-control" name="Marital" id="Marital">
								<option value="unmarried">Unmarried</option>
								<option value="married">Married</option>
							</select>
						</div>
					</div>
				</div>
			</fieldset>
		</div>

		<div class="col-md-6">
			<fieldset class="scheduler-border scheduler-search" style="height: 175px;">
				<legend class="scheduler-border">Picture Section</legend>
				<div class="control-group">
					<div class="col-md-5">
						<label for="">Employee Image</label>
						<input type="file" id="em_photo" name="em_photo" class="form-control" onchange="readURL(this)" style="height: 26px;padding: 1px 0;border-radius: 3px;" />
					</div>
					<div class="col-md-7">
						<img id="hideid" src="<?php echo base_url(); ?>uploads/no_user.png" alt="" style="width:100px;">
						<img id="preview" src="#" style="width:80px;height:80px" hidden>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
</div>


<script type="text/javascript">
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				document.getElementById('preview').src = e.target.result;
			}
			reader.readAsDataURL(input.files[0]);
			$("#hideid").hide();
			$("#preview").show();
		}
	}
</script>
<script type="text/javascript">
	function resetData() {
		$("#em_name").css('border-color', '');
		$(".em_name").css('display', 'none');
		$("#em_Designation").css('border-color', '');
		$(".em_Designation").css('display', 'none');
		$("#em_Depertment").css('border-color', '');
		$(".em_Depertment").css('display', 'none');
		$("#em_Joint_date").css('border-color', '');
		$(".em_Joint_date").css('display', 'none');
		$("#Gender").css('border-color', '');
		$(".Gender").css('display', 'none');
		$("#em_dob").css('border-color', '');
		$(".em_dob").css('display', 'none');
		$("#Marital").css('border-color', '');
		$(".Marital").css('display', 'none');
		$("#em_contact").css('border-color', '');
		$(".em_contact").css('display', 'none');
		$("#salary_range").css('border-color', '');
		$(".salary_range").css('display', 'none');
	}

	function Employee_submit() {
		var Employeer_id = $("#Employeer_id").val();
		var em_name = $("#em_name").val();
		resetData();
		if (em_name == "") {
			$("#em_name").css('border-color', 'red');
			$(".em_name").css('display', 'block');
			return false;
		}
		var em_Designation = $("#em_Designation").val();
		if (em_Designation == "") {
			$("#em_Designation").css('border-color', 'red');
			$(".em_Designation").css('display', 'block');
			return false;
		}
		var em_Depertment = $("#em_Depertment").val();
		if (em_Depertment == "") {
			$("#em_Depertment").css('border-color', 'red');
			$(".em_Depertment").css('display', 'block');
			return false;
		}
		var em_Joint_date = $("#em_Joint_date").val();
		if (em_Joint_date == "") {
			$("#em_Joint_date").css('border-color', 'red');
			$(".em_Joint_date").css('display', 'block');
			return false;
		}
		var Gender = $("#Gender").val();
		if (Gender == "") {
			$("#Gender").css('border-color', 'red');
			$(".Gender").css('display', 'block');
			return false;
		}
		var em_dob = $("#em_dob").val();
		if (em_dob == "") {
			$("#em_dob").css('border-color', 'red');
			$(".em_dob").css('display', 'block');
			return false;
		}
		var Marital = $("#Marital").val();
		if (Marital == "") {
			$("#Marital").css('border-color', 'red');
			$(".Marital").css('display', 'block');
			return false;
		}
		var salary_range = $("#salary_range").val();
		if (salary_range == "") {
			$("#salary_range").css('border-color', 'red');
			$(".salary_range").css('display', 'block');
			return false;
		}
		var em_contact = $("#em_contact").val();
		if (em_contact == "") {
			$("#em_contact").css('border-color', 'red');
			$(".em_contact").css('display', 'block');
			return false;
		}
		var em_Present_address = $("#em_Present_address").val();
		var em_reference = $("#em_reference").val();

		var em_father = $("#em_father").val();
		var mother_name = $("#mother_name").val();

		var em_Permanent_address = $("#em_Permanent_address").val();



		var ec_email = $("#ec_email").val();
		var status = $("#status").val();

		var fd = new FormData();
		fd.append('em_photo', $('#em_photo')[0].files[0]);
		fd.append('Employeer_id', $('#Employeer_id').val());
		fd.append('em_name', $('#em_name').val());
		fd.append('em_Designation', $('#em_Designation').val());
		fd.append('em_Depertment', $('#em_Depertment').val());
		fd.append('em_Joint_date', $('#em_Joint_date').val());
		fd.append('em_father', $('#em_father').val());
		fd.append('mother_name', $('#mother_name').val());
		fd.append('em_Present_address', $('#em_Present_address').val());
		fd.append('em_reference', $('#em_reference').val());
		fd.append('em_Permanent_address', $('#em_Permanent_address').val());
		fd.append('em_dob', $('#em_dob').val());
		fd.append('em_contact', $('#em_contact').val());
		fd.append('Gender', $('#Gender').val());
		fd.append('ec_email', $('#ec_email').val());
		fd.append('Marital', $('#Marital').val());
		fd.append('bio_id', $('#bio_id').val());
		fd.append('nid', $('#nid').val());

		fd.append('salary_range', salary_range);
		fd.append('status', $('#status').val());

		var x = $.ajax({
			url: "<?php echo base_url(); ?>employeeInsert",
			type: "POST",
			data: fd,
			enctype: 'multipart/form-data',
			processData: false,
			contentType: false,
			success: function(res) {
				let resp = $.parseJSON(res);
				alert(resp.message);
				if (resp.success) {
					location.reload();
				}
			}
		});
	}
</script>