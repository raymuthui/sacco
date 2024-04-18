<?php include 'db_connect.php' ?>
<?php 
$members = $conn->query("SELECT * FROM members")->fetch_all();
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM borrowers where id=".$_GET['id']);
	foreach($qry->fetch_array() as $k => $val){
		$$k = $val;
	}
}

?>
<div class="container-fluid">
	<div class="col-lg-12">
		<form id="manage-borrower">
			<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="" class="control-label">Last Name</label>
						<input name="lastname" class="form-control" required="" value="<?php echo isset($lastname) ? $lastname : '' ?>">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">First Name</label>
						<input name="firstname" class="form-control" required="" value="<?php echo isset($firstname) ? $firstname : '' ?>">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Middle Name</label>
						<input name="middlename" class="form-control" value="<?php echo isset($middlename) ? $middlename : '' ?>">
					</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-6">
					<label for="">Address</label>
					<textarea name="address" id="" cols="30" rows="2" class="form-control" required=""><?php echo isset($address) ? $address : '' ?></textarea>
				</div>
				<div class="col-md-5">
					<div class="">
						<label for="">Contact #</label>
						<input type="text" class="form-control" name="contact_no" value="<?php echo isset($contact_no) ? $contact_no : '' ?>">
					</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-6">
					<label for="">Select Member</label>
					<select name="member_id" id="member_id" class="form-control" required="">
						<option value="">Select Member</option>
						<?php foreach($members as $member): ?>
							<option value="<?php echo $member[0] ?>" <?php echo isset($member_id) && $member_id == $member[0] ? 'selected' : '' ?>><?php echo $member[1] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-5">
					<div class="">
						<label for="">Tax ID</label>
						<input type="text" class="form-control" name="tax_id" value="<?php echo isset($tax_id) ? $tax_id : '' ?>">
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#member_id').change(function() {
            var member_id = $(this).val();
            if (member_id != '') {
                $.ajax({
                    url: 'ajax.php?action=fetch_member_details',
                    method: 'POST',
                    data: { member_id: member_id },
                    dataType: 'json',
                    success: function(data) {
                        if (data !== null) {
                            $('input[name="lastname"]').val(data.lastname).prop('readonly', true);
                            $('input[name="firstname"]').val(data.firstname).prop('readonly', true);
                            $('input[name="middlename"]').val(data.middlename).prop('readonly', true);
                            $('textarea[name="address"]').val(data.address).prop('readonly', true);
                            $('input[name="contact_no"]').val(data.contact_no).prop('readonly', true);
                            $('input[name="tax_id"]').val(data.tax_id).prop('readonly', true);
                        } else {
                            alert('Member details not found!');
                        }
                    }
                });
            } else {
                // Reset all fields if no member is selected
                $('input[name="lastname"]').val('').prop('readonly', false);
                $('input[name="firstname"]').val('').prop('readonly', false);
                $('input[name="middlename"]').val('').prop('readonly', false);
                $('textarea[name="address"]').val('').prop('readonly', false);
                $('input[name="contact_no"]').val('').prop('readonly', false);
                $('input[name="tax_id"]').val('').prop('readonly', false);
            }
        });
    });

    $('#manage-borrower').submit(function(e){
        e.preventDefault();
        start_load();
        $.ajax({
            url: 'ajax.php?action=save_borrower',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp){
                if(resp == 1){
                    alert_toast("Borrower data successfully saved.","success");
                    setTimeout(function(e){
                        location.reload();
                    }, 1500);
                } else {
                    alert_toast("Error saving data. Please try again.", "error");
                    console.log(resp); // Log the response for debugging
                }
            },
            error: function(xhr, status, error) {
                alert_toast("Error: " + error, "error");
                console.log(xhr.responseText); // Log the detailed error message
            }
        });
    });
</script>

