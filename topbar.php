<?php $username = $_SESSION["username"]; $usertype = getValue('users','username', $username,'type'); ?>


<!-- Navigation Drawer -->
<div class="layout-drawer">
    <div class="drawer-header drawer-header-cover" style="background-color: #ae1930; color: #000;">
        <div class="drawer-user">
            <div class="drawer-avatar">
                <img src="https://pim.samsgroup.info/sga-logo.jpg" width=200px>
            </div>
            <div class="drawer-meta">
                <span class="drawer-name"><a href="profile.php" style="color:#fff;"> <i class="fa-solid fa-user"></i>  <?php echo $_SESSION["username"]; ?></a></span>
                <span class="drawer-email"></span>
            </div>
        </div>
        <!-- <span onclick="openSearch()"><i class="fa-solid fa-magnifying-glass"></i></span> &nbsp; &nbsp;
        <span onclick="openNav()"><i class="fa-solid fa-bars fa-xl menu-icon"></i></span>

        <div id="mySearchnav" class="sidenav">
            
            <div class="searchnav" id="searchnav">
                <h2>Search PIM</h2>
                <form action="search.php" method="post" name="searchpim" enctype="multipart/form-data">
                    <input type="radio" id="sku">Search SKU</input>
                    <input type="radio" id="name">Search Product Name</input>
                    <input type="text" id="value" value="Input Search Term"></input>
                    <button type="submit" id="submit" name="Submit" class="btn btn-primary button-loading">>Submit</button>   
                </form>
            </div>

        </div> -->
    </div>
    <nav class="drawer-navigation drawer-border">
        <a class="drawer-list-item drawer-list-item is-active" href="/" >Home</a>
        <a class="drawer-list-item"  href="/logs.php"><span>PIM Logs</span></a>
    </nav>
    

        <?php if ($usertype == "admin" || $usertype == "production") {
            echo '<nav class="drawer-navigation drawer-border">';
            echo '<button class="drawer-dropdown-toggle" data-target="#drawer-dropdown-admin"><span>Admin/Data Menu</span><i class="fa-solid fa-caret-down"></i></button>';
            echo '<nav class="drawer-navigation drawer-border" id="drawer-dropdown-admin">';
            echo '<a class="drawer-list-item"  href="/check_inactive.php"><span>Check Inactive SKUs</span></a>';
            echo '<a class="drawer-list-item"  href="/myExports.php"><span>Export Templates</span></a>';
            //echo '';
            echo '</nav> </nav>';
        }
        ?>
        <?php if ($usertype == "graphics" || $usertype == "admin") { 
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
        ?>
        <?php if ($usertype == "sales" || $usertype == "admin") { 
            echo '<nav class="drawer-navigation drawer-border">';
            echo '<button class="drawer-dropdown-toggle" data-target="#drawer-dropdown-sales"><span>Sales Team Menu</span><i class="fa-solid fa-caret-down"></i></button>';
            echo '<nav class="drawer-navigation drawer-border" id="drawer-dropdown-sales">';
            echo '<a class="drawer-list-item"  href="/export-data"><span>Export Data for Stockists</span></a> ';
            echo '<a class="drawer-list-item"  href="/stockist-images"><span>Grab Images for Stockists</span></a>';
            echo '<a class="drawer-list-item"  href="/appro/appro_list.php"><span>Appros List</span></a>';
            echo '</nav> </nav>';
        
            echo '<nav class="drawer-navigation drawer-border">';
            echo '<button class="drawer-dropdown-toggle" ><span>Repair Centre</span><i class="fa-solid fa-caret-down"></i></button>';
            echo '<nav class="drawer-navigation drawer-border" id="drawer-dropdown-sales">';
            echo '<a class="drawer-list-item"  href="/add_repairjob.php"><span>Add a Repair</span></a> ';
            echo '<a class="drawer-list-item"  href="/repairs.php"><span>Repair Log</span></a>';
            echo '</nav> </nav>';
        }
        ?>

    <nav class="drawer-navigation">
        <a href="https://pim.samsgroup.info/logout.php"  class="drawer-list-item drawer-icon-right" ><span>Log out</span></a>
    </nav>
</div>


<header class="pim-padding">
    <div class="header-menu">
    <a class="header-drawer-toggle">
        <i class="fa-solid fa-bars fa-xl menu-icon"></i><span>s</span>
    </a>
    <a href="https://pim.samsgroup.info"><img src="https://pim.samsgroup.info/sga-pim-redwhite-horizontal.png" ></a>
    <a class="header-search">
    <span>s</span><i class="fa-solid fa-search fa-xl menu-icon"></i>
    </a>
    <a class="header-search-close">
    <span>s</span><i class="fa-solid fa-times fa-xl menu-icon"></i>
    </a>
    <form id="search-form" action="search.php" method="post" name="searchpim">
        <label for="fname">search:</label>
        <input type="submit" value="Search">
        <input type="text" id="search-field" name="search-term" placeholder="Type in SKU or Product Name"></input>
    </form>
    </div>

</header>
<div style="height:50px"></div>
