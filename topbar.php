<?php $username = $_SESSION["username"]; $usertype = getValue('users','username', $username,'type'); ?>
<header>
        <div class="welcome_info">Welcome, <?php echo $_SESSION["username"]; ?>!</div>
        <div class="header_nav">
            <a href="logout.php" >Logout</a>
            <a href="profile.php">User Profile</a>

            
            <?php if ($usertype == "graphics" || $usertype == "admin") { ?>
                <a href="temp_images.php" > Check Uploaded Images</a>
                <a href="upload_images.php" >Upload images</a>
            <?php } ?>

            <?php if ($usertype == "admin" || $usertype == "marketing") { ?>
                <a href="update.php" >Update Products</a>
                <a href="import.php" >Import Products</a>
                <a href="marketing_incomplete.php" > Missing Descriptions or Tags</a>
            <?php } ?>

           
            <a href="homepage.php">Home</a>
        </div>
</header>