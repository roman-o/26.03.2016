<?php
//---------------------------------------------------------------------------------------
function LOG_REG($type,$link,$pass){  //логин, регистрация
	
	if($_POST){
	if ($type == 'login') {$type_page = "AND password='$pass'";}
    if ($type == 'reg') {$type_page = "";}
	
    $sql = "SELECT * FROM users WHERE login='{$_POST['login']}' $type_page ";
    $res = mysqli_query($link, $sql);
    $res = mysqli_fetch_assoc($res);
    if((!$res)  AND ($type == 'login') ){
	    echo "<h2>Логин или пароль введен неправильно</h2>";
       } elseif ($type == 'login') {
		   
	        session_start();
			$_SESSION['user'] = "logged";	
			$_SESSION['role'] = $res['role'];
			$_SESSION['login'] = $res['login'];
			header("Location: /index.php");
       }
   if(($res) AND ($type == 'reg') ){
	     
	     echo "<h2>Такой юзер уже есть</h2>";
       } elseif ($type == 'reg') {
		   
	        $sql = "INSERT INTO users SET
	        login ='{$_POST['login']}',
			role ='0',
            password = '$pass'";
			$res = mysqli_query($link,$sql);
			session_start();
			$_SESSION['user'] = "logged";	
			$_SESSION['role'] = '0';
			$_SESSION['login'] = $_POST['login'];
			header("Location: /index.php");
        }

    }
}
//----------------------------------------------------------------------------------------
function UPDATE_INSERT ($a,$link){   //редактирование добавление
     
    if (isset($_POST['id'])){  // добавление или редактирование
         $id = $_POST['id'];
		 } 
      else {
		  $id = "";
		  }	
//-------  
// закачка файла
     if ($_FILES['filenames']['error'] == 0){   // проверяем был ли файл
        //  echo "не пустой"; print_r($_FILES);
	    $file_name = $_FILES['filenames']['name'];
        $file_type = substr($file_name, strrpos($file_name, '.')+1);
        //echo $file_type;
      if (($file_type == 'jpg')  OR ($file_type == 'jpeg')  OR ($file_type == 'png') OR ($file_type == 'JPG') OR ($file_type == 'JPEG') OR ($file_type == 'PNG')){
	    //if ($_FILES['filenames']['size'][$k] < 5120) {
	    $newname = uniqid('img_').'.'.$file_type;
	  if (getimagesize($_FILES['filenames']['tmp_name'])){
	    move_uploaded_file($_FILES['filenames']['tmp_name'], 'images/'.$newname);
	    }
	    }
	  else{ echo "неверный тип файла";}
    }
	else{
   // echo 'пустой';print_r($_FILES);
	  if ($id != ""){
		$newname = $_POST['image']; }  // если редактирование
	  else{
		  $newname = 'default.jpg';}  // если добавление
    }
   //--------
   // запись в базу	  
   if ($id > 0) {$id = "WHERE id=$id";}
   else { $id = "";}
   $sql = "$a products SET
   `name`='{$_POST['name']}',
   description='{$_POST['description']}',
   price='{$_POST['price']}',
   image='{$newname}',
   is_active='{$_POST['is_active']}',
   data = NOW(),
   vendor='{$_POST['vendor']}' $id
    ";
   $res = mysqli_query($link, $sql);	
   header("Location: /my/index.php");
}
//----------------------------------------------------------------------------------------
function order_by_price(){
	//var_dump( $_GET['order_by_price']);
	if (isset($_GET['order_by_price'])){
	   if ($_GET['order_by_price'] =="down"){$sq = "  ORDER BY price ";}
	   if ($_GET['order_by_price'] =="up"){$sq =   "  ORDER BY price DESC";}
	}
	
	else {
		$sq = "  ORDER BY id DESC";
	}
	return ($sq);
}
//----------------------------------------------------------------------------------------
function order_by_alf(){
	if (isset($_GET['order_by_alf'])){
	   if ($_GET['order_by_alf'] =="az"){$sq = "  ORDER BY  name ";}
	    if ($_GET['order_by_alf'] =="za"){$sq = "  ORDER BY name DESC ";}
	}
	
	else {
		$sq = "  ORDER BY id DESC ";
	}
	return ($sq);
}
//----------------------------------------------------------------------------------------
function order_by_id(){
	if (isset($_GET['order_by_id'])){
	   if ($_GET['order_by_id'] =="new"){$sq = "  ORDER BY id DESC";}
	    if ($_GET['order_by_id'] =="old"){$sq = "  ORDER BY id ";}
	}
	
	else {
		$sq = "  ORDER BY id DESC ";
	}
	return ($sq);
}
//----------------------------------------------------------------------------------------
function SELECT ($link,$sq){  // выборка для index

	if (($_SESSION['role'] == "0") AND (isset($_GET['vendor_filter']))){
	     if ($_GET['vendor_filter'] == ""){$sql = "SELECT * FROM products WHERE is_active ='1'  $sq  ";}
	     else{ $sql = "SELECT * FROM products WHERE is_active ='1' AND vendor ='{$_GET['vendor_filter']}'  $sq  ";}
	}			
	elseif ($_SESSION['role'] == "0" AND (!isset($_GET['vendor_filter']))){$sql = "SELECT * FROM products WHERE is_active ='1'  $sq  ";}
	
	if (($_SESSION['role'] == "1") AND (isset($_GET['vendor_filter']))){
	     if ($_GET['vendor_filter'] == ""){$sql = "SELECT * FROM products   $sq  ";}
	     else{ $sql = "SELECT * FROM products WHERE  vendor ='{$_GET['vendor_filter']}'  $sq  ";}
	}			
	elseif ($_SESSION['role'] == "1" AND (!isset($_GET['vendor_filter']))){$sql = "SELECT * FROM products   $sq  ";}
	
    $res = mysqli_query($link, $sql);
	$items = [];
    while ($row = mysqli_fetch_assoc($res)){
        $items[] = $row;
    } 
    return ($items);	
}

//------------------------------------------------------------------------------------------
function get_p (){   // переброска vendor
	if (isset($_GET['vendor_filter'])){echo "vendor_filter=".$_GET['vendor_filter']."&";}
}
//------------------------------------------------------------------------------------------
function vendor_list($link){  // сисок производителей
	$sql = "SELECT vendor FROM products group by vendor ";
    $res = mysqli_query($link, $sql);
    
	$vendor_array=[];
	while ($row = mysqli_fetch_assoc($res)){
        $vendor_array[] = $row;
    }
	//return ($vendor_array);
	
	foreach ($vendor_array as $item){ 
		if ($_GET['vendor_filter'] ==$item['vendor'] ){$selected ="selected";}else{$selected ="";}
		if($item['vendor']) echo '<option value="'.$item['vendor'].'" '.$selected.'  >'.$item['vendor']."</option>";}
}
//------------------------------------------------------------------------------------------
?>
