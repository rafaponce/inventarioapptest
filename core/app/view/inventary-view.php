<?php
$stock = StockData::getById($_GET["stock"]);
?>
<section class="content">
<div class="row">
	<div class="col-md-12">
<!-- Single button -->
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="report/inventary-word.php?stock_id=<?php echo $stock->id; ?>">Word 2007 (.docx)</a></li>
    <li><a href="report/inventary-xlsx.php?stock_id=<?php echo $stock->id; ?>">Excel 2007 (.xlsx)</a></li>
<li><a onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a>
  </ul>
</div>
		<h1><i class="glyphicon glyphicon-stats"></i> Inventario <small><?php echo $stock->name; ?></small></h1>
<ol class="breadcrumb">
  <li><a href="./?view=home">Inicio</a></li>
  <?php if(Core::$user->kind==1):?>
  <li><a href="./?view=stocks">Almacenes</a></li>
<?php endif; ?>
  <li><a href="./?view=inventary&stock=<?php echo $stock->id; ?>"><?php echo $stock->name;?></a></li>
</ol>
<div class="allfilterinventary"></div>

	</div>
</div>
</section>






<script type="text/javascript">
  $(document).ready(function(){
      $(".allfilterinventary").html("<i class='fa fa-refresh fa-spin'></i>");
    $.get("./?action=filterinventary&stock=<?php echo $_GET['stock']; ?>",$("#filterinventary").serialize(),function(data){
      $(".allfilterinventary").html(data);
    });


  });
</script>