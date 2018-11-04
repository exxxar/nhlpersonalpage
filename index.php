<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <title>Document</title>
</head>
<style>
    thead > th {
        text-transform:uppercase;
    }

    .to-clipboard {
      text-decoration:underline;
      text-decoration-style:dashed;
    }
    .promo-list,
    .user-history,
    .user-list {
        width:100%;
        height:300px;
        box-sizing:border-box;
        padding:10px;
        overflow-y:scroll;
    } 

    .user-list table {
      width:2000px;
    }
 
    a {
      cursor:pointer;
    }

    .tooltip {
    position: relative;
    display: inline-block;
}

.tooltip .tooltiptext {
    visibility: hidden;
    width: 140px;
    background-color: #555;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px;
    position: absolute;
    z-index: 1;
    bottom: 150%;
    left: 50%;
    margin-left: -75px;
    opacity: 0;
    transition: opacity 0.3s;
}

.tooltip .tooltiptext::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #555 transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}
</style>
<body>
    
    <h3>Список пользователей</h3>
    <a class="btn-floating btn-large waves-effect waves-light red modal-trigger" href="#userModal"><i class="material-icons">add</i></a>
    <div class="user-list">
        <table>
            <thead>
                <th>Имя</th>                
                <th>Название команды HUT</th>
                <th>E-email</th>
                <th>Vkontakte</th>
                <th>Facebook</th>
                <th>Telegram</th>
                <th>Viber</th>
                <th>WatsUP</th>
                <th>Skype</th>
                <th>Способ оплаты</th>
                <th>Номер карты</th>
                <th>Бонусные баллы</th>
                <th>Аватар</th>
                <th>Реферал</th>            
            </thead>
            <tbody>
                <?php 
                  global $wpdb;
                  $query = $wpdb->get_results("SELECT * FROM `wp_user_info`");

                  foreach($query as $q) {                
                    ?>
                <tr>
                    <td> <a class="load-user-info-data" data-id="<?php echo $q->id;?>"><?php echo $q->name;?></a></td>
                    <td><?php echo $q->hut_command;?></td>
                    <td><?php echo $q->email;?></td>
                    <td><?php echo $q->vk;?></td>
                    <td><?php echo $q->fb;?></td>
                    <td><?php echo $q->telegramm;?></td>                    
                    <td><?php echo $q->viber;?></td>
                    <td><?php echo $q->whatsapp;?></td>
                    <td><?php echo $q->skype;?></td>
                    <td><?php echo $q->payment_type;?></td>
                    <td><?php echo $q->payment_card;?></td>
                    <td><?php echo $q->exp;?></td>
                    <td><?php echo $q->img;?></td>
                    <td><?php echo $q->referal;?></td>               
                </tr>
                <?php
                }
                ?>
            </tbody>

        </table>
    </div> 
    <hr>
    <h3>Таблица промокодов</h3>
    <div class="row">
      <div class="col s2"> <a  class="waves-effect waves-light btn-large btn-promocode-all">Все промокоды</a></div>
      <div class="col s2"><a  class="waves-effect waves-light btn-large btn-promocode-active">Активные промокоды</a></div>
      <div class="col s2"><a class="btn-floating btn-large waves-effect waves-light red modal-trigger add-new-promo" href="#promoModal"><i class="material-icons">add</i></a></div>
    </div>
   
    
    
    <div class="promo-list">
    <?php 
        global $wpdb;
         //echo  substr(sha1(rand(0,getrandmax())),rand(0,24),16);         
        if (!isset($_GET['promo'])||$_GET["promo"]!="all") {
          $query = $wpdb->get_results("SELECT * FROM `wp_promo` WHERE `is_active`='1'");
        }
        else
          $query = $wpdb->get_results("SELECT * FROM `wp_promo`");
          
       
          if (count($query)>0) {
          ?>

      <table>
        <thead>
          <th>Промокод</th>
          <th>Дата начала</th>
          <th>Дата окончания</th>
          <th>Описание промокода</th>
          <th>Активен</th>
          <th>Доступное колличество</th>
          <th>Величина скидки</th>
          <th>Действие</th>
        </thead>
        <tbody>
      
            <?php
                 
                  foreach($query as $q) {                
                    ?>
                <tr>
                    <td>
              
                    <div class="tooltip">
                      <a class="to-clipboard"> 
                          <span class="tooltiptext">Копировать в буфер</span>
                          <?php echo $q->promocode;?>
                        </a>
                    </div> 
                    
                    </td>     
                    <td><?php echo $q->date_start;?></td>    
                    <td><?php echo $q->date_exp;?></td>  
                    <td><?php echo $q->action_text;?></td> 
                    <td><?php echo $q->is_active==1?"Активен":"Не активен";?></td>  
                    <td> <div class="col s2"><a data-id="<?php echo $q->id;?>" class="user-on-promo modal-trigger" href="#userOnPromo"> <?php echo $q->count;?></a></div>
                   </td>  
                   <td><?php echo $q->value;?></td> 
                    <td><a data-id="<?php echo $q->id;?>" class="modify-promo" >Редактировать</a></td>          
                </tr>
                <?php
                }
              }
              else
                  echo "<p>К сожалению промокодов нет:(</p>"
                ?>
            </tbody>
      </tbody>
    </table>
    </div>
    <hr>
    <h3>Таблица опыта</h3>
    <a class="btn-floating btn-large waves-effect waves-light red modal-trigger" href="#addLevel"><i class="material-icons">add</i></a>
    <form action="" class="levels">
    <table>
      <thead>
        <th>Уровень</th>
        <th>Название</th>
        <th>Бонусные баллы</th>
        <th>Скидка</th>
        <th>Действия</th>
      </thead>
      <tbody>

   

       <?php 
        
        $query = $wpdb->get_results("SELECT * FROM `wp_levels`");

        
        foreach($query as $q) {

  
            ?>
        <tr name="level_tr_<?php echo $q->id;?>">
          <td>
            <div class="input-field"><input type="text" data-id="<?php echo $q->id;?>" value="<?php echo $q->level;?>" name="level<?php echo $q->id;?>"><label for="level<?php echo $q->id;?>">Введите уровень</label></div>
          </td>
          <td>
            <div class="input-field"><input type="text" data-id="<?php echo $q->id;?>" value="<?php echo $q->title;?>" name="title<?php echo $q->id;?>"><label for="name<?php echo $q->id;?>">Введите название уровня</label></div>
          </td>
          <td>
            <div class="input-field"><input type="text" data-id="<?php echo $q->id;?>" value="<?php echo  $q->exp;?>" name="exp<?php echo $q->id;?>"><label for="exp<?php echo $q->id;?>">Введите бонусные баллы</label></div>
          </td>
          <td>
            <div class="input-field"><input type="text" data-id="<?php echo $q->id;?>" value="<?php echo  $q->disc;?>" name="disc<?php echo $q->id;?>"><label for="dis<?php echo $q->id;?>">Введите скидку,%</label></div>
          </td>
          <td>  <a data-id="<?php echo $q->id;?>" class="btn-floating btn-large waves-effect waves-light red btn-remove-level"><i class="material-icons">clear</i></a></td>
        </tr>
        <?php
        }
        ?>
        
   
      </tbody>
      
    </table>

    </form>
   <!-- Modal Structure -->
  <div id="addLevel" class="modal">
    <div class="modal-content">
      <h4>Добавление уровня</h4>
      <form id="add-level-form">   
          <input type="hidden" name="option" value="insert">     
          <table>
            <thead>
              <th>Уровень</th>
              <th>Название</th>
              <th>Бонусные баллы</th>
              <th>Скидка</th>
            </thead>
          <tbody>
            <tbody>
              <tr>
                <td>
                  <div class="input-field"><input type="text"  value="0" name="level"><label for="level">Введите уровень</label></div>
                </td>
                <td>
                  <div class="input-field"><input type="text"  value="Новый уровень" name="title"><label for="name">Введите название уровня</label></div>
                </td>
                <td>
                  <div class="input-field"><input type="text"  value="0" name="exp"><label for="exp">Введите бонусные баллы</label></div>
                </td>
                <td>
                  <div class="input-field"><input type="text"  value="0" name="disc"><label for="dis">Введите скидку,%</label></div>
                </td>
              </tr>
            </tbody>
          </table>
      </form>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close btn waves-effect waves-light btn-add-level">Добавить</a>
    </div>
  </div>
  
    <!-- Modal Structure -->
    <div id="userOnPromo" class="modal">
    <div class="modal-content">
      <h4>Список пользователей с промокодом</h4>
      <ul>
      
      </ul>
    </div>
  </div>

    <!-- Modal Structure -->
    <div id="promoModal" class="modal">
    <div class="modal-content">
      <h4>Добавление промокода</h4>
   
      <input type="hidden" name="promo_id" id="promo_id">
      <form id="add-promo-form">   
          <div class="row">
              <div class="input-field col s12">
                <input type="text" id="promo" name="promocode" value="" disabled>
                <input type="button" class="btn generate-promo" value="Генерировать">
                <label for="promo">Промокод</label>
              </div>                 
          </div>
          <div class="row">
              <div class="input-field col s6">
                <input type="text" class="datepicker" name="date_start" id="date_start" value="">
                <label for="date_start">Начало действия промокода</label>
              </div>  
              <div class="input-field col s6">
                <input type="text" class="datepicker" name="date_end" id="date_end" value="">
                <label for="date_end">Окончание действия промокода</label>
              </div>                
          </div>
          <div class="row">
              <div class="input-field col s12">
                <input type="text" id="action_text" name="action_text" value="">              
                <label for="action_text">Описание действия промокода</label>
              </div>                 
          </div>
          <div class="row">
              <div class="input-field col s12">
                <input type="number" min="0" id="count" name="count" value="">              
                <label for="count">Доступное колличество</label>
              </div>                 
          </div>
          <div class="row">
              <div class="input-field col s12">
                <input type="number" min="0" max=100 step="0.5" id="value" name="value" value="">              
                <label for="value">Величина скидки</label>
              </div>                 
          </div>
          <div class="row">
              <div class="input-field col s12">
                <select name="is_active" id="is_active">
                  <option value="0">Не активировано</option>
                  <option value="1">Активировано</option>
                </select>         
                <label for="is_active">Состояние активности промокода</label>
              </div>                 
          </div>
      </form>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close btn waves-effect waves-light btn-promocode">Добавить</a>
    </div>
  </div>

     <!-- Modal Structure -->
  <div id="userModal" class="modal">
    <div class="modal-content">
      <h4>Информация о пользователе</h4>
     

      <div class="row">
            <form class="col s12">
              <div class="row">
                <div class="input-field col s12">
                  <select name="assign_user" id="assign_user">
                    <option value="">exxxar</option>
                  </select>
                  <label for="assign_user">Связать с пользователем</label>
                </div>                
              </div>

              <div class="row">
                <div class="input-field col s6">
                  <input id="
                  "  type="text" class="validate">
                  <label for="first_name">Имя</label>
                </div>
                <div class="input-field col s6">
                  <input id="command_name" type="text" class="validate">
                  <label for="command_name">Название команды HUT</label>
                </div>
              </div>
              <div class="row">
              <div class="input-field col s6">
                  <input id="login" type="text" class="validate">
                  <label for="login">Логин</label>
                </div>

                <div class="input-field col s6">
                  <input id="password"  type="password" class="validate">
                  <label for="password">Пароль</label>
                </div>
               
              </div>
             
              <div class="row">
                  <div class="input-field col s12">
                    <input  id="email" type="text" class="validate">
                    <label for="email">Почта</label>
                  </div>
              </div>

              <div class="row">
                  <div class="input-field col s6">
                    <input  id="telegramm" type="text" class="validate">
                    <label for="telegramm">Telegram</label>
                  </div>
                  <div class="input-field col s6">
                    <input  id="skype" type="text" class="validate">
                    <label for="skype">Скайп</label>
                  </div>
              </div>

              <div class="row">
                  <div class="input-field col s6">
                    <input  id="facebook" type="text" class="validate">
                    <label for="facebook">Facebook</label>
                  </div>

                  <div class="input-field col s6">
                    <input  id="vkontakte" type="text" class="validate">
                    <label for="vkontakte">Vkontakte</label>
                  </div>
              </div>

              <div class="row">
                  <div class="input-field col s6">
                    <input  id="viber" type="text" class="validate">
                    <label for="viber">Viber</label>
                  </div>

                  <div class="input-field col s6">
                    <input  id="whatsapp" type="text" class="validate">
                    <label for="whatsapp">WatsUP</label>
                  </div>
              </div>

        

              <div class="row">
                  <div class="input-field col s6">
                    <input  id="experience" min="0"  type="number" class="validate">
                    <label for="experience">Бонусные баллы</label>
                  </div>

                   <div class="input-field col s6">
                    <input  id="discount" min="0"  type="number" class="validate">
                    <label for="discount">Скидка,%</label>
                  </div>
              </div>
              <div class="row">
                  <div class="input-field col s6">
                    <select  id="payment_type">
                        <option value="0">Банковская карта</option>
                        <option value="1">Агрегатор 1</option>
                        <option value="2">Агрегатор 2</option>
                    </select>
                    <label for="expirience">Система оплаты</label>
                  </div>

                   <div class="input-field col s6">
                    <input  id="bank_account"   type="text" >
                    <label for="bank_account">Номер счета</label>
                  </div>
              </div>
            </form>
            
          </div>
          <hr>
          <!-- История оплаты пользователя -->
          <h3>История оплаты</h3>
          <div class="user-history">
          <table>
            <thead>
                <th>ID</th>
                <th>Способ оплаты</th>
                <th>Сумма</th>
                <th>Баллы</th>                           
            </thead>
            <tbody>
            <?php 

                  for ($i=0;$i<20;$i++) {
                      ?>
                  <tr>
                      <td> 5857565543829980</td>
                      <td>Банковская карта</td>
                      <td>200.00 руб.</td>
                      <td>200</td>                    
                      
                  </tr>
                  <?php
                  }
                  ?>
            </tbody>
            </table>
          </div>
          <hr>
          <!-- Личные сообщения пользователю  -->
          <h3>Сообщение пользователю</h3>
          <div class="row">
            <form class="col s12">
              <div class="row">
                <div class="input-field col s6">
                    <input  id="title" type="text" class="validate">
                    <label for="title">Заголовок</label>
                  </div>
                  
              </div>
              <div class="row">
                <div class="input-field col s12">
                   <textarea name="message" id="message" class="materialize-textarea" data-length="250"></textarea>
                    <label for="message">Сообщение</label>
                  </div>                  
              </div>

              <div class="row">
                <button class="btn waves-effect waves-light" type="submit" name="action">Отправить</button>
              </div>

            </form>
          </div>        

    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close btn waves-effect waves-ligh red">Сохранить изменения</a>
    </div>
  </div>
  <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script> 
    <script>
     $(document).ready(function(){
        $('.modal').modal();
        $('select').formSelect();
        $('.datepicker').datepicker({
          format:'yyyy-mm-dd'
        });

        $(".to-clipboard").click(function(){

          var $temp = $("<input>");
          $("body").append($temp);
          $temp.val($(this).text()).select();
          document.execCommand("copy");
          $temp.remove();

          $(this).children(".tooltiptext").html("Скопировано!");
          setTimeout(() => {
            $(this).children(".tooltiptext").html("Копировать в буфер");
          }, 2000);
          
        });


        $(".generate-promo").click(function(){
          $.post("<?php echo plugin_dir_url( __FILE__ ).'promoHandler.php';?>",{
            option:"generatepromo"
          }).done(function( data ) {
            $("#promo").removeAttr("disabled");
            $("#promo").val(data);
            $("#promo").focus();
            $("#promo").attr({"disabled":"true"});
          });
        });

        $(".btn-promocode-active").click(function(){
          var path = "http://"+location.host +location.pathname +"?page=nhlpersonalpage/index.php&promo=active";
          console.log(path);
          window.location = path;
        });

        $(".btn-promocode-all").click(function(){
          var path = "http://"+location.host +location.pathname +"?page=nhlpersonalpage/index.php&promo=all";
          console.log(path);
          window.location = path;
        });

        $(".add-new-promo").click(function(){
          $("#promo_id").val("");
        });

        $(".modify-promo").click(function(){
          var _id = $(this).attr("data-id");
                
          $.post("<?php echo plugin_dir_url( __FILE__ ).'promoHandler.php';?>",{
            option:"get",
            id:_id
          }).done(function( data ) {
           
            var json = $.parseJSON( data )[0];
            console.log(json);
            $("#promo_id").val(json.id);
            $("#promo").val(json.promocode);
            $("#date_start").val(json.date_start);
            $("#date_end").val(json.date_exp);
            $("#action_text").val(json.action_text);
            $("#count").val(json.count);
            $("#is_active").val(json.is_active);
            $("#value").val(json.value);

            var instance = M.Modal.getInstance($("#promoModal"));
            instance.open();
            $("#date_start,#date_end,#action_text,#count,#value").focus();

            $("#promo").removeAttr("disabled");
            $("#promo").focus();
            $("#promo").attr({"disabled":"true"});
            
          });
        });

        $(".btn-promocode").click(function(){
          var promo_id =  $("#promo_id").val().trim();
          console.log(promo_id);
          var promocode = $("#promo").val().trim();
          var dateStart = $("#date_start").val().trim();
          var dateEnd = $("#date_end").val().trim();
          var actionText = $("#action_text").val().trim();
          var count = $("#count").val().trim();
          var isActive = $("#is_active").val();
          var value = $("#value").val();

          console.log("isActive="+isActive);
          if (promocode.length==0||
              dateStart.length==0||
              dateEnd.length==0||
              actionText.lenght == 0||
              count.length==0 ||
              value.length==0
          )
          {
            alert("Не все поля заполнены!");
            return;
          }

          if (parseInt(count)<=0)
          {
            alert("Количество промокодов должно быть больше 0");
            return;
          }

          $.post("<?php echo plugin_dir_url( __FILE__ ).'promoHandler.php';?>",{
            option:"add",
            id:promo_id,
            promocode:promocode,
            date_start:dateStart,
            date_end:dateEnd,
            action_text:actionText,
            count:count,
            is_active:isActive,
            value:value
          }).done(function( data ) {
            console.log(data);
            location.reload();
          });
        });

        $(".levels input").blur(function(){

 
          var _id = $(this).attr("data-id");
          var _level = $(`.levels input[name='level${_id}']`).val();
          
          var _title = $(`.levels input[name='title${_id}']`).val();
          var _exp = $(`.levels input[name='exp${_id}']`).val();
          var _disc = $(`.levels input[name='disc${_id}']`).val();
          console.log(_level+" "+_title+" "+_exp+" "+_disc);
          $.post("<?php echo plugin_dir_url( __FILE__ ).'levelsHandler.php';?>",{
            option:"update",
            id:_id,
            level: _level, 
            title:_title,
            exp:_exp,
            disc:_disc

          }).done(function( data ) {
            var border = $(`.levels input[name*='${data}']`).css("border-bottom");
            $(`.levels input[name*='${data}']`).css({"border-bottom":"2px green solid"});
            setTimeout(function(){
              $(`.levels input[name*='${data}']`).css({"border-bottom":border});
            },5000);
          });
        });

        $(".btn-remove-level").click(function(){
          var _id = $(this).attr("data-id");
          $.post("<?php echo plugin_dir_url( __FILE__ ).'levelsHandler.php';?>",{
            id:_id,
            option:"remove"
          }).done(function(data){
            console.log(data);
              $(`[name='level_tr_${data}']`).css({"display":"none"});
          });
        });

        $(".btn-add-level").click(function(){
      
          $.post("<?php echo plugin_dir_url( __FILE__ ).'levelsHandler.php';?>",$("#add-level-form").serialize()).done(function(data){
            location.reload();
          });
        });

        $(".user-on-promo").click(function(){
          $("#userOnPromo .modal-content ul").html("");
          var _id = $(this).attr("data-id");

          $.post("<?php echo plugin_dir_url( __FILE__ ).'promoHandler.php';?>",{
              option:"usersonpromo",
              promo_id:_id
          }).done(function(data){
             var json = $.parseJSON( data )[0];
             for(var u in json){           
              $("#userOnPromo .modal-content ul").append("<li>"+json[u].user_nicename+" ("+json[u].user_email+")</li>");
             }
            
          });
        });
        $(".load-user-info-data").click(function(){
          var _id = $(this).attr("data-id");
     
          $.post("<?php echo plugin_dir_url( __FILE__ ).'usersHandler.php';?>",{
              option:"getuser",
              id:_id
          }).done(function(data){
              var userInfo = $.parseJSON( data )[0];
              $("#first_name").val(userInfo.name);
              $("#command_name").val(userInfo.hut_command);
              $("#login").val("");
              $("#password").val("");
              $("#email").val(userInfo.email);
              $("#telegramm").val(userInfo.telegramm);
              $("#skype").val(userInfo.skype);
              $("#facebook").val(userInfo.fb);
              $("#vkontakte").val(userInfo.vk);
              $("#experience").val(userInfo.exp);
              $("#discount").val("");
              $("#bank_account").val("");

               
              var instance = M.Modal.getInstance(document.getElementById("userModal"));
              instance.open();
              $("#command_name,#login,#password,#email,#telegramm,#skype,#facebook,#vkontakte,#experience,#first_name").focus();

          });
        });
    });
  </script>
</body>
</html>