<?php
include('db_connect.php');

if (isset($_GET['id'])) {
    $member_id = $_GET['id'];
    $member = $conn->query("SELECT * FROM members WHERE id = $member_id");
    $meta = $member->fetch_assoc();
}
?>

<div class="container-fluid">
<form action="" id="manage-user">
    <input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id'] : '' ?>">
    <?php if (isset($meta['lastname'])) : ?>
    <div class="form-group">
        <label for="lastname">Last Name</label>
        <input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo isset($meta['lastname']) ? $meta['lastname'] : '' ?>" required>
    </div>
    <?php endif; ?>
    <?php if (isset($meta['firstname'])) : ?>
    <div class="form-group">
        <label for="firstname">First Name</label>
        <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo isset($meta['firstname']) ? $meta['firstname'] : '' ?>" required>
    </div>
    <?php endif; ?>
    <?php if (isset($meta['middlename'])) : ?>
    <div class="form-group">
        <label for="middlename">Middle Name</label>
        <input type="text" name="middlename" id="middlename" class="form-control" value="<?php echo isset($meta['middlename']) ? $meta['middlename'] : '' ?>" required>
    </div>
    <?php endif; ?>
    <?php if (isset($meta['institution_name'])) : ?>
    <div class="form-group">
        <label for="institution_name" class="control-label">Name of Institution</label>
        <input type="text" id="institution_name" name="institution_name" value="<?php echo $meta['institution_name'] ?>" class="form-control">
    </div>
    <?php endif; ?>
    <?php if (isset($meta['rep_name'])) : ?>
    <div class="form-group">
        <label for="rep_name" class="control-label">Representative Name</label>
        <input type="text" id="rep_name" name="rep_name" value="<?php echo $meta['rep_name'] ?>" class="form-control">
    </div>
    <?php endif; ?>
    <?php if (isset($meta['license_no'])) : ?>
    <div class="form-group">
        <label for="license_no" class="control-label">License Number</label>
        <input type="text" id="license_no" name="license_no" value="<?php echo $meta['license_no'] ?>" class="form-control">
    </div>
    <?php endif; ?>
    <div class="form-group">
        <label for="address">Address</label>
        <input type="text" name="address" id="address" class="form-control" value="<?php echo isset($meta['address']) ? $meta['address'] : '' ?>" required>
    </div>
    <div class="form-group">
        <label for="contact_no">Contact No</label>
        <input type="text" name="contact_no" id="contact_no" class="form-control" value="<?php echo isset($meta['contact_no']) ? $meta['contact_no'] : '' ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? $meta['email'] : '' ?>" required>
    </div>
    <div class="form-group">
        <label for="tax_id">Tax ID</label>
        <input type="text" name="tax_id" id="tax_id" class="form-control" value="<?php echo isset($meta['tax_id']) ? $meta['tax_id'] : '' ?>" required>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?php if (isset($meta['id_front_path'])) : ?>
                <img src="<?php echo $meta['id_front_path'] ?>" class="img-thumbnail" alt="ID Front">
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label for="id_front">ID Front Picture</label>
                <input type="file" name="id_front" id="id_front" class="form-control">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php if (isset($meta['id_back_path'])) : ?>
                <img src="<?php echo $meta['id_back_path'] ?>" class="img-thumbnail" alt="ID Back">
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label for="id_back">ID Back Picture</label>
                <input type="file" name="id_back" id="id_back" class="form-control">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php if (isset($meta['profile_pic_path'])) : ?>
                <img src="<?php echo $meta['profile_pic_path'] ?>" class="img-thumbnail" alt="Profile Pic">
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label for="profile_pic">Profile Picture</label>
                <input type="file" name="profile_pic" id="profile_pic" class="form-control">
            </div>
        </div>
    </div>

</form>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        // Log member details to console
        console.log(<?php echo json_encode($meta); ?>);

        $('#manage-user').submit(function(e) {
            e.preventDefault();

            // Create FormData object to handle form data
            var formData = new FormData(this);

            start_load();
            $.ajax({
                url: 'ajax.php?action=save_member', // Update the action name as needed
                method: 'POST',
                data: formData,
                processData: false, // Prevent jQuery from processing the data
                contentType: false, // Prevent jQuery from setting contentType
                success: function(resp) {
                    console.log('Response from server:', resp);
                    if (resp == 1) {
                        alert_toast("Data successfully saved", 'success');
                        setTimeout(function(e) {
                            location.reload();
                        }, 1500);
                    } else {
                        alert_toast("Failed to save data. Please try again", 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert_toast("An error occurred while processing your request. Please try again later.", 'error');
                }
            });
        });
    });
</script>