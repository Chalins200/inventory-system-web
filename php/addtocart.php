<?php
/*
* Agrega el producto a la variable de sesion de productos.
*/
session_start();
if(!empty($_POST)){
    //Se verifica si hay suficientes productos en almacen
    if($_POST["quantity"] > $_POST["stock"]){
        print "<script>alert('No hay suficiente producto en almacen. Stock actual: {$_POST["stock"]}');</script>";
        print "<script>window.location='../add_sale.php';</script>";
    }else{
	if(isset($_POST["s_id"]) && isset($_POST["quantity"])){
		// si es el primer producto simplemente lo agregamos
		if(empty($_SESSION["cart"])){
			$_SESSION["cart"]=array( array("s_id"=>$_POST["s_id"],"quantity"=> $_POST["quantity"]));
		}else{
			// apartie del segundo producto:
			$cart = $_SESSION["cart"];
			$repeated = false;
			// recorremos el carrito en busqueda de producto repetido
			foreach ($cart as $c) {
				// si el producto esta repetido rompemos el ciclo
				if($c["s_id"]==$_POST["s_id"]){
					$repeated=true;
					break;
				}
			}
			// si el producto es repetido no hacemos nada, simplemente redirigimos
			if($repeated){
				print "<script>alert('Error: Producto Repetido!');</script>";
			}else{
				// si el producto no esta repetido entonces lo agregamos a la variable cart y despues asignamos la variable cart a la variable de sesion
				array_push($cart, array("s_id"=>$_POST["s_id"],"quantity"=> $_POST["quantity"]));
				$_SESSION["cart"] = $cart;
			}
		}
		print "<script>window.location='../add_sale.php';</script>";
        $s_id = $c["s_id"];
        $quantity = $c["quantity"];
	}
}
}

?>