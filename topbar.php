<?php $username = $_SESSION["username"]; $usertype = getValue('users','username', $username,'type'); ?>
<header>
        <div class="welcome_info">Welcome, <?php echo $_SESSION["username"]; ?>!</div>
        <div class="header_nav">
            <a href="logout.php" ><i class="fa-solid fa-right-from-bracket"></i></a>
            <a href="profile.php"><i class="fa-solid fa-user"></i></a>

           
            
        </div>
</header>
<div class="top-bar">
    <div style="width:30%; display:inline-block;"><a href="/"><img src="https://samsgroup.info/img/logo/SAMSlogo.png" width=100px></a></div>
    <div style="width:69%; text-align:right; display:inline-block;">
        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="/">Home</a>
            <?php if ($usertype == "admin") { ?>
                <?php if ($usertype == "admin") { ?> <div class="title"><i class="fa-solid fa-caret-right"></i> Admin/Data Menu</div> <?php } ?>
                <a href="https://pim.samsgroup.info/check_inactive.php">Check Inactive SKUs</a>
            <?php } ?>
                <a href="https://pim.samsgroup.info/logs.php">PIM Logs</a>
            <?php if ($usertype == "graphics" || $usertype == "admin") { ?>
                <?php if ($usertype == "admin") { ?> <div class="title"><i class="fa-solid fa-caret-right"></i> Graphic Team Menu</div> <?php } ?>
                <a href="https://pim.samsgroup.info/upload_images.php">Upload images to PIM</a>
                <a href="https://pim.samsgroup.info/temp_images.php"> Approve Uploaded Images</a>
            <?php } ?>

            <?php if ($usertype == "admin" || $usertype == "marketing") { ?>
                <?php if ($usertype == "admin") { ?> <div class="title"><i class="fa-solid fa-caret-right"></i> Marketing Team Menu</div> <?php } ?>
                <a href="https://pim.samsgroup.info/update.php">Update or Add to PIM</a>
                <a href="https://pim.samsgroup.info/marketing_incomplete.php"> Missing Descriptions or Tags</a>
            <?php } ?>

            <?php if ($usertype == "admin" || $usertype == "sales") { ?>
                <?php if ($usertype == "admin") { ?> <div class="title"><i class="fa-solid fa-caret-right"></i> Sales Team Menu</div> <?php } ?>
                <a href="https://pim.samsgroup.info/export-data/">Export Data for Stockists</a>
                <a href="https://pim.samsgroup.info/stockist-images/">Grab Images for Stockists</a>
            <?php } ?>
        </div>
        <span onclick="openSearch()"><i class="fa-solid fa-magnifying-glass"></i></span> &nbsp; &nbsp;
        <span onclick="openNav()"><i class="fa-solid fa-bars fa-xl menu-icon"></i></span>

        <div id="mySearchnav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeSearch()">&times;</a>
            <div class="searchnav" id="searchnav">
                <h2>Search PIM</h2>
                <form action="search.php" method="post" name="searchpim" enctype="multipart/form-data">
                    <input type="radio" id="sku">Search SKU</input>
                    <input type="radio" id="name">Search Product Name</input>
                    <input type="text" id="value" value="Input Search Term"></input>
                    <button type="submit" id="submit" name="Submit" class="btn btn-primary button-loading">>Submit</button>   
                </form>
            </div>

        </div>
    </div>
</div>
<br>
<br>

<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "400px";
        window.addEventListener('mouseup',function(event){
        var mySidenav = document.getElementById('mySidenav');
        if(event.target != mySidenav && event.target.parentNode != mySidenav){
            document.getElementById("mySidenav").style.width = "0";
        }
        });  
    }
    function openSearch() {
        document.getElementById("mySearchnav").style.width = "400px";
        window.addEventListener('mouseup',function(event){
        var mySidenav = document.getElementById('mySearchnav');
        /*if(event.target != searchnav && event.target.parentNode != searchnav){
            document.getElementById("mySearchnav").style.width = "0";
        }*/
        }); 
    }

    /* Set the width of the side navigation to 0 */
    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }
    function closeSearch() {
        document.getElementById("mySearchnav").style.width = "0";
    }

    var active = 0;
    for (var i = 0; i < document.links.length; i++) {
        if (document.links[i].href === document.URL) {
            active = i;
        }
    }
    document.links[active].className = 'active';
</script>