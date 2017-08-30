<?php 
session_start();
include "conection.php";
if(!empty($_POST)){
    
	$query =$con->query("Select * FROM sales_gen");
	$num_products = $query->num_rows;
    
    
$q1 = $con->query("insert into sales_gen(qty,price,date) value(\"$_POST[num_products]\",\"$_POST[total_venta]\",NOW())");

    if($q1){
        $num_products += 1;
        foreach($_SESSION["cart"] as $c){
            $q1 = $con->query("insert into detalle_sales(product_id,qty,folio_id) value($c[s_id],$c[quantity],".$num_products.")");
            $sql1 = $con->query("UPDATE products SET quantity=quantity -'{$c[quantity]}' WHERE id = '{$c[s_id]}'");
            
        }

        unset($_SESSION["cart"]);
}
    
}

print "<script>alert('Venta procesada exitosamente');window.location='../add_sale.php';</script>";
?>
