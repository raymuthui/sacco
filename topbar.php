<style>
	.logo {
		margin: auto;
		font-size: 35px;
		/* background: white;
    padding: 7px 11px;
    border-radius: 50% 50%;
    color: #000000b3;*/
	}
</style>

<div id="page"></div>
<div id="loading"></div>
<nav class="navbar navbar-light fixed-top" style="padding:0; height: 180px">
	<div class="container-fluid mt-2 mb-2">
		<div class="col-lg-12 d-flex align-items-center justify-content-between">
			<div class="col-md-2 float-left" style="display: flex;">
				<div class="logo">
					<img src="assets/img/logo.png" width="320px">
				</div>
			</div>

			<div class="col-md-6 text-right text-dark">
				<strong><a href="https://www.enchanted-tech.com/" target="a_blank" class="text-dark">Contact Author : Enchanted Tech.</a></strong>
			</div>
		</div>
	</div>

	<div class="flex flex-row justify-between px-12 py-4 w-full bg-[#80808045]">
		<div class="flex justify-item-start">
			<strong>
				<p>Welcome to UTUMISHI Sacco, <?php echo $user['name'] ?>!</p>
			</strong>
		</div>
	</div>

</nav>