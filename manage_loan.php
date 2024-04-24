<?php 
include('db_connect.php');

// Check if ID is set for editing existing loan data
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT l.*, m.firstname, m.middlename, m.lastname, m.contact_no, m.address, lt.type_name, lt.interest_percentage, lt.penalty_rate, lt.months
                            FROM loan_list l 
                            INNER JOIN members m ON l.member_id = m.id 
                            INNER JOIN loan_types lt ON l.loan_type_id = lt.id
                            WHERE l.id = ".$_GET['id']);
    $row = $qry->fetch_assoc();
}

// Fetch loan types
$type = $conn->query("SELECT * FROM loan_types ORDER BY `type_name` DESC");
?>

<div class="container-fluid">
    <div class="col-lg-12">
        <form action="" id="loan-application">
            <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label">Borrower</label>
                    <select name="member_id" id="member_id" class="custom-select browser-default select2">
                        <option value=""></option>
                        <?php if(isset($row['member_id'])): ?>
                            <option value="<?php echo $row['member_id'] ?>" selected>
                                <?php echo $row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname'] ?>
                            </option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="control-label">Loan Type</label>
                    <select name="loan_type_id" id="loan_type_id" class="custom-select browser-default select2">
                        <option value=""></option>
                        <?php while($row_type = $type->fetch_assoc()): ?>
                            <option value="<?php echo $row_type['id'] ?>" <?php echo isset($row['loan_type_id']) && $row['loan_type_id'] == $row_type['id'] ? "selected" : '' ?>>
                                <?php echo $row_type['type_name'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label">Loan Amount</label>
                    <input type="number" name="amount" class="form-control text-right" step="any" value="<?php echo isset($row['amount']) ? $row['amount'] : '' ?>">
                    <input type="hidden" id="initial_status" name="initial_status" value="<?php echo isset($row['status']) ? $row['status'] : ''; ?>">
                </div>
				<div class="form-group col-md-4">
                    <label class="control-label">Status</label>
                    <select class="custom-select browser-default" name="status">
                        <option value="0" <?php echo isset($row['status']) && $row['status'] == 0 ? 'selected' : '' ?>>For Approval</option>
                        <option value="1" <?php echo isset($row['status']) && $row['status'] == 1 ? 'selected' : '' ?>>Approved</option>
                        <option value="2" <?php echo isset($row['status']) && $row['status'] == 2 ? 'selected' : '' ?>>Released</option>
                        <option value="3" <?php echo isset($row['status']) && $row['status'] == 3 ? 'selected' : '' ?>>Complete</option>
                        <option value="4" <?php echo isset($row['status']) && $row['status'] == 4 ? 'selected' : '' ?>>Denied</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label">Purpose</label>
                    <textarea name="purpose" id="" cols="30" rows="2" class="form-control"><?php echo isset($row['purpose']) ? $row['purpose'] : '' ?></textarea>
                </div>
                <!-- <div class="form-group col-md-4">
                    <label class="control-label">&nbsp;</label>
                    <button class="btn btn-primary btn-block" type="button" id="calculate">Calculate</button>
                </div> -->
            </div>
            <!-- <div id="calculation_table"></div> -->
            <div class="row">
                <div class="col-md-12 text-center">
                    <button class="btn btn-primary">Save</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.select2').select2({
            placeholder: "Please select here",
            width: "100%"
        });

        // $('#calculate').click(function(){
        //     calculate();
        // });

        $('#loan-application').submit(function(e){
            e.preventDefault();
            start_load();
			var status = $('[name="status"').val(); // Get the selected status value
            var initialStatus = $('#initial_status').val(); 
			console.log("Selected status: ", status);

			if(status == 2 && initialStatus != 2) {
				// Update date_released field
				var currentDate = '<?php echo date("Y-m-d H:i:s"); ?>';
				console.log('Current Date: ', currentDate);
				$('[name="date_released"]').val(currentDate);
			}

            $.ajax({
                url: 'ajax.php?action=save_loan',
                method: 'POST',
                data: $(this).serialize(),
                success: function(resp){
                    if(resp == 1){
                        $('.modal').modal('hide');
                        alert_toast("Loan Data successfully saved.", "success");
                        setTimeout(function(){
                            location.reload();
                        }, 1500);
                    }
                }
            });
        });

        // if('<?php echo isset($_GET['id']) ?>' == 1){
        //     calculate();
        // }
    });

    // function calculate(){
    //     start_load();
    //     if($('[name="loan_type_id"]').val() == '' && $('[name="amount"]').val() == ''){
    //         alert_toast("Select loan type and enter amount first.", "warning");
    //         return false;
    //     }
    //     var interest = $('[name="loan_type_id"] option:selected').attr('data-interest_percentage');
    //     var penalty = $('[name="loan_type_id"] option:selected').attr('data-penalty_rate');
    //     var months = $('[name="loan_type_id"] option:selected').attr('data-months');
    //     $.ajax({
    //         url: "calculation_table.php",
    //         method: "POST",
    //         data: {
    //             amount: $('[name="amount"]').val(),
    //             interest: interest,
    //             penalty: penalty,
    //             months: months
    //         },
    //         success: function(resp){
    //             if(resp){
    //                 $('#calculation_table').html(resp);
    //                 end_load();
    //             }
    //         }
    //     });
    // }
</script>

<style>
    #uni_modal .modal-footer{
        display: none;
    }
</style>
