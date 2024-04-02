<section id="content3" class="media-content">
    <p class="product-content">
        <div class="row">
        <?php 
        $image=false; 
        $imagearray= array("image1", "image2", "image3", "image4", "image5", "image6", "packaging_image");
        for ($i = 0; $i < count($imagearray); $i++) {
            $imagename = $imagearray[$i];
            if($row[$imagename] != ""){
                $image=true;
                echo '<div class="col-md-3"><div class="img-border"><img src="'.$row[$imagename].'"><aside class="title">Product '.$imagename.'&nbsp;&nbsp;&nbsp;<button onclick="copyText(\''.$row[$imagename].'\')"><i class="fa-solid fa-copy"></i></button></aside></div></div>';
            }
        }

        ?>
            <div id="sirv360" class="col-md-3 d-none" brand="<?php  echo $brand;  ?>" sku="<?php  echo $sku;  ?>">
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

      <div class="showing-noimg <?php if($image){ echo 'd-none';}?>">No Media or Images</div>
    </p>

    <script>
        function copyText(link) {
     
            /* Copy text into clipboard */
            navigator.clipboard.writeText(link);
        }
    </script>
</section>