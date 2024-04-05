<?php
    include 'functions.php';
    $sku = array("TPR2103","TPR2105","TPR2140","STA0037","TDR1959","TPR2138","TPR2143","TPR2160","TPR2134");
    require ('connect.php');



?>

<html>
    <head>
        <?php include 'header.php'; ?>
        <title> Quote for Perth Mint </title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
        <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
        <style>
            .total {
                padding: 0.9em 0.9em;
                background: #ae1930;
                color: white;
                font-size: 0.9em;
                letter-spacing: .1em;
                transition: all .3s;
                text-transform: uppercase;
                text-align:center;
                width:80vw;
                margin: 0 auto;
            }
            .accordion {
                font-size: 1rem;
                width: 80vw;
                margin: 0 auto;
                border-radius: 5px;
            }

            .accordion-header,
            .accordion-body {
                background: white;
            }

            .accordion-header {
                padding: 1.5em 1.5em;
                background: #3E3E3E;
                color: white;
                cursor: pointer;
                font-size: .7em;
                letter-spacing: .1em;
                transition: all .3s;
                text-transform: uppercase;
            }

            .accordion__item {
                border-bottom: 1px solid #2F2F2F;
            }

            .accordion__item .accordion__item {
                border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            }

            .accordion-header:hover {
                background: #CC6F7E;
                position: relative;
                z-index: 5;
            }

            .accordion-body {
                background: #fcfcfc;
                color: #353535;
                display: none;
            }

            .accordion-body__contents {
                padding: 1.5em 1.5em;
                font-size: .85em;
            }

            .accordion__item.active:last-child .accordion-header {
                border-radius: none;
            }

            .accordion:first-child > .accordion__item > .accordion-header {
                border-bottom: 1px solid transparent;
            }

            .accordion__item.active .accordion-header {
                background: #ae1930;
            }

            .accordion__item .accordion__item .accordion-header {
                background: #f1f1f1;
                color: #353535;
            }
            .imgcontainer { width:500px; margin: 0 auto; height:500px; } 
            .mobile-only { display:none; }
            .desktop-only { display: inline-block; }
            .logo { margin:0 auto; width:10%;}
            .info-area { margin:0 auto; width:80vw; font-size: 1em; letter-spacing: .1em; padding: 1.5em 1.5em; margin-top:2em; margin-bottom: 2em; line-height: 1.5em;}
            @media screen and (min-width: 801px) {
                .accordion__item > .accordion-header:after {
                content: "\f107";
                font-family: FontAwesome;
                font-size: 1.2em;
                float: right;
                position: relative;
                top: -2px;
                transition: .3s all;
                transform: rotate(0deg);
                }

                .accordion__item.active > .accordion-header:after {
                    transform: rotate(-180deg);
                }
            }
            @media screen and (max-width: 800px) {
                .accordion { width: 100%; }
                .imgcontainer { width:100%; margin: 0 auto; height: 50vh; }
                .accordion-body__contents { padding: 0.5em; } 
                .desktop-only { display:none; }
                .mobile-only { display: inline-block; }
                .total{position:-webkit-sticky; position:sticky; bottom:0; width:100%;}
                .logo { margin:0 auto; width:25%;}
                .info-area { width: 100%; font-size:0.8em; }
            }
            .splide__slide img {
                width: 100%;
                height: auto;
            }
            
            
        </style>
    </head>
    
    <body>
    <div class="logo"><img src="sga-logo.jpg"></div>
    <div class="info-area">
        <b>Quote prepared for:</b> The Perth Mint<br>
        <b>Sales Representative:</b> Mark Dimmock<br>
        <b>Contact Number:</b> +61 498 166 167<br>
        <b>Contact Email:</b> <a href="mailto:mark@samsgroup.com.au">mark@samsgroup.com.au</a><br>
        <b>Date:</b> Friday, 5 April 2024<br>
    </div>
    <?php     
        foreach($sku as $v)
        { 
            $query = " SELECT * from pim WHERE SKU = '".$v."'";
            $result = mysqli_query($con, $query) or die(mysqli_error($con));
            while($row = mysqli_fetch_assoc($result)){
            $count = $count+1;
            $total = $total + $row[stone_price_wholesale_aud];
    ?>
    <div class="accordion js-accordion">
        <div class="accordion__item js-accordion-item">
            <div class="accordion-header js-accordion-header">
                <div class="desktop-only"><?php echo "<b>".$v."</b>" . " - " . $row[product_title]; ?></div>
                <div class="mobile-only"><?php echo "<span style='font-weight:700; font-size:1.5em;'>".$v."</span>" . "<br><span style='font-size:0.5em;'>" . $row[product_title] . "</span>"; ?></div>
            </div> 
            <div class="accordion-body js-accordion-body">
                <div class="accordion-body__contents">
                    <div class="imgcontainer">
                    <section id="image-carousel<?php echo $count;?>" class="splide">
                        <div class="splide__track">
                            <ul class="splide__list">
                                <?php
                                    $image=false; 
                                    $imagearray= array("image1", "image2", "image3", "image4", "image5", "image6", "packaging_image");
                                    for ($i = 0; $i < count($imagearray); $i++) {
                                        $imagename = $imagearray[$i];
                                        if($row[$imagename] != ""){
                                            $image=true;
                                            echo '<li class="splide__slide"><a href="'.$row[$imagename].'" class="glightbox"><img src="'.$row[$imagename].'" alt=""></a></li>';
                                        }
                                    }
                                ?>
                                <li class="splide__slide">
                                    <div id="sirv360" class="col-md-3 d-none" brand="<?php  echo $row[brand];  ?>" sku="<?php  echo $v;  ?>">
                                        <div class="img-border">
                                            <div class="sirv-container"></div>
                                                <div class="sirv-controls ">
                                                <a onclick="Sirv.instance('sirv-spin').play(-1); return false;" href="#" class="button flaticon-keyboard54" title="Left"></a>
                                                <a id="pause-button-sirv-spin" onclick="Sirv.instance('sirv-spin').pause(); return false;" href="#" class="button flaticon-pause44" title="Pause"></a>
                                                <a id="play-button-sirv-spin" onclick="Sirv.instance('sirv-spin').play(); return false;" href="#" class="button flaticon-play106" title="Play"></a>
                                                <a onclick="Sirv.instance('sirv-spin').play(1); return false;" href="#" class="button flaticon-keyboard53" title="Right"></a>
                                                <a onclick="Sirv.instance('sirv-spin').zoomIn(); return false;" href="#" class="button flaticon-round57" title="Zoom In"></a>
                                                <a onclick="Sirv.instance('sirv-spin').zoomOut(); return false;" href="#" class="button flaticon-round56" title="Zoom Out"></a>
                                                <a onclick="Sirv.instance('sirv-spin').fullscreen('sirv-spin'); return false;" href="#" class="button flaticon-move26" title="Full Screen"></a>
                                                <div class="clear"></div>
                                            </div>  
                                            <aside class="title">Spin animation</aside>   
                                        </div>    
                                    </div>  
                                    </div>
                                    <link rel="stylesheet" href="https://demo.sirv.com/sirv-controls/sirv-controls.css">
                                    <script src="https://scripts.sirv.com/sirv.js"></script>
                                </li>
                            </ul>
                        </div>
                    </section>
                    </div>
                    <table class="sga-table producttable">
                        <thead><tr><td colspan="1000">Details</td></tr></thead>
                        <tbody>
                        <tr><td class="l"> SKU: </td> <td><?php echo $v; ?></td></tr>
                        <tr><td class="l"> Specifications: </td> <td><?php echo $row[specifications]; ?></td></tr>
                        <tr><td class="l"> Wholesale PPC ex GST: </td> <td>AU$ <?php echo number_format($row[wholesale_aud], 2,'.',','); ?></td></tr>
                        <tr><td class="l"> Stone Price ex GST: </td> <td>AU$ <?php echo number_format($row[stone_price_wholesale_aud], 2,'.',','); ?></td></tr>
                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php
        }
    }
?>
    <footer class="total">
        <?php echo $count . " items quoted. Total Value: <b>AU$" . number_format($total, 2,'.',','). "</b> excluding GST."; ?>
    </footer>


    <div class="info-area">
       <b>Please note:<b> Prices listed are only active for the next 3 business days. Other details, images and specifications may change at any time.
    </div>


<script>
//accordion
  var accordion = (function(){
  
  var $accordion = $('.js-accordion');
  var $accordion_header = $accordion.find('.js-accordion-header');
  var $accordion_item = $('.js-accordion-item');
 
  // default settings 
  var settings = {
    // animation speed
    speed: 400,
    
    // close all other accordion items if true
    oneOpen: false
  };
    
  return {
    // pass configurable object literal
    init: function($settings) {
      $accordion_header.on('click', function() {
        accordion.toggle($(this));
      });
      
      $.extend(settings, $settings); 
      
      // ensure only one accordion is active if oneOpen is true
      if(settings.oneOpen && $('.js-accordion-item.active').length > 1) {
        $('.js-accordion-item.active:not(:first)').removeClass('active');
      }
      
      // reveal the active accordion bodies
      $('.js-accordion-item.active').find('> .js-accordion-body').show();
    },
    toggle: function($this) {
            
      if(settings.oneOpen && $this[0] != $this.closest('.js-accordion').find('> .js-accordion-item.active > .js-accordion-header')[0]) {
        $this.closest('.js-accordion')
               .find('> .js-accordion-item') 
               .removeClass('active')
               .find('.js-accordion-body')
               .slideUp()
      }
      
      // show/hide the clicked accordion item
      $this.closest('.js-accordion-item').toggleClass('active');
      $this.next().stop().slideToggle(settings.speed);
    }
  }
})();

$(document).ready(function(){
  accordion.init({ speed: 300, oneOpen: true });
});
</script>
    <?php 
        for ($i = 1; $i <= $count; $i++) {
            echo "<script>new Splide('#image-carousel".$i."', {rewind: true, type: 'fade', }).mount();</script>";
        }
    ?>

<script type="text/javascript">
  const lightbox = GLightbox({ touchNavigation: true, });
</script>
