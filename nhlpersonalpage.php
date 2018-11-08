<?php
/*
Plugin Name: NHL ЛИЧНЫЕ СТРАНИЦЫ
Plugin URI: 
Description: система управления личными страницами пользователей
Version: 1.0
Author: Алексей Гукай
Author URI: http://vk.com/exxxar

Copyright 2018  Алексей Гукай  (email: exxxar@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

register_activation_hook( __FILE__, 'nhlpersonalpage_install' ); 
register_deactivation_hook( __FILE__, 'nhlpersonalpage_deactivation' );


add_action( 'admin_menu', 'register_nhl_personal_page_menu_page' );


function register_nhl_personal_page_menu_page(){
	add_menu_page( 
		'NHL PERSONAL PAGE MENU', 'NHL Управление пользователями', 'manage_options', 'nhlpersonalpage/index.php', '', '
        dashicons-admin-users', 4 
	);
}



add_action("wp_head", "nhl_add_styles");
//add_action("wp_footer", "mfp_Add_Text");
//add_action("wp_loaded", "mfp_Add_Text");
// Определяем 'mfp_Add_Text'
 function nhl_add_styles()
 {
	 //добавление стиля и скрипт вызова модального окна
	 //добавление самого модального окна

 }

 function themeslug_enqueue_style() {
	wp_enqueue_style( 'nhl_personal_page_style', plugins_url( '/css/style.css', __FILE__ ), false ); 
  }
  add_action( 'wp_enqueue_scripts', 'themeslug_enqueue_style' );


function nhlpersonalpage_install(){

	
	//таблицы: информация о пользователе, таблица уровней и коэффициентов, таблица истории платежей
	global $wpdb;
	$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$table_name = $wpdb->get_blog_prefix() . 'user_info';

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

		$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`userId` INT NULL,
			`name` VARCHAR(45) NULL,
			`hut_command` VARCHAR(255) NULL,
			`email` VARCHAR(255) NULL,
			`vk` VARCHAR(255) NULL,
			`fb` VARCHAR(255) NULL,
			`telegramm` VARCHAR(255) NULL,
			`viber` VARCHAR(255) NULL,
			`skype` VARCHAR(255) NULL,
			`whatsapp` VARCHAR(255) NULL,
			`payment_type` VARCHAR(45) NULL,
			`payment_card` VARCHAR(45) NULL,
			`exp` INT NULL,
			`img` VARCHAR(255) NULL,
			`referal` INT NULL,
			PRIMARY KEY (`id`)) {$charset_collate};";
		dbDelta( $sql );

		$tmp = $wpdb->get_results("SELECT `user_login`,`user_nicename`,`user_email`,`ID`  FROM `wp_users`");

		foreach($tmp as $u) {
			$userId = $u->ID;

			$name = $u->user_nicename;
            $hut_command = "";
            $email = "";
            $vk = "";
            $fb = "";
			$telegramm = "";
			$viber = "";
			$skype = "";
            $whatsapp = "";
            $payment_type = "";
            $payment_card = "";
            $exp = 0;
            $img = "";
			$referal = 0;
			
			$wpdb->query( "INSERT INTO `wp_user_info`( `userId`, `name`, `hut_command`, `email`, `vk`, `fb`, `telegramm`,`viber`,`skype`, `whatsapp`, `payment_type`, `payment_card`, `exp`, `img`, `referal`) VALUES ( '$userId', '$name', '$hut_command', '$email', '$vk', '$fb', '$telegramm','$viber','$skype', '$whatsapp', '$payment_type', '$payment_card', '$exp', '$img', '$referal')" );
		}
	
	}

	$table_name = $wpdb->get_blog_prefix() . 'levels';

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`level` INT NULL,
			`title` VARCHAR(45) NULL,
			`exp` INT NULL,
			`disc` DOUBLE NULL,
			PRIMARY KEY (`id`)) {$charset_collate};";
		dbDelta( $sql );
		

		$lvls = array("Бронзовый" , "Серебряный" , "Золотой", "Платиновый", "Карбоновый");
		$lvlNumber = 1;
		foreach($lvls as $lvlTitle) {

			$level = $lvlNumber;
			$title = $lvlTitle;
			$exp = $lvlNumber*5000;
			$disc = $lvlNumber;

			$wpdb->query("INSERT INTO `wp_levels`( `level`, `title`, `exp`, `disc`) VALUES ($level,'$title',$exp ,$disc)");

			$lvlNumber++;
		}
	}

	$table_name = $wpdb->get_blog_prefix() . 'pay_history';

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			`id` INT NOT NULL,
			`payment_type` VARCHAR(45) NULL,
			`money` DOUBLE NULL,
			`currency` VARCHAR(45) NULL,
			`exp` INT NULL,
			`date` DATETIME NULL,
			PRIMARY KEY (`id`)) {$charset_collate};";
		dbDelta( $sql );
	}
	
	$table_name = $wpdb->get_blog_prefix() . 'chat';

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			`id` INT NOT NULL AUTO_INCREMENT,
			`from_id` INT NOT NULL,
			`to_id` INT NOT NULL,
			`message` VARCHAR(255) NULL,
			`title` VARCHAR(45) NULL,
			`date` DATETIME NULL,
			PRIMARY KEY (`id`)) {$charset_collate};";
		dbDelta( $sql );
	}
	

	$table_name = $wpdb->get_blog_prefix() . 'promo';

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			`id` INT NOT NULL AUTO_INCREMENT,
			`promocode` VARCHAR(45) NULL,
			`date_start` DATE NULL,
			`date_exp` DATE NULL,
			`action_text` VARCHAR(255) NULL,
			`is_active` TINYINT NULL,
			`count` INT NOT NULL,
			`value` INT NOT NULL,
			PRIMARY KEY (`id`)) {$charset_collate};";
		dbDelta( $sql );
	}

	$table_name = $wpdb->get_blog_prefix() . 'user_has_promo';

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
		  	`id` INT NOT NULL AUTO_INCREMENT,
			`user_id` INT NULL,
			`promo_id` INT NULL,
			PRIMARY KEY (`id`)) {$charset_collate};";
		dbDelta( $sql );
	}

}



function nhlpersonalpage_deactivation() {
		 //добавление самого модального окна
		 global $wpdb; 
		$tables = array("pay_history","levels","user_info","promo","chat","user_has_promo");
		foreach($tables as $table) {
			$table_name = $wpdb->get_blog_prefix() . $table;
			$wpdb->query("DROP TABLE IF EXISTS $table_name");
		} 
		
}

function personal_cabinet( $atts ){
	return "
	<div class='content'>
	<div class='row'>
		<div class='col col-md-3'>

			<div class='photo-block'>
			
				<div class='photo no-photo'><img src='' alt=''></div>
				<a href='' class='take-photo'><i class='fas fa-camera'></i>Выбрать фотографию</a>
				<hr>
				<div class='level-block'>
					<div class='level-info'>
						<p class='level'><strong>10</strong>ур.</p>
						<p class='procent'>-18%</p>
					</div>

					<div class='expirence'>
						<p>7300 опыта</p>
						<div class='strip'></div>
					</div>
				</div>
			</div>

	   
<script type='text/javascript' src='https://vk.com/js/api/openapi.js?159'></script>

<!-- VK Widget -->
<div id='vk_groups'></div>
<script type='text/javascript'>
VK.Widgets.Group('vk_groups', {mode: 4, height: '525', color2: '333333', color3: '9E0A0A'}, 20003922);
</script>
		</div>
		<div class='col col-md-6'>
			
			<div class='personal-info-block'>
				<div class='modify'><a href=''>Редактировать</a></div>
				<p class='tname'>Хокеев</p>
				<p class='fname'>Игорь</p>
				<p class='sname'>Эдуардович</p>
				<hr>
				<table>
					<tr>
						<td>E-mail</td>
						<td><a href=''>hokkey.igor.eduardovich@yandex.ru</a></td>
						<td></td>
					</tr>
					<tr>
						<td>Skype</td>
						<td><a href=''>Hokkey_igorevich</a></td>
						<td></td>
					</tr>
					<tr>
						<td>Способ оплаты</td>
						<td><a href=''>Яндекс.деньги</a></td>
						<td><a href=''>4100<span></span>0012</a></td>
					</tr>
				</table>
				<hr>
				<div class='table-container'>
					<table>
						<thead>
							<th>Id</th>
							<th>Способ оплаты</th>
							<th>Сумма</th>
							<th>Баллы</th>
						</thead>
					</table>
					<div class='tbody-container'>
						<table>


							<tbody>
								<tr>
									<td>
										<p>58328355577891</p>
									</td>
									<td>
										<p>Банковская карта</p>
									</td>
									<td>
										<p>127.00p</p>
									</td>
									<td>
										<p>127</p>
									</td>
								</tr>
								 <tr>
									<td>
										<p>58328355577891</p>
									</td>
									<td>
										<p>Банковская карта</p>
									</td>
									<td>
										<p>127.00p</p>
									</td>
									<td>
										<p>127</p>
									</td>
								</tr>
								 <tr>
									<td>
										<p>58328355577891</p>
									</td>
									<td>
										<p>Банковская карта</p>
									</td>
									<td>
										<p>127.00p</p>
									</td>
									<td>
										<p>127</p>
									</td>
								</tr>
								 <tr>
									<td>
										<p>58328355577891</p>
									</td>
									<td>
										<p>Банковская карта</p>
									</td>
									<td>
										<p>127.00p</p>
									</td>
									<td>
										<p>127</p>
									</td>
								</tr>
								 <tr>
									<td>
										<p>58328355577891</p>
									</td>
									<td>
										<p>Банковская карта</p>
									</td>
									<td>
										<p>127.00p</p>
									</td>
									<td>
										<p>127</p>
									</td>
								</tr>
								 <tr>
									<td>
										<p>58328355577891</p>
									</td>
									<td>
										<p>Банковская карта</p>
									</td>
									<td>
										<p>127.00p</p>
									</td>
									<td>
										<p>127</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>58328355577891</p>
									</td>
									<td>
										<p>Банковская карта</p>
									</td>
									<td>
										<p>127.00p</p>
									</td>
									<td>
										<p>127</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>58328355577891</p>
									</td>
									<td>
										<p>Банковская карта</p>
									</td>
									<td>
										<p>127.00p</p>
									</td>
									<td>
										<p>127</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>58328355577891</p>
									</td>
									<td>
										<p>Банковская карта</p>
									</td>
									<td>
										<p>127.00p</p>
									</td>
									<td>
										<p>127</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>58328355577891</p>
									</td>
									<td>
										<p>Банковская карта</p>
									</td>
									<td>
										<p>127.00p</p>
									</td>
									<td>
										<p>127</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>58328355577891</p>
									</td>
									<td>
										<p>Банковская карта</p>
									</td>
									<td>
										<p>127.00p</p>
									</td>
									<td>
										<p>127</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>58328355577891</p>
									</td>
									<td>
										<p>Банковская карта</p>
									</td>
									<td>
										<p>127.00p</p>
									</td>
									<td>
										<p>127</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>58328355577891</p>
									</td>
									<td>
										<p>Банковская карта</p>
									</td>
									<td>
										<p>127.00p</p>
									</td>
									<td>
										<p>127</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>58328355577891</p>
									</td>
									<td>
										<p>Банковская карта</p>
									</td>
									<td>
										<p>127.00p</p>
									</td>
									<td>
										<p>127</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>58328355577891</p>
									</td>
									<td>
										<p>Банковская карта</p>
									</td>
									<td>
										<p>127.00p</p>
									</td>
									<td>
										<p>127</p>
									</td>
								</tr>
							</tbody>

						</table>
					</div>
				</div>
			</div>
			
			<div class='chat'>
				<div class='message-window'>
					<ul>
						<li class='qst'>
							<p class='time'>18:30:25 10.02.2018 <strong>Вопрос</strong></p>
							<p class='message'>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere nesciunt ipsum, rem perferendis, porro cum saepe quasi reprehenderit temporibus doloremque at atque cumque voluptas pariatur officia neque suscipit accusantium necessitatibus!</p>
						</li>
						
						<li class='ask'>
							<p class='time'>18:30:25 10.02.2018 <strong>Ответ</strong></p>
							<p class='message'>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere nesciunt ipsum, rem perferendis, porro cum saepe quasi reprehenderit temporibus doloremque at atque cumque voluptas pariatur officia neque suscipit accusantium necessitatibus!</p>
						</li>
						
						 <li class='ask'>
							<p class='time'>18:30:25 10.02.2018 <strong>Ответ</strong></p>
							<p class='message'>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere nesciunt ipsum, rem perferendis, porro cum saepe quasi reprehenderit temporibus doloremque at atque cumque voluptas pariatur officia neque suscipit accusantium necessitatibus!</p>
						</li>
					</ul>
				</div>
				<div class='reply-window'>
					<form action=''>
						<textarea name='' id='' placeholder='Текст сообщения' maxlength='255'></textarea>
						<button><i class='far fa-envelope'></i>Отправить</button>
					</form>
				</div>
			</div>
		</div>
		<div class='col col-md-3'>

			<div class='slider'>
				<div><img src='cards/1.png' alt=''><div class='bye-block'><button class='buy'>Купить</button></div></div>
				<div><img src='cards/1.png' alt=''><div class='bye-block'><button class='buy'>Купить</button></div></div>
				<div><img src='cards/1.png' alt=''><div class='bye-block'><button class='buy'>Купить</button></div></div>
				<div><img src='cards/1.png' alt=''><div class='bye-block'><button class='buy'>Купить</button></div></div>
				<div><img src='cards/1.png' alt=''><div class='bye-block'><button class='buy'>Купить</button></div></div>
				<div><img src='cards/1.png' alt=''><div class='bye-block'><button class='buy'>Купить</button></div></div>
				<div><img src='cards/1.png' alt=''><div class='bye-block'><button class='buy'>Купить</button></div></div>
				<div><img src='cards/1.png' alt=''><div class='bye-block'><button class='buy'>Купить</button></div></div>
				<div><img src='cards/1.png' alt=''><div class='bye-block'><button class='buy'>Купить</button></div></div>
				<div><img src='cards/1.png' alt=''><div class='bye-block'><button class='buy'>Купить</button></div></div>
				<div><img src='cards/1.png' alt=''><div class='bye-block'><button class='buy'>Купить</button></div></div>
				<div><img src='cards/1.png' alt=''><div class='bye-block'><button class='buy'>Купить</button></div></div>
				<div><img src='cards/1.png' alt=''><div class='bye-block'><button class='buy'>Купить</button></div></div>
			   
				
			</div>
			
			<div class='slider-arrow-block'>
				<div class='arrow-left'><i class='fas fa-angle-left'></i></div>
				<div class='arrow-info'><p>1/15</p></div>
				<div class='arrow-right'><i class='fas fa-angle-right'></i></div>
			</div>
		</div>
	</div>


</div>
	";
	}

add_shortcode( 'wpum_profile', 'personal_cabinet' );

function enterPromocode(){
	$url =  plugin_dir_url( __FILE__ ).'promocallback.php';
	return "
	<form action='$url' method=\"post\" class=\"promo-form\">
		<input type=\"hidden\" name=\"option\" value=\"accept\">		
		<label for=\"promocode\">Промокод</label>	
			<input placeholder=\"Введите промокод!\" id=\"promocode\" name=\"promocode\" type=\"text\">
			

			<input type=\"submit\" value=\"Отправить\">						
				
			
		</form>
			";
}
add_shortcode("promocode",'enterPromocode');


 
function misha_render_login() {
 
	// проверяем, если пользователь уже авторизован, то выводим соответствующее сообщение и ссылку "Выйти"
	if ( is_user_logged_in() ) {
		return sprintf( "Вы уже авторизованы на сайте. <a href='%s'>Выйти</a>.", wp_logout_url() );
	}
 
	// присваиваем содержимое формы переменной и затем возвращаем её, выводить через echo() мы не можем, так как это шорткод
	$return = '<div class="login-form-container"><h2>Войти на сайт</h2>';
 
	// если возникли какие-либо ошибки, отображаем их
	if ( isset( $_REQUEST['errno'] ) ) {
		$error_codes = explode( ',', $_REQUEST['errno'] );
 
		foreach ( $error_codes as $error_code ) {
			switch ( $error_code ) {
				case 'empty_username':
					$return .= '<p class="errno">Вы не забыли указать свой email/имя пользователя?</p>';
					break;
				case 'empty_password':
					$return .= '<p class="errno">Пожалуйста, введите пароль.</p>';
					break;
				case 'invalid_username':
					$return .= '<p class="errno">На сайте не найдено указанного пользователя.</p>';
					break;
				case 'incorrect_password':
					$return .= sprintf( "<p class='errno'>Неверный пароль. <a href='%s'>Забыли</a>?</p>", wp_lostpassword_url() );
					break;
				case 'confirm':
					$return .= '<p class="errno success">Инструкции по сбросу пароля отправлены на ваш email.</p>';
					break;
				case 'changed':
					$return .= '<p class="errno success">Пароль успешно изменён.</p>';
					break;
				case 'expiredkey':
				case 'invalidkey':
					$retun .= '<p class="errno">Недействительный ключ.</p>';
					break;
			}
		}
	}
 
	// используем wp_login_form() для вывода формы (но можете сделать это и на чистом HTML)
	$return .= wp_login_form(
		array(
			'echo' => false, // не выводим, а возвращаем
			'redirect' => site_url('/account/'), // куда редиректить пользователя после входа
		)
	);
 
	$return .= '<a class="forgot-password" href="' . wp_lostpassword_url() . '">Забыли пароль</a></div>';
 
	// и наконец возвращаем всё, что получилось
	return $return;
 
}
add_shortcode( 'misha_custom_login', 'misha_render_login' );