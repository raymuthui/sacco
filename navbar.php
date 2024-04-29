
<style>
</style>
<nav id="sidebar" class='mx-lt-5 bg-white' >
		
		<div class="sidebar-list">

				<a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Home</a>
				<a href="index.php?page=news" class="nav-item nav-news"><span class='icon-field'><i class="icofont-newspaper"></i> News</span></a>
				<a href="index.php?page=loans" class="nav-item nav-loans"><span class='icon-field'><i class="fa fa-file-invoice-dollar"></i></span> Loans</a>	
				<a href="index.php?page=payments" class="nav-item nav-payments"><span class='icon-field'><i class="fa fa-money-bill"></i></span> Payments</a>
				<a href="index.php?page=savings" class="nav-item nav-borrowers"><span class='icon-field'><i class="fa fa-user-friends"></i></span> Savings</a>
				<!-- <a href="index.php?page=plan" class="nav-item nav-plan"><span class='icon-field'><i class="fa fa-list-alt"></i></span> Loan Plans</a>	 -->
				<a href="index.php?page=loan_type" class="nav-item nav-loan_type"><span class='icon-field'><i class="fa fa-th-list"></i></span> Loan Types</a>	
				<a href="index.php?page=members" class="nav-item nav-members"><span class='icon-field'><i class="fa fa-users"></i></span> Members</a>	
				<?php if($_SESSION['login_type'] == 1): ?>
				<a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users"></i></span> Users</a>
				<?php endif; ?>
			<a href="ajax.php?action=logout" class="nav-item nav-logout"><span class='icon-field'><i class="fa fa-lock"></i></span> Logout</a>
		</div>

</nav>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>
