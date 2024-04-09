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
            <div class="col-md-3" >
                <div class="img-border">
                    <sirvspin sku="<?php  echo $sku;  ?>" brand="<?php  echo $brand;  ?>" :multispins="false"></sirvspin>
                    <aside class="title">Spin animation</aside>   
                </div>    
            </div>  
        </div>


      <div class="showing-noimg <?php if($image){ echo 'd-none';}?>">No Media or Images</div>
    </p>

    <script>
        function copyText(link) {
     
            /* Copy text into clipboard */
            navigator.clipboard.writeText(link);
        }
    </script>
</section>