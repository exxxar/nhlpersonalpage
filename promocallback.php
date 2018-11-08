
<?php
  require_once("../../../wp-load.php");

    get_header();

?>

	<div class="col-lg-8 col-lg-offset-0 col-md-9 col-md-offset-3 col-sm-9 col-xs-12 b_main b_whitefont">
                    <div class="scroll">
    <?php


    function acceptPromo() {
        global $wpdb;
        if (!isset($_POST['promocode']))
            return "Промокод не найден!";

        $promocode = $_POST['promocode'];
        $cur_user_id = get_current_user_id();

      
        $tmp = $wpdb->get_results("SELECT * FROM `wp_promo` WHERE `promocode`='$promocode' and `count`>=1");
     
       $value = $tmp[0]->value;

        if (count($tmp)==0) {
            $result = "Таких промокодов нет!";
            return $result;
        }

        $promo_id = $tmp[0]->id;
        $count = $tmp[0]->count;

        $tmp = $wpdb->get_results("SELECT * FROM `wp_user_has_promo` WHERE `user_id`='$cur_user_id' and  `promo_id`='$promo_id'");

        if (count($tmp)!=0) {
            $result = "Такой промокод уже использован вами!";
            return $result;
        }

        $count--;
        $wpdb->query( "UPDATE `wp_promo` SET `count`='$count' WHERE `id`=$promo_id" );

        $result = $wpdb->query( "INSERT INTO `wp_user_has_promo`( `user_id`, `promo_id`) VALUES ( '$cur_user_id', '$promo_id')" );

        return "Спасибо! Ваш промокод принят! Вы получаете бонус скидки в размере $value%";
    }

   
    ?>
 
    <p class="error-message"><?php echo acceptPromo();?></p>
<div class="flex-container">
<a href="/" class="btn trans">Вернуться на главную<img src="<?php echo get_template_directory_uri();?>/assets/images/arr-right.png" alt=""></a>
    
<a href="/promo" class="btn trans">Ввести другой промокод<img src="<?php echo get_template_directory_uri();?>/assets/images/arr-right.png" alt=""></a>
</div>

  
			</div>
		</div>
	</div>
</div>
<?php
// get_sidebar();
get_footer();
