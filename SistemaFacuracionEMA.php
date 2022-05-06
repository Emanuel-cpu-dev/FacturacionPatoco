<div class="row">	
  <div class="col-xs-6">
  
<div class="heading">
		<h2>Build Invoice System with PHP & MySQL</h2>
	</div>
<div class="login-form">
<form action="" method="post">
    <h2 class="text-center">Iniciar Sesión</h2>  
<div class="form-group">
<?php if ($loginError ) { ?>
<div class="alert alert-warning"><?php echo $loginError; ?></div>
<?php } ?>
</div>         
<div class="form-group">
    <input name="email" id="email" type="email" class="form-control" placeholder="Email address" autofocus required>
</div>
<div class="form-group">
    <input type="password" class="form-control" name="pwd" placeholder="Password" required>
</div> 
<div class="form-group">
    <button type="submit" name="login" class="btn btn-primary" style="width: 100%;"> Acceder </button>
</div>
<div class="clearfix">
<label class="pull-left checkbox-inline"><input type="checkbox"> Recordarme</label>
</div>        
</form>
</div>   

</div>
<div class="col-xs-6">-</div>	
</div>		
</div>

<?php
if (!empty($_POST['email']) && !empty($_POST['pwd'])) {
include 'Invoice.php';
$invoice = new Invoice();
$user = $invoice->loginUsers($_POST['email'], $_POST['pwd']);
if(!empty($user)) {
$_SESSION['user'] = $user[0]['first_name']."".$user[0]['last_name'];
$_SESSION['userid'] = $user[0]['id'];
$_SESSION['email'] = $user[0]['email'];
$_SESSION['address'] = $user[0]['address'];
$_SESSION['mobile'] = $user[0]['mobile'];
header("Location:invoice_list.php");
} else {
$loginError = "Email o Password Invalido!";
}
}

<div class="container">		
<h2 class="title">Sistema de facturación PHP</h2>
<?php include('menu.php');?>			  
<table id="data-table" class="table table-condensed table-striped">
<thead>
  <tr>
    <th width="7%">Fac. No.</th>
    <th width="15%">Fecha Creación</th>
    <th width="35%">Cliente</th>
    <th width="15%">Fatura Total</th>
    <th width="3%"></th>
    <th width="3%"></th>
    <th width="3%"></th>
  </tr>
</thead>
<?php		
$invoiceList = $invoice->getInvoiceList();
foreach($invoiceList as $invoiceDetails){
    $invoiceDate = date("d/M/Y, H:i:s", strtotime($invoiceDetails["order_date"]));
    echo '
      <tr>
        <td>'.$invoiceDetails["order_id"].'</td>
        <td>'.$invoiceDate.'</td>
        <td>'.$invoiceDetails["order_receiver_name"].'</td>
        <td>'.$invoiceDetails["order_total_after_tax"].'</td>
        <td><a href="print_invoice.php?invoice_id='.$invoiceDetails["order_id"].'" title="Imprimir Factura"><div class="btn btn-primary"><span class="glyphicon glyphicon-print"></span></div></a></td>
        <td><a href="edit_invoice.php?update_id='.$invoiceDetails["order_id"].'"  title="Editar Factura"><div class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></div></a></td>
        <td><a href="#" id="'.$invoiceDetails["order_id"].'" class="deleteInvoice"  title="Borrar Factura"><div class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></div></a></td>
      </tr>
    ';
}       
?>
</table>	
</div>	

<div class="container content-invoice">
<form action="" id="invoice-form" method="post" class="invoice-form" role="form" novalidate> 
<div class="load-animate animated fadeInUp">
<div class="row">
<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
    <h2 class="title">Sistema de Facturación PHP</h2>
    <?php include('menu.php');?>	
</div>		    		
</div>
<input id="currency" type="hidden" value="$">
<div class="row">
<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
    <h3>De,</h3>
    <?php echo $_SESSION['user']; ?><br>	
    <?php echo $_SESSION['address']; ?><br>	
    <?php echo $_SESSION['mobile']; ?><br>
    <?php echo $_SESSION['email']; ?><br>	
</div>      		
<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 pull-right">
    <h3>Para,</h3>
    <div class="form-group">
        <input type="text" class="form-control" name="companyName" id="companyName" placeholder="Nombre de Empresa" autocomplete="off">
    </div>
    <div class="form-group">
        <textarea class="form-control" rows="3" name="address" id="address" placeholder="Su dirección"></textarea>
    </div>
    
</div>
</div>
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <table class="table table-bordered table-hover" id="invoiceItem">	
        <tr>
            <th width="2%"><input id="checkAll" class="formcontrol" type="checkbox"></th>
            <th width="15%">Prod. No</th>
            <th width="38%">Nombre Producto</th>
            <th width="15%">Cantidad</th>
            <th width="15%">Precio</th>								
            <th width="15%">Total</th>
        </tr>							
        <tr>
            <td><input class="itemRow" type="checkbox"></td>
            <td><input type="text" name="productCode[]" id="productCode_1" class="form-control" autocomplete="off"></td>
            <td><input type="text" name="productName[]" id="productName_1" class="form-control" autocomplete="off"></td>			
            <td><input type="number" name="quantity[]" id="quantity_1" class="form-control quantity" autocomplete="off"></td>
            <td><input type="number" name="price[]" id="price_1" class="form-control price" autocomplete="off"></td>
            <td><input type="number" name="total[]" id="total_1" class="form-control total" autocomplete="off"></td>
        </tr>						
    </table>
</div>
</div>
<div class="row">
<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
    <button class="btn btn-danger delete" id="removeRows" type="button">- Borrar</button>
    <button class="btn btn-success" id="addRows" type="button">+ Agregar Más</button>
</div>
</div>
<div class="row">	
<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
    <h3>Notas: </h3>
    <div class="form-group">
        <textarea class="form-control txt" rows="5" name="notes" id="notes" placeholder="Notas"></textarea>
    </div>
    <br>
    <div class="form-group">
        <input type="hidden" value="<?php echo $_SESSION['userid']; ?>" class="form-control" name="userId">
        <input data-loading-text="Guardando factura..." type="submit" name="invoice_btn" value="Guardar Factura" class="btn btn-success submit_btn invoice-save-btm">						
    </div>
    
</div>
<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
    <span class="form-inline">
        <div class="form-group">
            <label>Subtotal: &nbsp;</label>
            <div class="input-group">
                <div class="input-group-addon currency">$</div>
                <input value="" type="number" class="form-control" name="subTotal" id="subTotal" placeholder="Subtotal">
            </div>
        </div>
        <div class="form-group">
            <label>Tasa Impuesto: &nbsp;</label>
            <div class="input-group">
                <input value="" type="number" class="form-control" name="taxRate" id="taxRate" placeholder="Tasa de Impuestos">
                <div class="input-group-addon">%</div>
            </div>
        </div>
        <div class="form-group">
            <label>Monto Inpuesto: &nbsp;</label>
            <div class="input-group">
                <div class="input-group-addon currency">$</div>
                <input value="" type="number" class="form-control" name="taxAmount" id="taxAmount" placeholder="Monto de Impuesto">
            </div>
        </div>							
        <div class="form-group">
            <label>Total: &nbsp;</label>
            <div class="input-group">
                <div class="input-group-addon currency">$</div>
                <input value="" type="number" class="form-control" name="totalAftertax" id="totalAftertax" placeholder="Total">
            </div>
        </div>
        <div class="form-group">
            <label>Cantidad pagada: &nbsp;</label>
            <div class="input-group">
                <div class="input-group-addon currency">$</div>
                <input value="" type="number" class="form-control" name="amountPaid" id="amountPaid" placeholder="Cantidad pagada">
            </div>
        </div>
        <div class="form-group">
            <label>Cantidad debida: &nbsp;</label>
            <div class="input-group">
                <div class="input-group-addon currency">$</div>
                <input value="" type="number" class="form-control" name="amountDue" id="amountDue" placeholder="Cantidad debida">
            </div>
        </div>
    </span>
</div>
</div>
<div class="clearfix"></div>		      	
</div>
</form>			
</div>
</div>	

<?php
include 'Invoice.php';
$invoice = new Invoice();
$invoice->saveInvoice($_POST);
?>

<table class="table table-bordered table-hover" id="invoiceItem">	
<tr>
    <th width="2%"><input id="checkAll" class="formcontrol" type="checkbox"></th>
<th width="15%">Prod. No</th>
<th width="38%">Nombre Producto</th>
<th width="15%">Cantidad</th>
<th width="15%">Precio</th>								
<th width="15%">Total</th>
</tr>
<?php 
$count = 0;
foreach($invoiceItems as $invoiceItem){
    $count++;
?>								
<tr>
    <td><input class="itemRow" type="checkbox"></td>
    <td><input type="text" value="<?php echo $invoiceItem["item_code"]; ?>" name="productCode[]" id="productCode_<?php echo $count; ?>" class="form-control" autocomplete="off"></td>
    <td><input type="text" value="<?php echo $invoiceItem["item_name"]; ?>" name="productName[]" id="productName_<?php echo $count; ?>" class="form-control" autocomplete="off"></td>			
    <td><input type="number" value="<?php echo $invoiceItem["order_item_quantity"]; ?>" name="quantity[]" id="quantity_<?php echo $count; ?>" class="form-control quantity" autocomplete="off"></td>
    <td><input type="number" value="<?php echo $invoiceItem["order_item_price"]; ?>" name="price[]" id="price_<?php echo $count; ?>" class="form-control price" autocomplete="off"></td>
    <td><input type="number" value="<?php echo $invoiceItem["order_item_final_amount"]; ?>" name="total[]" id="total_<?php echo $count; ?>" class="form-control total" autocomplete="off"></td>
    <input type="hidden" value="<?php echo $invoiceItem['order_item_id']; ?>" class="form-control" name="itemId[]">
</tr>	
<?php } ?>		
</table>

<?php
include 'Invoice.php';
$invoice = new Invoice();
$invoice->updateInvoice($_POST);
?>

