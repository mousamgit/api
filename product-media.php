<section id="content3">
    <p class="product-content">
        <?php $image=false; ?>
        <?php if($row[image1] != "") { ?>
            <table class="product-table media">
                <tr>
                    <td><img src="<?php echo $row[image1]; $image=true; ?>"></td>
                    <?php if($row[image2] != "") { ?><td><img src="<?php echo $row[image2]; $image=true; ?>"></td><?php } ?>
                    <?php if($row[image3] != "") { ?><td><img src="<?php echo $row[image3]; $image=true; ?>"></td><?php } ?>
                    <?php if($row[image4] != "") { ?><td><img src="<?php echo $row[image4]; $image=true; ?>"></td><?php } ?>
                    <?php if($row[image5] != "") { ?><td><img src="<?php echo $row[image5]; $image=true; ?>"></td><?php } ?>
                    <?php if($row[image6] != "") { ?><td><img src="<?php echo $row[image6]; $image=true; ?>"></td><?php } ?>
                    <?php if($row[packaging_image] != "") { ?><td><img src="<?php echo $row[packaging_image]; ?>"></td><?php } ?>
                </tr>
                <tr>
                    <td class="title">Main Product Image</td>
                    <?php if($row[image2] != "") { ?><td class="title">Product Image 2</td><?php } ?>
                    <?php if($row[image3] != "") { ?><td class="title">Product Image 3</td><?php } ?>
                    <?php if($row[image4] != "") { ?><td class="title">Product Image 4</td><?php } ?>
                    <?php if($row[image5] != "") { ?><td class="title">Product Image 5</td><?php } ?>
                    <?php if($row[image6] != "") { ?><td class="title">Product Image 6</td><?php } ?>
                    <?php if($row[packaging_image] != "") { ?><td class="title">Packaging</td><?php } ?>
                </tr>
            </table>
        <?php } else { ?>
            <table class="product-table media">
                <tr>
                    <?php if($row[image2] != "") { ?><td><img src="<?php echo $row[image2]; $image=true; ?>"></td><?php } ?>
                    <?php if($row[image3] != "") { ?><td><img src="<?php echo $row[image3]; $image=true; ?>"></td><?php } ?>
                    <?php if($row[image4] != "") { ?><td><img src="<?php echo $row[image4]; $image=true; ?>"></td><?php } ?>
                    <?php if($row[image5] != "") { ?><td><img src="<?php echo $row[image5]; $image=true; ?>"></td><?php } ?>
                    <?php if($row[image6] != "") { ?><td><img src="<?php echo $row[image6]; $image=true; ?>"></td><?php } ?>
                    <?php if($row[packaging_image] != "") { ?><td><img src="<?php echo $row[packaging_image]; ?>"></td><?php } ?>
                </tr>
                <tr>
                    <?php if($row[image2] != "") { ?><td class="title">Product Image 2</td><?php } ?>
                    <?php if($row[image3] != "") { ?><td class="title">Product Image 3</td><?php } ?>
                    <?php if($row[image4] != "") { ?><td class="title">Product Image 4</td><?php } ?>
                    <?php if($row[image5] != "") { ?><td class="title">Product Image 5</td><?php } ?>
                    <?php if($row[image6] != "") { ?><td class="title">Product Image 6</td><?php } ?>
                    <?php if($row[packaging_image] != "") { ?><td class="title">Packaging</td><?php } ?>
                </tr>
            </table>
        <?php } if($image == false) { echo "No Media or Images"; } ?>
    </p>
</section>