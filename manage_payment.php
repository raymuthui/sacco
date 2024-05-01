<?php include 'db_connect.php' ?>
<?php 

if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM payments where id=".$_GET['id']);
	foreach($qry->fetch_array() as $k => $val){
		$$k = $val;
	}
}
$default_overdue = 0;

?>
<div class="container-fluid">
	<div class="col-lg-12">
		<form id="manage-payment">
			<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="" class="control-label">Loan Reference No.</label>
						<select name="loan_id" id="" class="custom-select browser-default select2">
							<option value=""></option>
							<?php 
							$loan = $conn->query("SELECT * from loan_list where status =2 ");
							while($row=$loan->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($loan_id) && $loan_id == $row['id'] ? "selected" : '' ?> readonly><?php echo $row['ref_no'] ?></option>
							<?php endwhile; ?>
						</select>
						
					</div>
					<div class="form-group">
						<label for="" class="control-label">Loan Payment Method</label>
						<select name="payment_method" id="" class="custom-select browser-default select2">
							<option value=""></option>
							<option value="Mpesa">Mpesa</option>
							<option value="Paypal">Paypal</option>
							<option value="Credit Card">Credit Card</option>
						</select>
						
					</div>
					<div>
						<label for="penalty_amount">Penalty Amount</label>
						<input type="number" name="penalty_amount" id="penalty_amount">
					</div>
					<div class="form-group">
						<label class="control-label" for="amount">Amount</label>
						<input type="number" name="amount" id="amount" class="form-control">
					</div>
					<!-- Hidden input field for status with default value of 0 -->
					<input type="hidden" name="overdue" id="overdue" value="<?php echo $default_overdue; ?>">
				</div>
			</div>
			<!-- <div class="row" id="fields">
				
			</div> -->
		</form>
	</div>
</div>

<script>
	$('[name="loan_id"]').change(function(){
		load_fields()
	})
	$('.select2').select2({
		placeholder:"Please select here",
		width:"100%"
	})

	// function load_fields(){
	// 	start_load()
	// 	$.ajax({
	// 		url:'load_fields.php',
	// 		method:"POST",
	// 		data:{id:'<?php echo isset($id) ? $id : "" ?>',loan_id:$('[name="loan_id"]').val()},
	// 		success:function(resp){
	// 			if(resp){
	// 				$('#fields').html(resp)
	// 				end_load()
	// 			}
	// 		}
	// 	})
	// }
	 $('#manage-payment').submit(function(e){
		console.log("Submit");
	 	e.preventDefault()
	 	start_load()
	 	$.ajax({
	 		url:'ajax.php?action=save_payment',
	 		method:'POST',
	 		data:$(this).serialize(),
	 		success:function(resp){
	 			if(resp == 1){
	 				alert_toast("Payment data successfully saved.","success");
	 				setTimeout(function(e){
	 					location.reload()
	 				},1500)
	 			}
	 		}
	 	})
	 })
	 $(document).ready(function(){
	 	if('<?php echo isset($_GET['id']) ?>' == 1)
		load_fields()
	 })
</script>