<div id="wp_impact_div">
    <div class="container-fluid">
        <?php foreach($items as $item){
            $date = date('F d', strtotime($item->ExpirationDate));
            $date2 = date('D', strtotime($item->ExpirationDate));
            $date3 = date('h:i a', strtotime($item->ExpirationDate));
            $url = '?cat='.$item->CatalogId.'&ticket='.$item->Id;
        ?>
        <div class="row impact_div">
            <div class="col-md-2 col-sm-12">
                <h4><?php echo $date; ?></h4>
                <h5><?php echo strtoupper($date2) .' '. $date3; ?></h5>
            </div>

            <div class="col-md-7 col-sm-12 border-left">
                <a rel="nofollow" class="impact_title" href="<?php echo $url; ?>"><?php echo $item->Name; ?></a>
                <a rel="nofollow" class="impact_subtitle" href="<?php echo $url; ?>&venue=1"><?php echo $item->Text1; ?></a>
                    - 
                <a rel="nofollow" class="impact_subtitle" href="<?php echo $url; ?>&venue=1"><?php echo $item->Text2; ?></a>
            </div>

            <div class="col-md-2 col-sm-12 col-md-offset-1">
                <a class="impact_button" rel="nofollow" href="<?php echo $url; ?>">$<?php echo $item->CurrentPrice; ?></a>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
