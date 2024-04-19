<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>New User Registration</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Add your custom CSS styles here -->
  <style>
    body {
      width: 100%;
      height: 100vh;
      background: #007bff;
    }

    main#main {
      width: 100%;
      height: calc(100%);
      background: url(assets/img/background.jpg);
      background-repeat: no-repeat;
      background-size: cover;
    }

    #login-right {
      position: absolute;
      left: 0;
      width: 40%;
      height: calc(100%);
      display: flex;
      align-items: center;
    }

    #login-left {}

    #login-right .card {
      margin: auto;
      z-index: 1
    }

    .logo {
      margin: auto;
      font-size: 4rem;
      background: white;
      border-radius: 50% 50%;
      color: #000000b3;
      z-index: 10;
      text-align: center;
    }

    div#login-right::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: calc(100%);
      height: calc(100%);
    }
  </style>
</head>

<body>
  <main id="main" class="bg-dark">
    <div id="login-right">
      <div class="card col-md-10">
        <div class="card-body">
          <div class="logo">
            <img src="assets/img/logo.png" width="250px">
          </div>
          <form id="registration-form">
            <div class="form-group row">
              <div class="col">
                <label for="lastname" class="control-label">Last Name</label>
                <input type="text" id="lastname" name="lastname" class="form-control">
              </div>
              <div class="col">
                <label for="firstname" class="control-label">First Name</label>
                <input type="text" id="firstname" name="firstname" class="form-control">
              </div>
              <div class="col">
                <label for="middlename" class="control-label">Middle Name</label>
                <input type="text" id="middlename" name="middlename" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label for="address" class="control-label">Address</label>
              <input type="text" id="address" name="address" class="form-control">
            </div>
            <div class="form-group">
              <label for="contact_no" class="control-label">Contact No</label>
              <input type="text" id="contact_no" name="contact_no" class="form-control">
            </div>
            <div class="form-group">
              <label for="email" class="control-label">Email</label>
              <input type="email" id="email" name="email" class="form-control">
            </div>
            <div class="form-group">
              <label for="password" class="control-label">Password</label>
              <input type="password" id="password" name="password" class="form-control">
            </div>
            <div class="form-group">
              <label for="tax_id" class="control-label">Tax ID</label>
              <input type="text" id="tax_id" name="tax_id" class="form-control">
            </div>
            <div class="form-group row">
              <div class="col">
                <label for="id_front" class="control-label">ID Front Picture</label>
                <input type="file" id="id_front" name="id_front" class="form-control">
              </div>
              <div class="col">
                <label for="id_back" class="control-label">ID Back Picture</label>
                <input type="file" id="id_back" name="id_back" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label for="profile_pic" class="control-label">Profile Picture</label>
              <input type="file" id="profile_pic" name="profile_pic" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </main>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $('#registration-form').submit(function(e) {
    e.preventDefault();

    var formData = new FormData(this);

    // Debugging: Log FormData to console
    for (var pair of formData.entries()) {
      console.log(pair[0] + ': ' + pair[1]);
    }

    // Uncomment the below line to display FormData as an alert
    // alert('FormData: ' + formData);

    $.ajax({
      url: 'ajax.php?action=save_member',
      method: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(resp) {
        console.log('Response from server:', resp); //log the response
        if (resp == 1) {
          // Redirect to success page
          window.location.href = 'success_page.php'
        } else {
          alert("Submission failed. Please try again.");
          location.reload();
        }
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
        alert_toast("An error occurred while processing your request. Please try again later.");
        location.reload();
      }
    });
  });
</script>

</html>