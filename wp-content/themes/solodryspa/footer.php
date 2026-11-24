</main>
<footer class="container">
    <div class="h2">Find us in Newburyport.</div>
    <div class="solo-section-content">
        <div class="row location-info">
            <div class="col-md-6">
                <ul class="contact-info">
                    <!--
                    <li class="address">58 Merrimac St, Unit 1-1<br>Newburyport, MA 01950</li>
                    <li class="phone"><a href="tel:978-629-7656">(978) 629-7656 <span>(629-SOLO)</span></a></li>
                    <li class="directions"><a href="https://www.google.com/maps/dir//58+Merrimac+St,+Newburyport,+MA+01950/@42.8129464,-70.8756083,19z/data=!4m9!4m8!1m0!1m5!1m1!1s0x89e2e10820f4ebcd:0xf7242a84d6d7617c!2m2!1d-70.8732179!2d42.8124166!3e0?entry=ttu&g_ep=EgoyMDI2MTExNS4wIKXMDSoASAFQAw%3D%3D" target="_blank">Get Directions</a></li>
                    -->
                    <?php
                    $location = get_field('location', 'option');
                    if($location != '') {
                        echo '<li class="address">'.$location.'</li>';
                    }
                    $phone = get_field('phone', 'option');
                    if($phone != '') {
                        echo '<li class="phone"><a href="'.$phone['url'].'" title="Call us at '.$phone['title'].'">'.$phone['title'].'</a></li>';
                    }
                    $directions = get_field('directions', 'option');
                    if($directions != '') {
                        echo '<li class="directions"><a href="'.$directions['url'].'" title="'.$directions['title'].'">'.$directions['title'].'</a></li>';
                    }
                    ?>
                </ul>
            </div>
            <!--<div class="col-md-6 map-container">
                <img src="<?php echo THEME_URI; ?>assets/img/location.jpg" alt="SOLO Dry Spa location" width="273" height="83" loading="lazy" />
            </div>-->
            <div class="col-md-6 location-hours">
                <?php
                $hours = array(
                    'monday' => 'Monday',
                    'tuesday' => 'Tuesday',
                    'wednesday' => 'Wednesday',
                    'thursday' => 'Thursday',
                    'friday' => 'Friday',
                    'saturday' => 'Saturday',
                    'sunday' => 'Sunday',
                    'additional_details' => '',
                );
                ?>
                <ul class="contact-info">
                    <?php foreach($hours as $key => $label) {
                        $value = get_field($key, 'option');
                        if($value != '') {
                            if($label != '') {
                                echo '<li>'.$label.': '.$value.'</li>';
                            } else {
                                echo '<li><em>'.$value.'</em></li>';
                            }
                        }
                    } ?>
                </ul>
            </div>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>