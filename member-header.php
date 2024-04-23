<header class="bg-blue-100 flex-column sticky">
    <nav class="navbar navbar-light" style="padding:0;">
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


        <div class="flex flex-row justify-between px-12 py-4 w-full bg-[#f2efef]">
            <div><a class="text-black" href="member-home.php"><i class="fa-solid fa-left-long"></i> Go Back</a></div>
            <div class="flex justify-item-start">
                <strong>
                    <p>Welcome to UTUMISHI Sacco, <?php echo $member['firstname'] ?>!</p>
                </strong>
            </div>

            <div class="flex flex-row gap-4">
                <div class="flex flex-row gap-2 col items-center">
                    <img class="" width="25px" height="25px" src="./assets/img/gear.png" alt="settings">
                    <strong>
                        <p>Settings</p>
                    </strong>
                </div>
                <a style="text-decoration: none;" href="ajax.php?action=logout">
                    <div class="flex flex-row gap-2 col items-center">
                        <i class="fa-solid fa-right-from-bracket" style="color: #FFC66C; font-size: 25px;"></i>
                        <strong>
                            <p>Logout</p>
                        </strong>
                    </div>
                </a>
            </div>
        </div>
    </nav>
</header>