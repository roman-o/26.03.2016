<?php

 session_start();
 if ($_SESSION['user'] != 'logged'){
	 header("location: /login.php");
 }
error_reporting(E_ALL);

include 'config.php';
include 'lib.php';

if ($_POST){
    if (isset($_POST['id'])) {
		$a = "UPDATE";	
    } else {
		$a = "INSERT";  
    }
UPDATE_INSERT ($a,$link);
}
if (isset($_GET['order_by_price'])){$sq = order_by_price();}
elseif (isset($_GET['order_by_alf'])){$sq = order_by_alf();}
elseif (isset($_GET['order_by_id'])){$sq = order_by_id();}
else {$sq = "  ORDER BY id DESC ";}
$items = SELECT ($link,$sq);
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <title>index.php</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="css/style.css" rel="stylesheet">
   </head>

  <body>
     <div class="container">
         <div class="row">
   	        <div class="col-lg-9 col-sm-8">
			</div>
	        <div class="col-lg-3 col-sm-4">	
               Приветствуем <?echo $_SESSION['login'];?>, <a href="/logout.php">Выйти</a><br><br>
           </div>
	    </div>
	    <div class="row">
   	        <div class="col-lg-1 col-sm-4">
	           Фильтры: 
	        </div>
	        <div class="col-lg-2 col-sm-4">
	           Цена: <br>
	          <a href="?<?get_p ()?>order_by_price=down">от дешевых к дорогим</a><br> 
	          <a href="?<?get_p ()?>order_by_price=up">от дорогих к дешевым</a>
	       </div>
	       <div class="col-lg-2 col-sm-4">
	          Порядок:<br>
	          <a href="?<?get_p ()?>order_by_alf=az">по алфавиту az</a> <br>
	          <a href="?<?get_p ()?>order_by_alf=za">по алфавиту za</a> 
	       </div>
	       <div class="col-lg-2 col-sm-4">
	          Сортировать:<br>
	          <a href="?<?get_p ()?>order_by_id=new">по id новые</a> <br>
	          <a href="?<?get_p ()?>order_by_id=old">по id старые</a> 
	       </div>
	       <div class="col-lg-2 col-sm-4">
              Выбрать производителя: 
	          <form action="" method="GET">
	          <select size="1"  name="vendor_filter">
		             <option value="">все</option>
		             <?php vendor_list($link);?>
					 <input type="submit" value="выбрать"  >
	          </form>
	         </select>
	      </div>
		  <div class="col-lg-3 col-sm-4 ">
	           <?php if($_SESSION['role']== 1){?>
               <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">Добавить новый</button>
               <!-- Modal -->
                   <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                       <div class="modal-dialog" role="document">
                           <div class="modal-content">
                                <div class="modal-header">
                                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                     <h4 class="modal-title" id="myModalLabel">Добавить новый  товар</h4>
                                </div>
                                <div class="modal-body">

	                                 <form action="" method="post" enctype="multipart/form-data"  class="form-edit">
	                                 <div class="col-lg-6">
	                                 название <input class="form-control" type="text" name = "name">
									 <hr>
                                     описание<textarea rows="4"  class="form-control"  name = "description"></textarea>
									 <hr>
									 </div>
									  <div class="col-lg-6">
                                     цена<input class="form-control" type="text" name = "price">
									 <hr>
									  производитель<input class="form-control" type="text" name = "vendor" >
									  <hr>
		                             <input name="is_active" type="radio" value="1" checked >Товар активен
		                             <input name="is_active" type="radio" value="0" >Товар не активен
									 <hr>
                                     </div>
									 <input type="file"  name="filenames">
                               </div>
                               <div class="modal-footer">
                                     <button type="button" class="btn btn-default" data-dismiss="modal">закрыть</button>
                                     <input type="submit" name="submit">
		                             </form>
                               </div>
                           </div>
                       </div>
                 </div><?}?>
            </div>
	      
	  </div>
	  <div class="row">
        <?php
        foreach ($items as $item){
        ?>
	      <div class="col-lg-3 col-sm-4  col-xs-6">
	         <div class="product_fon">
                <div class="product ">
                   <h1><?=$item['name']?></h1>
	               <h2><?=$item['price']?>грн</h2> 
	               <span  class="ven">Товар №<?=$item['id']?>. Производитель: <?=$item['vendor']?>.</span>
                   <img src="images/<?=$item['image']?>">
                   <p><?=$item['description']?></p>
	               <?php if($_SESSION['role']== 1){?>
                   <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#<?=$item['id']?>">редактировать </button>
                   <div class="modal fade" id="<?=$item['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                       <div class="modal-dialog" role="document">
                           <div class="modal-content">
                                <div class="modal-header">
                                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                     <h4 class="modal-title" id="myModalLabel">Редактирование Товара № <?=$item['id']?> </h4>
                               </div>
                               <div class="modal-body">
							    <form action="" method="post" enctype="multipart/form-data" class="form-edit">

									<div class="col-lg-6">
                                    <input type="hidden" name="id"  value = "<?=$item['id']?>" > 
	                                 Название<input class="form-control" type="text" name = "name" value = "<?=$item['name']?>">
									 <hr>
                                     Описание <textarea class="form-control" rows="4" name="description"><?=$item['description']?></textarea>
									 <hr>
									</div>
									<div class="col-lg-6">
                                     Цена<input class="form-control" type="text" name = "price" value = "<?=$item['price']?>">
									 <hr>
									 Производитель<input class="form-control" type="text" name = "vendor" value = "<?=$item['vendor']?>" >
									 <hr>
									 <input name="is_active" type="radio" value="1" <? if($item['is_active'] == "1") echo "checked"; ?> >Товар активен 
		                             <input name="is_active" type="radio" value="0" <? if($item['is_active'] == "0") echo "checked"; ?> >Товар не активен
									  <hr>
									 </div>
                                     <div class="row">
                                     <div class="col-lg-6">
	                                 <input type="hidden" name="image"  value = "<?=$item['image']?>" > 
	                                 <h6>Текущее изображение</h6> <img style="height: 100px;" src="images/<?=$item['image']?>">
									 </div>
									 <div class="col-lg-6">
									   <div class="col-lg-12">
									      Закачать новое<input type="file" multiple="multiple" name="filenames">
										</div>  
										<div class="col-lg-12">
										
										</div>
                                     </div>
									 </div>
	                           </div>
                               <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">закрыть</button>
							
                                    <input type="submit" name="submit" >
		                            </form>
                               </div>
                          </div>
                      </div>
                   </div>
                 	<?}?>
                 <br><span  class="ven">изменен: <?=$item['data']?></span>
              </div>
          </div>
      </div>
       <?php
       }
       ?>

    </div>
</div>
</body>
</html>
