<?php
  $page_title = 'Edit sale';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php
$sale = find_by_folio('detalle_sales',(int)$_GET['id']) ;
if(!$sale){
  $session->msg("d","Missing product id. ".$_GET['product_id']." ".$_GET['fecha']." ".$_GET['id']);
  redirect('sales.php');
}
?>

<?php

  if(isset($_POST['update_sale'])){
    $req_fields = array('title','quantity'.$_GET['product_id'].'','price'.$_GET['product_id'].'','total'.$_GET['product_id'].'', 'date' );
    validate_fields($req_fields);
        if(empty($errors)){
          $sale_id   = $db->escape((int)$_GET['id']);
          $p_id      = $db->escape((int)$_GET['product_id']);
          $s_qty     = $db->escape((int)$_POST['quantity'.$_GET['product_id'].'']);
          $s_total   = $db->escape($_POST['total'.$_GET['product_id'].'']);
          $date      = $db->escape($_POST['date']);
          $s_date    = date("Y-m-d", strtotime($date));
          
          $qty       = $db->escape((int)$_POST['q'.$_GET['product_id'].'']);
            
          $p_qty     = (int)$qty - (int)$s_qty;
          
          $sql  = "UPDATE detalle_sales SET";
          $sql .= " qty='{$s_qty}'";
          $sql .= " WHERE product_id='{$p_id}' AND folio_id ='{$sale_id}'";
          $result = $db->query($sql);
          if( $result){
                    update_product_qty($p_qty,$p_id);
                    
                    $update = update_sale_qty($sale_id);
              foreach ($update as  $up):
                    $sql1 = "UPDATE sales_gen SET qty='{$up['total_sales']}', price='{$up['total_saleing_price']}' WHERE folio= '{$sale_id}'";
      
                    $result = $db->query($sql1);
              endforeach;
                    $session->msg('s',"Venta actualizada.");
                    redirect('edit_sale.php?id='.$sale_id.'&fecha='.$date.'', false);
                  } else {
                    $session->msg('d','  Lo sentimos, la actualización falló!'.$p_id);
                    redirect('sales.php', false);
                  }
        } else {
           $session->msg("d", $errors);
           redirect('edit_sale.php?id='.(int)$sale['id'],false);
        }
  }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">

  <div class="col-md-12">
  <div class="panel">
    <div class="panel-heading clearfix">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Productos de la venta</span>
     </strong>
     <div class="pull-right">
       <a href="sales.php" class="btn btn-primary">Mostrar todas las ventas</a>
     </div>
    </div>
    <div class="panel-body">
       <table class="table table-bordered">
         <thead>
          <th> Nombre del producto </th>
          <th> Cantidad </th>
          <th> Precio </th>
          <th> Total </th>
          <th> Fecha</th>
          <th> Acción</th>
         </thead>
           <tbody  id="product_info">
              
                 <?php
               global $db;
                if(tableExists('products')){
                    $sql ="
                        SELECT d.id as sale_id, p.name as name, p.id as product_id, 
                        p.sale_price as sale_price,
                        d.qty as qty ,(p.sale_price * d.qty) as Total
                        FROM detalle_sales d 
                        RIGHT JOIN products p ON d.product_id=p.id
                        WHERE d.folio_id='{$sale['folio_id']}'";
                    $result = $db->query($sql) or trigger_error($con->error);
                  while($r = mysqli_fetch_assoc($result)) { 
                     
                  $html ="<tr><form method=\"post\" action=\"edit_sale.php?id=".$sale['folio_id'] ."&product_id=".$r['product_id'] ."&fecha=".$_GET['fecha']."\">
                <td id=\"s_name\">
                  <input type=\"text\" class=\"form-control\" id=\"sug_input\" name=\"title\" value='".$r['name']."'>
                  <div id=\"result\" class=\"list-group\"></div>
                </td>
                <td id=\"s_qty\">
                  <input type=\"text\" class=\"form-control\" name=\"quantity".$r['product_id'] ."\" value=".$r['qty'].">
                  <input type=\"hidden\" name=\"q".$r['product_id'] ."\" value=".$r['qty'].">
                </td>
                <td id=\"s_price\">
                  <input type=\"text\" class=\"form-control\" name=\"price".$r['product_id'] ."\" value=".$r['sale_price']." >
                  <input type=\"hidden\" name=\"p".$r['product_id'] ."\" value=".$r['qty'].">
                </td>
                <td>
                  <input type=\"text\" class=\"form-control\" name= \"total".$r['product_id'] ."\" value=".$r['Total'].">
                  <input type=\"hidden\" name=\"t".$r['product_id'] ."\" value=".$r['qty'].">
                </td>
                <td id=\"s_date\">
                  <input type=\"date\" class=\"form-control datepicker\" name=\"date\" data-date-format=\"\" value=".$_GET['fecha'].">
                </td>
                <td>
                  <button type=\"submit\" name=\"update_sale\" class=\"btn btn-primary\">ACTUALIZAR</button>
                </td>
              </form>
                  </tr>"; 
                  echo $html; 
                  }
                    
                }
                
                  ?>
              
           </tbody>
       </table>

    </div>
  </div>
  </div>

</div>

<?php include_once('layouts/footer.php'); ?>
