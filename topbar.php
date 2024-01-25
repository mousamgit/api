<?php $username = $_SESSION["username"]; $usertype = getValue('users','username', $username,'type'); ?>

<script src="./js/myjquery.js" ></script>
<!-- Navigation Drawer -->
<div class="layout-drawer">
    <div class="drawer-header drawer-header-cover" style="background-color: #e1e1e1; color: #000;">
        <div class="drawer-user">
            <div class="drawer-avatar">

            </div>
            <div class="drawer-meta">
                <span class="drawer-name"><a href="profile.php"> <i class="fa-solid fa-user"></i>  <?php echo $_SESSION["username"]; ?></a></span>
                <span class="drawer-email"></span>
            </div>
        </div>
<<<<<<< HEAD
=======
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
>>>>>>> 7f6650b9fe6be10bc903c1605aa6de95278a0f2d
    </div>
    <nav class="drawer-navigation drawer-border">
        <a class="drawer-list-item drawer-list-item is-active" href="/" >Home</a>
    </nav>
    

        <?php if ($usertype == "admin") { 
            echo '<nav class="drawer-navigation drawer-border">';
            echo '<button class="drawer-dropdown-toggle" data-target="#drawer-dropdown-admin"><span>Admin/Data Menu</span><i class="fa-solid fa-caret-down"></i></button>';
            echo '<nav class="drawer-navigation drawer-border" id="drawer-dropdown-admin">';
            echo '<a class="drawer-list-item"  href="/check_inactive.php"><span>Check Inactive SKUs</span></a>';
            echo '<a class="drawer-list-item"  href="/logs.php"><span>PIM Logs</span></a>';
            echo '</nav> </nav>';
        }
        ?>
        <?php if (usertype == "graphics" || $usertype == "admin") { 
            echo '<nav class="drawer-navigation drawer-border">';
            echo '<button class="drawer-dropdown-toggle" data-target="#drawer-dropdown-graphic"><span>Graphic Team Menu</span><i class="fa-solid fa-caret-down"></i></button>';
            echo '<nav class="drawer-navigation drawer-border" id="drawer-dropdown-graphic">';
            echo '<a class="drawer-list-item"  href="/upload_images.php"><span>Upload images to PIM</span></a> ';
            echo '<a class="drawer-list-item"  href="/temp_images.php"><span>Approve Uploaded Images</span></a>';
            echo '</nav> </nav>';
        }
        ?>
        <?php if ($usertype == "marketing" || $usertype == "admin") { 
            echo '<nav class="drawer-navigation drawer-border">';
            echo '<button class="drawer-dropdown-toggle" data-target="#drawer-dropdown-marketing"><span>Marketing Team Menu</span><i class="fa-solid fa-caret-down"></i></button>';
            echo '<nav class="drawer-navigation drawer-border" id="drawer-dropdown-marketing">';
            echo '<a class="drawer-list-item"  href="/update.php"><span>Update or Add to PIM</span></a> ';
            echo '<a class="drawer-list-item"  href="/marketing_incomplete.php"><span>Missing Descriptions or Tags</span></a>';
            echo '</nav> </nav>';
        }
<<<<<<< HEAD
        ?>
        <?php if ($usertype == "sales" || $usertype == "admin") { 
            echo '<nav class="drawer-navigation drawer-border">';
            echo '<button class="drawer-dropdown-toggle" data-target="#drawer-dropdown-sales"><span>Sales Team Menu</span><i class="fa-solid fa-caret-down"></i></button>';
            echo '<nav class="drawer-navigation drawer-border" id="drawer-dropdown-sales">';
            echo '<a class="drawer-list-item"  href="/export-data"><span>Export Data for Stockists</span></a> ';
            echo '<a class="drawer-list-item"  href="/stockist-images"><span>Grab Images for Stockists</span></a>';
            echo '</nav> </nav>';
        }
        ?>

    <nav class="drawer-navigation">
        <a href="logout.php"  class="drawer-list-item drawer-icon-right" ><span>Log out</span></a>
    </nav>
</div>
=======
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
>>>>>>> 7f6650b9fe6be10bc903c1605aa6de95278a0f2d

<header>
    <div class="header-menu">
<a class="header-drawer-toggle">
        <i class="fa-solid fa-bars fa-xl menu-icon"></i>
    </a>
    <a class="header-search">
        <i class="fa-solid fa-search fa-xl menu-icon"></i>
    </a>
    <form id="search-form">
        <label for="fname">search:</label>
        <input type="text" id="search-field">
        <input type="submit" value="Submit">
    </form>
    </div>

</header>
