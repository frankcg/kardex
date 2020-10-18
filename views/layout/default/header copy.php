<!DOCTYPE html>

<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

  <title>Kardex</title>

  <!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">-->

  <link href="<?php echo BASE_URL ?>public/css/css.css" rel="stylesheet" type="text/css">

  <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>public/css/ionicons.min.css">
  <link href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css">
  

  <link rel="stylesheet" type="text/css" crossorigin="anonymous" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" href="<?php echo BASE_URL ?>public/css/font-awesome.min.css">
  <!--<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">-->

  <!-- DEMO ONLY: Function for the proper stylesheet loading according to the demo settings -->
  <script>function _pxDemo_loadStylesheet(a,b,c){var c=c||decodeURIComponent((new RegExp(";\\s*"+encodeURIComponent("px-demo-theme")+"\\s*=\\s*([^;]+)\\s*;","g").exec(";"+document.cookie+";")||[])[1]||"clean"),d="rtl"===document.getElementsByTagName("html")[0].getAttribute("dir");document.write(a.replace(/^(.*?)((?:\.min)?\.css)$/,'<link href="$1'+(c.indexOf("dark")!==-1&&a.indexOf("/css/")!==-1&&a.indexOf("/themes/")===-1?"-dark":"")+(!d||0!==a.indexOf("public/css")&&0!==a.indexOf("public/demo")?"":".rtl")+'$2" rel="stylesheet" type="text/css"'+(b?'class="'+b+'"':"")+">"))}</script>

  <!-- DEMO ONLY: Set RTL direction -->
  <script>"ltr"!==document.getElementsByTagName("html")[0].getAttribute("dir")&&"1"===decodeURIComponent((new RegExp(";\\s*"+encodeURIComponent("px-demo-rtl")+"\\s*=\\s*([^;]+)\\s*;","g").exec(";"+document.cookie+";")||[])[1]||"0")&&document.getElementsByTagName("html")[0].setAttribute("dir","rtl");</script>

  <!-- DEMO ONLY: Load PixelAdmin core stylesheets -->
  <script>
    _pxDemo_loadStylesheet('public/css/bootstrap.min.css', 'px-demo-stylesheet-bs');
    _pxDemo_loadStylesheet('public/css/pixeladmin.min.css', 'px-demo-stylesheet-core');
    _pxDemo_loadStylesheet('public/css/widgets.min.css', 'px-demo-stylesheet-widgets');
  </script>

  <!-- DEMO ONLY: Load theme -->
  <script>
    function _pxDemo_loadTheme(a){var b=decodeURIComponent((new RegExp(";\\s*"+encodeURIComponent("px-demo-theme")+"\\s*=\\s*([^;]+)\\s*;","g").exec(";"+document.cookie+";")||[])[1]||"clean");_pxDemo_loadStylesheet(a+b+".min.css","px-demo-stylesheet-theme",b)}
    _pxDemo_loadTheme('public/css/themes/');
  </script>

  <!-- Demo public -->
  <script>_pxDemo_loadStylesheet('public/demo/demo.css');</script>
  <!-- / Demo public -->

  <!-- holder.js -->
  <!--<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/holder/2.9.0/holder.js"></script>-->
  <script src="<?php echo BASE_URL ?>public/js/holder/holder.js"></script>   

  <!-- Pace.js -->
  <script src="public/pace/pace.min.js"></script>

  <script src="public/demo/demo.js"></script>


  

  <!-- Custom styling -->
  <style>
  .page-header-form .input-group-addon,
  .page-header-form .form-control {
    background: rgba(0,0,0,.05);
  }
</style>
<!-- / Custom styling -->

<script src="<?php echo BASE_URL ?>public/js/jquery-2.1.1.min.js"></script> 
<script src="<?php echo BASE_URL ?>public/js/jquery-ui-1.10.3.min.js"></script>


<script src="<?php echo BASE_URL?>public/js/bootstrap.min.js"></script>
<script src="<?php echo BASE_URL?>public/js/pixeladmin.min.js"></script>

<script src="<?php echo BASE_URL ?>public/js/typeahead.bundle.js"></script>

<script src="<?php echo BASE_URL?>public/js/datatables/pdfmake.min.js"></script>
<script src="<?php echo BASE_URL?>public/js/datatables/buttons.print.min.js"></script>
<script src="<?php echo BASE_URL?>public/js/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo BASE_URL?>public/js/datatables/buttons.bootstrap.min.js"></script>
<script src="<?php echo BASE_URL?>public/js/datatables/jszip.min.js"></script>      
<script src="<?php echo BASE_URL?>public/js/datatables/vfs_fonts.js"></script>
<script src="<?php echo BASE_URL?>public/js/datatables/buttons.html5.min.js"></script>        
<script src="<?php echo BASE_URL?>public/js/datatables/dataTables.fixedHeader.min.js"></script>
<script src="<?php echo BASE_URL?>public/js/datatables/dataTables.keyTable.min.js"></script>
<script src="<?php echo BASE_URL?>public/js/datatables/dataTables.scroller.min.js"></script>

<script src="<?php echo BASE_URL ?>public/js/validasololetras/validCampoFranz.js"></script>



<!-- ALERT DIALOG -->
<link rel="stylesheet" href="<?php echo BASE_URL ?>public/js/alert_dialog/jquery-confirm.css">
<script src="<?php echo BASE_URL ?>public/js/alert_dialog/jquery-confirm.js"></script>

<?php if(isset($_layoutParams['js']) && count($_layoutParams['js'])): ?>
  <?php foreach($_layoutParams['js'] as $layout): ?>
    <script src="<?php echo  $layout ?>" type="text/javascript"></script>
  <?php endforeach; ?>
<?php endif; ?>

<script>
  $(function() {
    $('#tab-resize-xs').pxTabResize();
  });
</script>



<script>
  $(function() {
    $('.select2').select2({
      placeholder: 'Selecione',
      dropdownParent: $(".modal_select2"),
    });
  });

  $(function() {
    $('.datepicker').datepicker({

      dateFormat : 'yyyy-mm-dd'
    });
  });
</script>





<script type="text/javascript">

  html='';
  cont=0;

  /*$.ajax({
    url: 'asignacion/getsolicitudes',  
    type: 'POST',
    cache: false,
    contentType: false,
    processData: false,
    dataType:'json',
    success: function(datas){
      console.log(datas);
      //console.log(datas.data[1].NOMBRE);
      $.each(datas.data, function(key, val){
         //console.log(datas.data[key].NOMBRE);
         html += '<div class="widget-messages-alt-item">';
         html += '<img src="public/demo/avatars/2.jpg" alt="" class="widget-messages-alt-avatar">';
         html += '<a href="#" class="widget-messages-alt-subject text-truncate">'+datas.data[key].NOMBRE+'</a>';
         html += '<div class="widget-messages-alt-description">Asignado por: <a href="#">'+datas.data[key].IDUSUARIOCREACION+'</a></div>';
         html += '<div class="widget-messages-alt-date">'+datas.data[key].FECHA+'</div>';
         html += '</div>';
         cont+=1
         //console.log(datas.data[key].NOMBRE);                 
      });
      $('#contador').html(cont);
      $('#navbar-messages').html(html);
    }                     
  });*/
</script>



          <script type="text/javascript">
            $(function () {
            //Para escribir solo letras
            $('.letras').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou._');
            //Para escribir solo numeros
            $('.numeros').validCampoFranz('0123456789');
            $('.numerospunto').validCampoFranz('0123456789.');
            $('.numerosyletras').validCampoFranz('0123456789.abcdefghijklmnñopqrstuvwxyzáéiou-@_ ');
          });
        </script>

      </head>
      <body>
        <nav class="px-nav px-nav-left">
          <button type="button" class="px-nav-toggle" data-toggle="px-nav">
            <span class="px-nav-toggle-arrow"></span>
            <span class="navbar-toggle-icon"></span>
            <span class="px-nav-toggle-label font-size-11">HIDE MENU</span>
          </button>

          <ul class="px-nav-content">
            <li class="px-nav-box p-a-3 b-b-1" id="demo-px-nav-box">
              <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <img src="public/demo/avatars/1.jpg" alt="" class="pull-xs-left m-r-2 border-round" style="width: 54px; height: 54px;">
              <div class="font-size-12"><span class="font-weight-light">Bienvenido, </span><strong><?php echo $_SESSION['nombre']; ?></strong></div>
              <div class="btn-group hide" style="margin-top: 4px;">
                <a href="#" class="btn btn-xs btn-primary btn-outline"><i class="fa fa-envelope"></i></a>
                <a href="#" class="btn btn-xs btn-primary btn-outline"><i class="fa fa-user"></i></a>
                <a href="#" class="btn btn-xs btn-primary btn-outline"><i class="fa fa-cog"></i></a>
                <a href="#" class="btn btn-xs btn-danger btn-outline"><i class="fa fa-power-off"></i></a>
              </div>
            </li>

            
            <?php if (isset($_SESSION['menu']['MENU_PAN'])):?>
              <li class="px-nav-item px-nav-dropdown">
                <a href=""><i class="px-nav-icon ion-ios-pulse-strong"></i><span class="px-nav-label">Panel</span></a>
                <ul class="px-nav-dropdown-menu">
                  <?php foreach ($_SESSION['menu']['MENU_PAN'] as $submenu):?>
                    <li class="px-nav-item"><a href="<?php echo BASE_URL . $submenu['UBICACION']?>"><span class="px-nav-label"><?php echo $submenu['DESCRIPCION']?></span></a></li>
                  <?php endforeach;?>
                </ul>
              </li>
            <?php endif;?>

<!-- 
            <?php if (isset($_SESSION['menu']['MENU_COM'])):?>
              <li class="px-nav-item px-nav-dropdown">
              <a href=""><i class="px-nav-icon ion-ios-pulse-strong"></i><span class="px-nav-label">Compra</span></a>
                <ul class="px-nav-dropdown-menu">
                  <?php foreach ($_SESSION['menu']['MENU_COM'] as $submenu):?>
                    <li class="px-nav-item"><a href="<?php echo BASE_URL . $submenu['UBICACION']?>"><span class="px-nav-label"><?php echo $submenu['DESCRIPCION']?></span></a></li>
                  <?php endforeach;?>
                </ul>
              </li>
            <?php endif;?>           
            

            <?php if (isset($_SESSION['menu']['MENU_PRO'])):?>
              <li class="px-nav-item px-nav-dropdown">
                <a href=""><i class="px-nav-icon ion-ios-pulse-strong"></i><span class="px-nav-label">Producto</span></a>
                <ul class="px-nav-dropdown-menu">
                  <?php foreach ($_SESSION['menu']['MENU_PRO'] as $submenu):?>
                    <li class="px-nav-item"><a href="<?php echo BASE_URL . $submenu['UBICACION']?>"><span class="px-nav-label"><?php echo $submenu['DESCRIPCION']?></span></a></li>
                  <?php endforeach;?>
                </ul>
              </li>
            <?php endif;?>

            <?php if (isset($_SESSION['menu']['MENU_VEN'])):?>
              <li class="px-nav-item px-nav-dropdown">
                <a href=""><i class="px-nav-icon ion-ios-pulse-strong"></i><span class="px-nav-label">Venta</span></a>
                <ul class="px-nav-dropdown-menu">
                  <?php foreach ($_SESSION['menu']['MENU_VEN'] as $submenu):?>
                    <li class="px-nav-item"><a href="<?php echo BASE_URL . $submenu['UBICACION']?>"><span class="px-nav-label"><?php echo $submenu['DESCRIPCION']?></span></a></li>
                  <?php endforeach;?>
                </ul>
              </li>
            <?php endif;?>

            <?php if (isset($_SESSION['menu']['MENU_REP'])):?>
              <li class="px-nav-item px-nav-dropdown">
                <a href=""><i class="px-nav-icon ion-ios-pulse-strong"></i><span class="px-nav-label">Reportes</span></a>
                <ul class="px-nav-dropdown-menu">
                  <?php foreach ($_SESSION['menu']['MENU_REP'] as $submenu):?>
                    <li class="px-nav-item"><a href="<?php echo BASE_URL . $submenu['UBICACION']?>"><span class="px-nav-label"><?php echo $submenu['DESCRIPCION']?></span></a></li>
                  <?php endforeach;?>
                </ul>
              </li>
            <?php endif;?> -->

            <?php if (isset($_SESSION['menu']['MENU_SEG'])):?>
              <li class="px-nav-item px-nav-dropdown">
              <a href=""><i class="px-nav-icon ion-locked"></i><span class="px-nav-label">Seguridad</span></a>
                <ul class="px-nav-dropdown-menu">
                  <?php foreach ($_SESSION['menu']['MENU_SEG'] as $submenu):?>
                    <li class="px-nav-item"><a href="<?php echo BASE_URL . $submenu['UBICACION']?>"><span class="px-nav-label"><?php echo $submenu['DESCRIPCION']?></span></a></li>
                  <?php endforeach;?>
                </ul>
              </li>
            <?php endif;?>

            <?php if (isset($_SESSION['menu']['MENU_TIENDA_1'])):?>
              <li class="px-nav-item px-nav-dropdown">
              <a href=""><i class="px-nav-icon ion-locked"></i><span class="px-nav-label">Tienda 1</span></a>
                <ul class="px-nav-dropdown-menu">
                  <?php foreach ($_SESSION['menu']['MENU_TIENDA_1'] as $submenu):?>
                    <li class="px-nav-item"><a href="<?php echo BASE_URL . $submenu['UBICACION']?>"><span class="px-nav-label"><?php echo $submenu['DESCRIPCION']?></span></a></li>
                  <?php endforeach;?>
                </ul>
              </li>
            <?php endif;?>


          </ul>
        </nav>

        <nav class="navbar px-navbar">
          <!-- Header -->
          <div class="navbar-header">
            <a class="navbar-brand px-demo-brand" href="<?php echo BASE_URL; ?>panel"><span class="font-size-20 line-height-1"><img src="public/img/kardex.png" alt="" class="pull-xs-left m-r-2 border-round" style="width: 124px; height: 50px;"></span></span></a>
          </div>

          <!-- Navbar togglers -->
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#px-demo-navbar-collapse" aria-expanded="false"><i class="navbar-toggle-icon"></i></button>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="px-demo-navbar-collapse">    

            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown hide">
                <a href="https://google.com" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                  <i class="px-navbar-icon fa fa-envelope font-size-14"></i>
                  <span class="px-navbar-icon-label">Income messages</span>
                  <span class="px-navbar-label label label-danger" id="contador"></span>
                </a>
                <div class="dropdown-menu p-a-0" style="width: 300px;">
                  <div id="navbar-messages" style="height: 150px; position: relative;" class="ps-container ps-theme-default" data-ps-id="112c41d3-0418-1f8f-4008-5f59a1b8eb91" >                     

                    <!--
                    <div class="widget-messages-alt-item">
                      <img src="public/demo/avatars/2.jpg" alt="" class="widget-messages-alt-avatar">
                      <a href="#" class="widget-messages-alt-subject text-truncate">Frank Laura Borja</a>
                      <div class="widget-messages-alt-description">Asignado por: <a href="#">FLAURA</a></div>
                      <div class="widget-messages-alt-date">20/04/2018</div>
                    </div>
                    -->

                  <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;"><div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; right: 3px;"><div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>

                  <a href="<?php echo BASE_URL ?>prueba" class="widget-more-link">Ver todas las evaluaciones</a>
                </div> <!-- / .dropdown-menu -->
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                  <img src="public/demo/avatars/1.jpg" alt="" class="px-navbar-image">
                  <span class="hidden-md"><?php echo $_SESSION['nombre']; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="hide"><a href=""><span class="label label-warning pull-xs-right"><i class="fa fa-asterisk"></i></span>Perfil</a></li>
                  <li class="divider"></li>
                  <li><a href="<?php echo BASE_URL; ?>index/logout"><i class="dropdown-icon fa fa-power-off"></i>&nbsp;&nbsp;Cerrar Sesion</a></li>
                </ul>
              </li>


            </ul>
          </div><!-- /.navbar-collapse -->

        </nav>

