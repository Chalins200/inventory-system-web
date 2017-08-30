<?php
  $page_title = 'Agregar venta';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
    <?php

  if(isset($_POST['add_sale'])){
      $req_fields = array('s_id','quantity','price','total', 'date' );
    validate_fields($req_fields);
        if(empty($errors)){
          $p_id      = $db->escape((int)$_POST['s_id']);
          $s_qty     = $db->escape((int)$_POST['quantity']);
          $s_total   = $db->escape($_POST['total']);
          $date      = $db->escape($_POST['date']);
          $s_date    = make_date();

          $sql  = "INSERT INTO sales (";
          $sql .= " product_id,qty,price,date";
          $sql .= ") VALUES (";
          $sql .= "'{$p_id}','{$s_qty}','{$s_total}','{$s_date}'";
          $sql .= ")";

                if($db->query($sql)){
                  update_product_qty($s_qty,$p_id);
                  $session->msg('s',"Venta agregada ");
                  redirect('add_sale.php', false);
                } else {
                  $session->msg('d','Lo siento, registro falló.');
                  redirect('add_sale.php', false);
                }
        } else {
           $session->msg("d", $errors);
           redirect('add_sale.php',false);
        } 
  }
if(isset($_POST['cerrar_venta'])){
    
    
}

?>
        <?php include_once('layouts/header.php'); ?>
        <div class="row">
            <div class="col-md-6">
                <?php echo display_msg($msg); ?>
                <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">Búsqueda</button>
            </span>
                            <input type="text" id="sug_input" class="form-control" name="title" placeholder="Buscar por el nombre del producto">
                        </div>
                        <div id="result" class="list-group"></div>
                    </div>
                </form>
            </div>

        </div>
        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Editar venta</span>
       </strong>
                    </div>
                    <div class="panel-body">
                        <form method="post" action="./php/addtocart.php">
                            <table class="table table-bordered">
                                <thead>
                                    <th> Producto </th>
                                    <th> Precio </th>
                                    <th> Cantidad </th>
                                    <th> Total </th>
                                    <th> Agregado</th>
                                    <th> Acciones</th>
                                </thead>
                                <tbody id="product_info"> </tbody>
                            </table>
                        </form>
                    </div>

                    <div class="panel-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <h1>Carrito</h1>

                                    <?php
            
            include "php/conection.php";
/*
* Esta es la consula para obtener todos los productos de la base de datos.
*/
$products = $con->query("select * from product");
            
if(isset($_SESSION["cart"]) && !empty($_SESSION["cart"])):
?>
                                        <table class="table table-bordered">
                                            <thead>
                                                <th>Cantidad</th>
                                                <th>Producto</th>
                                                <th>Precio Unitario</th>
                                                <th>Total</th>
                                                <th></th>
                                            </thead>
                                            <?php 
/*
* Apartir de aqui hacemos el recorrido de los productos obtenidos y los reflejamos en una tabla.
*/
   $total =0; 
   $num_products=0;
foreach($_SESSION["cart"] as $c):
    $query="select * from products where id=$c[s_id]";
 $products = $con->query($query)  or trigger_error($con->error);
 $r = $products->fetch_object();
	?>
                                            <tr>
                                                <th>
                                                    <?php 
    $total_product = $c["quantity"]*$r->sale_price;
    $total += $total_product;
    $num_products +=  $c["quantity"];
    echo $c["quantity"];?>
                                                </th>
                                                <td>
                                                    <?php echo $r->name;?>
                                                </td>
                                                <td>$
                                                    <?php echo $r->sale_price; ?>
                                                </td>
                                                <td>$
                                                    <?php echo $total_product; ?>
                                                </td>
                                                <td style="width:260px;">
                                                    <?php
	$found = false;
	foreach ($_SESSION["cart"] as $c) { if($c["s_id"]==$r->id){ $found=true; break; }}
	?>
                                                        <a href="php/delfromcart.php?id=<?php echo $c[" s_id "];?>" class="btn btn-danger">Eliminar</a>
                                                </td>
                                            </tr>

                                            <?php endforeach; ?>

                                        </table>

                                        <form class="form-horizontal" method="post" action="./php/process.php">
                                            <div class="col-sm-8">
                                                <button type="submit" class="btn btn-primary">Procesar Venta</button>
                                            </div>
                                            <div class="col-sm-2">
                                                <h4>TOTAL: $
                                                    <?php echo $total; ?>
                                                </h4>
                                                <input type="hidden" name="total_venta" value="<?php echo $total; ?>">
                                                <input type="hidden" name="num_products" value="<?php echo $num_products; ?>">
                                            </div>
                                        </form>


                                        <?php else:?>
                                        <div class="col-md-10">
                                            <p class="alert alert-warning">El carrito esta vacio.</p>
                                        </div>
                                        <?php endif;?>
                                        <br><br>

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

        </div>

        <?php include_once('layouts/footer.php'); ?>
