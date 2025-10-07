<?php
$default_chart = ConfigurationData::getByPreffix("default_chart")->val;

if(isset($_SESSION["client_id"])){ Core::redir("./?view=clienthome");}
if(Core::$user->kind==3){ Core::redir("./?view=sell"); }

  $dateB = new DateTime(date('Y-m-d')); 
  if($default_chart=="apex"){
  $dateA = $dateB->sub(DateInterval::createFromDateString('15 days'));

  }else if($default_chart=="morris"){
  $dateA = $dateB->sub(DateInterval::createFromDateString('30 days'));

  }
//  $dateA = $dateB->sub(DateInterval::createFromDateString('15 days'));
  $sd= strtotime(date_format($dateA,"Y-m-d"));
  $ed = strtotime(date("Y-m-d"));
  $ntot = 0;
  $nsells = 0;
  $totsells = 0;
  $totres = 0;
  $totpayments =0;
  $totspend = 0;
  $totdeposit=0;
for($i=$sd;$i<=$ed;$i+=(60*60*24)){
  $operations = SellData::getGroupByDateOp(date("Y-m-d",$i),date("Y-m-d",$i),2);
  $res = SellData::getGroupByDateOp(date("Y-m-d",$i),date("Y-m-d",$i),1);
  $spends = SpendData::getGroupByDateOp(date("Y-m-d",$i),date("Y-m-d",$i));
  $deposits = SpendData::getGroupByDateOp2(date("Y-m-d",$i),date("Y-m-d",$i));
  $payments = PaymentData::getGroupByDateOp(date("Y-m-d",$i),date("Y-m-d",$i));
//  echo $operations[0]->t;
  $sr = $res[0]->tot!=null?$res[0]->tot:0;
  $sl = $operations[0]->t!=null?$operations[0]->t:0;
  $sp = $spends[0]->t!=null?$spends[0]->t:0;
  $sp2 = $deposits[0]->t!=null?$deposits[0]->t:0;
  $paymentsx = $payments[0]->t!=null?$payments[0]->t:0;
  $ntot+=(($sl+$sp2)-($sp+$sr))+$paymentsx;
  $nsells += $operations[0]->c;
  $totsells+=$sl;
  $totres+=$sr;
  $totpayments+=$paymentsx;
  $totspend+=$sp;
  $totdeposit+=$sp2;
}
$stock =StockData::getPrincipal();
?>
  <section class="content-header">
    <h1>Inventio Max</h1>
    <h4>Almacen principal: <?php echo $stock->name;  ?></h4>
  </section>

    <section class="content">
      <?php if(Core::$user->kind==1):?>
<div class="row">
  <div class="col-md-12">
  <a href="./?view=home&show=2" class="btn btn-default">Dashboard 2</a>
  <a href="./?view=newproduct" class="btn btn-default">Nuevo Producto</a>
  <a href="./?view=inventary&stock=<?php echo $stock->id; ?>" class="btn btn-default">Inventario Principal</a>
  <a href="./?view=smallbox&opt=all" class="btn btn-default">Caja chica</a>
<!--  <a href="./?view=messages&opt=all" class="btn btn-default">Mensajes</a> -->
  </div>
  </div>
<?php endif; ?>

<br>
<?php if(isset($_GET["show"]) && $_GET["show"]=="2"):?>
  <div class="row">
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-purple"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Ventas del Mes</span>
              <span class="info-box-number"><?php echo Core::$symbol; ?> <?php echo number_format($totsells,2,".",",");?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="fa fa-check"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Pagos del mes</span>
              <span class="info-box-number"><?php echo Core::$symbol; ?> <?php echo number_format($totpayments,2,".",",");?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-navy"><i class="fa fa-bank"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Depositos del mes</span>
              <span class="info-box-number"><?php echo Core::$symbol; ?> <?php echo number_format($totdeposit,2,".",",");?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-truck"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Compras del Mes</span>
              <span class="info-box-number"><?php echo Core::$symbol; ?> <?php echo number_format($totres,2,".",",");?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-orange"><i class="fa fa-bank"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Gastos del mes</span>
              <span class="info-box-number"><?php echo Core::$symbol; ?> <?php echo number_format($totspend,2,".",",");?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-circle-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Balance del Mes</span>
              <span class="info-box-number"><?php echo Core::$symbol; ?> <?php echo number_format($ntot,2,".",",");?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
  <?php else:?>
<div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-glass"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Productos</span>
              <span class="info-box-number"><?php echo count(ProductData::getAll()); ?><small></small></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-male"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Clientes</span>
              <span class="info-box-number"><?php echo count(PersonData::getClients());?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Ventas del mes</span>
              <span class="info-box-number"><?php echo $nsells;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-area-chart"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Ventas del Mes</span>
              <span class="info-box-number"><?php echo Core::$symbol; ?> <?php echo number_format($totsells,2,".",",");?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
    <?php endif; ?>
      <!-- /.row -->

      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-9">

<?php if($default_chart=="apex"):?>
                  <p class="text-center">
                    <strong>Balance de los ultimos 15 dias</strong>
                  </p>
<div id="myapexchart"></div>

<script type="text/javascript">
 var options = {
            chart: {
                height: 380,
                type: 'line' // se puede cambiar por 'area' y 'bar'
            },
            title: {
                text: 'Ventas: Ultimos 30 dias', // Titulo de la grafica, se muestra en la parte superior
            },
            xaxis: {
                categories: [
<?php for($i=$sd;$i<=$ed;$i+=(60*60*24)):?>
                '<?php echo date("d/M",$i);?>', 
              <?php endfor; ?>
                ], // son 7 valores, deben coincidir con los valores de los datos en las series.
            },
            series: [{
                name: "Semana actual", // Nombre de la serie, se muestra cuando hay mas de 1 serie
                data: [
<?php for($i=$sd;$i<=$ed;$i+=(60*60*24)):
  $operations = SellData::getGroupByDateOp(date("Y-m-d",$i),date("Y-m-d",$i),2);
  $res = SellData::getGroupByDateOp(date("Y-m-d",$i),date("Y-m-d",$i),1);
  $spends = SpendData::getGroupByDateOp(date("Y-m-d",$i),date("Y-m-d",$i));
  $payments = PaymentData::getGroupByDateOp(date("Y-m-d",$i),date("Y-m-d",$i));
  $deposits = SpendData::getGroupByDateOp2(date("Y-m-d",$i),date("Y-m-d",$i));

  $sp2 = $deposits[0]->t!=null?$deposits[0]->t:0;

  $sr = $res[0]->tot!=null?$res[0]->tot:0;
  $sl = $operations[0]->t!=null?$operations[0]->t:0;
  $sp = $spends[0]->t!=null?$spends[0]->t:0;
  $paymentsx = $payments[0]->t!=null?$payments[0]->t:0;
  ?>
                <?php echo ((($sl+$sp2)-($sp+$sr))+$paymentsx );?>, 
              <?php endfor; ?>
] // valores hay 7 valores, debes coincidir con los valores de: xaxis
            }],
            colors:[ '#9C27B0' ]
            ,
            grid: {
                row: {
                    colors: ['#e5e5e5','transparent'], // color de fondo separados por coma para alternarlos
                }
            }
        }

        var chart = new ApexCharts( document.querySelector("#myapexchart") , options);

        chart.render();
</script>

<?php endif; ?>


<?php if($default_chart=="morris"):?>
                  <p class="text-center">
                    <strong>Balance de los ultimos 30 dias</strong>
                  </p>


<?php 
  $dateB = new DateTime(date('Y-m-d')); 
  $dateA = $dateB->sub(DateInterval::createFromDateString('30 days'));
  $sd= strtotime(date_format($dateA,"Y-m-d"));
  $ed = strtotime(date("Y-m-d"));

?>
<div id="graph" style="height:450px;" class="animate" data-animate="fadeInUp" ></div>
<script>

<?php 
echo "var c=0;";
echo "var dates=Array();";
echo "var data=Array();";
echo "var sells=Array();";
echo "var res=Array();";
echo "var payments=Array();";
echo "var spends=Array();";
echo "var deposits=Array();";
echo "var total=Array();";
for($i=$sd;$i<=$ed;$i+=(60*60*24)){
  $operations = SellData::getGroupByDateOp(date("Y-m-d",$i),date("Y-m-d",$i),2);
  $res = SellData::getGroupByDateOp(date("Y-m-d",$i),date("Y-m-d",$i),1);
  $spends = SpendData::getGroupByDateOp(date("Y-m-d",$i),date("Y-m-d",$i));
  $payments = PaymentData::getGroupByDateOp(date("Y-m-d",$i),date("Y-m-d",$i));
  $deposits = SpendData::getGroupByDateOp2(date("Y-m-d",$i),date("Y-m-d",$i));

  $sp2 = $deposits[0]->t!=null?$deposits[0]->t:0;

//  echo $operations[0]->t;
  $sr = $res[0]->tot!=null?$res[0]->tot:0;
  $sl = $operations[0]->t!=null?$operations[0]->t:0;
  $sp = $spends[0]->t!=null?$spends[0]->t:0;
  $paymentsx = $payments[0]->t!=null?$payments[0]->t:0;
  echo "dates[c]=\"".date("Y-m-d",$i)."\";";
  echo "data[c]=".((($sl+$sp2)-($sp+$sr))+$paymentsx ).";";
  echo "sells[c]=".(($sl)).";";
  echo "spends[c]=0-".(($sp)).";";
  echo "deposits[c]=".(($sp2)).";";
  echo "res[c]=0-".(($sr)).";";
  echo "payments[c]=".(($paymentsx)).";";
  echo "total[c]={x: dates[c],a: data[c], b: sells[c], c: res[c] , d: payments[c] , e: spends[c], f: deposits[c]  };";
  echo "c++;";
}
?>
// Use Morris.Area instead of Morris.Line
Morris.Line({
  element: 'graph',
  data: total,
  xkey: 'x',
  ykeys: ['a','b','c','d','e','f'],
  labels: ['Balance',"Ventas", "Compras","Pagos Credito","Gastos","Depositos"],
  lineColors: ['#3F51B5', "#27ae60" , "#e74c3c","#f1c40f" , "#e67e22", "#8e44ad"],
}).on('click', function(i, row){
  console.log(i, row);
});
</script>

<?php endif; ?>
                  <!-- /.chart-responsive -->
                </div>
<?php
$today = time();
$last30 = $today - (60*60*24*30);
 $today_at = date("Y-m-d",$today);
 $last30_at = date("Y-m-d",$last30);
$allops = OperationData::get10Popular($stock->id,$last30_at,$today_at );
?>

                <div class="col-md-3">

                  <p class="text-center">
                    <strong>Top 10 Productos/Mes</strong>
                  </p>
<div id="morrisdonut"></div>
</div>

<script type="text/javascript">
  Morris.Donut({
  element: 'morrisdonut',
  data: [
  <?php foreach($allops as $opx): $product = ProductData::getById($opx->product_id);    ?>
    {label: "<?php echo $product->name; ?>", value: <?php echo $opx->total; ?>},
  <?php endforeach; ?>
  ],  colors: ['#3F51B5',"#27ae60" , "#e74c3c","#f1c40f"],

});

</script>
                <!-- /.col -->

                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- 
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <h5 class="description-header">$35,210.43</h5>
                    <span class="description-text">Ventas de hoy</span>
                  </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <h5 class="description-header">$10,390.90</h5>
                    <span class="description-text">Ventas ayer</span>
                  </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <h5 class="description-header">$24,813.53</h5>
                    <span class="description-text">Ventas del mes</span>
                  </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block">
                    <h5 class="description-header">1200</h5>
                    <span class="description-text">Ventas totales</span>
                  </div>
                </div>
              </div>
            </div>
            -->

            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->







</section>


