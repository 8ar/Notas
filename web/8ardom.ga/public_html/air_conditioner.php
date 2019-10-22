<?php
session_start();
$logged = $_SESSION['logged'];
include('mqtt_devices/MySQL/search_devices_air_condi.php');

if(!$logged){
  echo "Ingreso no autorizado";
  die();
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Casa</title>
  <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- for ios 7 style, multi-resolution icon of 152x152 -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
  <link rel="apple-touch-icon" href="assets/images/logo.png">
  <meta name="apple-mobile-web-app-title" content="Flatkit">
  <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="shortcut icon" sizes="196x196" href="assets/images/logo.png">

  <!-- style -->
  <link rel="stylesheet" href="assets/animate.css/animate.min.css" type="text/css" />
  <link rel="stylesheet" href="assets/glyphicons/glyphicons.css" type="text/css" />
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="assets/material-design-icons/material-design-icons.css" type="text/css" />

  <link rel="stylesheet" href="assets/bootstrap/dist/css/bootstrap.min.css" type="text/css" />
  <!-- build:css assets/styles/app.min.css -->
  <link rel="stylesheet" href="assets/styles/app.css" type="text/css" />
  <!-- endbuild -->
  <link rel="stylesheet" href="assets/styles/font.css" type="text/css" />
</head>
<body>
  <div class="app" id="app">

    <!-- ############ LAYcOUT START-->

    <!-- BARRA IZQUIERDA -->
    <!-- aside -->
<?php include('ladoizquierdo.php') ?>
    <!-- / -->

    <!-- content -->
    <div id="content" class="app-content box-shadow-z0" role="main">
      <div class="app-header white box-shadow">
        <div class="navbar navbar-toggleable-sm flex-row align-items-center">
          <!-- Open side - Naviation on mobile -->
          <a data-toggle="modal" data-target="#aside" class="hidden-lg-up mr-3">
            <i class="material-icons">&#xe5d2;</i>
          </a>
          <!-- / -->

          <!-- Page title - Bind to $state's title -->
          <div class="mb-0 h5 no-wrap" ng-bind="$state.current.data.title" id="pageTitle"></div>

          <!-- navbar collapse -->
          <div class="collapse navbar-collapse" id="collapse">
            <!-- link and dropdown -->
            <ul class="nav navbar-nav mr-auto">
              <li class="nav-item dropdown">
                <a class="nav-link" href data-toggle="dropdown">
                  <i class="fa fa-fw fa-plus text-muted"></i>
                  <span>New</span>
                </a>
                <div ui-include="'views/blocks/dropdown.new.html'"></div>
              </li>
            </ul>

            <div ui-include="'views/blocks/navbar.form.html'"></div>
            <!-- / -->
          </div>
          <!-- / navbar collapse -->

          <!-- BARRA DE LA DERECHA -->
          <ul class="nav navbar-nav ml-auto flex-row">
            <li class="nav-item dropdown pos-stc-xs">
              <a class="nav-link mr-2" href data-toggle="dropdown">
                <i class="material-icons">&#xe7f5;</i>
                <span class="label label-sm up warn">3</span>
              </a>
              <div ui-include="'views/blocks/dropdown.notification.html'"></div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link p-0 clear" href="#" data-toggle="dropdown">
                <span class="avatar w-32">
                  <img src="assets/images/a0.jpg" alt="...">
                  <i class="on b-white bottom"></i>
                </span>
              </a>
              <div ui-include="'views/blocks/dropdown.user.html'"></div>
            </li>
            <li class="nav-item hidden-md-up">
              <a class="nav-link pl-2" data-toggle="collapse" data-target="#collapse">
                <i class="material-icons">&#xe5d4;</i>
              </a>
            </li>
          </ul>
          <!-- / navbar right -->
        </div>
      </div>


      <!-- PIE DE PAGINA -->
      <!-- <div class="app-footer">
        <div class="p-2 text-xs">
          <div class="pull-right text-muted py-1">
            &copy; Copyright <strong>Flatkit</strong> <span class="hidden-xs-down">- Built with Love v1.1.3</span>
            <a ui-scroll-to="content"><i class="fa fa-long-arrow-up p-x-sm"></i></a>
          </div>
          <div class="nav">
            <a class="nav-link" href="">About</a>
          </div>
        </div>
      </div> -->

      <div ui-view class="app-body" id="view">


        <!-- SECCION CENTRAL -->
        <div class="padding">
          <!-- Aqui se escoge el aire condicionado(s) para poder hacer el cambio en la casa o habitaciones deseadas -->
          <form id="air_conditioner">
            <div class="row">

              <div class="col-xs-13 col-sm-4">
                  <div class="box p-a">
                    <div class="clear">
                      <select id="airecondiciondados" class="form-control select2" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                        <?php foreach ($models as $model ) { ?>
                          <option value="<?php echo $model['devices_id']?>"><?php echo $model['devices_name'] ?></option>
                        <?php } ?>

                      </select>
                      <small class="text-muted">Dispositivo</small>
                    </div>
                  </div>
              </div>

              <div class="col-xs-12 col-sm-6">
                <div class="box p-a">
                  <div class="form-group row">
                    <label class="col-sm-2 form-control-label">Enviar</label>
                    <div class="col-sm-10">
                      <label class="ui-switch ui-switch-md info m-t-xs">
                        <input id="switch_repeticion"  type="checkbox" >
                        <i></i>
                      </label>
                    </div>
                  </div>
                </div>

            </div>
          </div>




            <!-- VALORES EN TIEMPO REAL -->


            <div class="row">

              <div class="col-xs-12 col-sm-6">
                <div class="box p-a">
                  <div class="form-group row">
                    <label class="col-sm-2 form-control-label">ON/OFF</label>
                    <div class="col-sm-10">
                      <label class="ui-switch ui-switch-md info m-t-xs">
                        <input id="switch_1"  type="checkbox" >
                        <i></i>
                      </label>
                    </div>
                  </div>
                </div>
              </div>


              <div class="col-xs-13 col-sm-4">
                <div class="box p-a">
                  <div class="clear">
                    <div class="pull-left m-r">
                      <input type="number" id="temperatura_1" class="m-0 num-lg _300" name="quantity" min=16 max=32 value=16></input>
                      <small class="text-muted">Temperatura</small>
                      <button id="SubirTemp" type="button" name="button"> + </button>
                      <button id="BajarTemp" type="button" name="button"> - </button>
                      <i></i>
                    </div>
                  </div>
                </div>
              </div>




          </div>

        <!-- <div class="row">

        </div> -->


          <div class="row">



            <div class="col-xs-13 col-sm-4">
                <div class="box p-a">
                  <div class="clear">
                    <select id="modo_1" name="mc_num" class="form-control select2" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                      <option value="cool">FRIO</option>
                      <option value="heat">CALOR</option>
                    </select>
                    <small class="text-muted">Modo</small>
                  </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6">
              <div class="box p-a">
                <div class="form-group row">
                  <label class="col-sm-2 form-control-label">Swing Up</label>
                  <div class="col-sm-10">
                    <label class="ui-switch ui-switch-md info m-t-xs">
                      <input id="switch_swingup_1"  type="checkbox" >
                      <i></i>
                    </label>
                  </div>
                </div>
              </div>
            </div>



          </div>


          <div class="row">
          <div class="col-xs-13 col-sm-4">
              <div class="box p-a">
                <div class="clear">
                  <select id="velocidad_1" name="mc_num" class="form-control select2" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="AUTO">AUTO</option>
                  </select>
                  <small class="text-muted">Velocidad</small>
                </div>
              </div>
          </div>


          <div class="col-xs-12 col-sm-6">
            <div class="box p-a">
              <div class="form-group row">
                <label class="col-sm-2 form-control-label">Swing Side</label>
                <div class="col-sm-10">
                  <label class="ui-switch ui-switch-md info m-t-xs">
                    <input id="switch_swingdown_1"  type="checkbox" >
                    <i></i>
                  </label>
                </div>
              </div>
            </div>
          </div>
          </div>



          </form>






        <!-- ############ PAGE END-->

      </div>

    </div>
    <!-- / -->

    <!-- SELECTOR DE TEMAS -->
    <div id="switcher">
      <div class="switcher box-color dark-white text-color" id="sw-theme">
        <a href ui-toggle-class="active" target="#sw-theme" class="box-color dark-white text-color sw-btn">
          <i class="fa fa-gear"></i>
        </a>
        <div class="box-header">
          <a href="https://themeforest.net/item/flatkit-app-ui-kit/13231484?ref=flatfull" class="btn btn-xs rounded danger pull-right">BUY</a>
          <h2>Theme Switcher</h2>
        </div>
        <div class="box-divider"></div>
        <div class="box-body">
          <p class="hidden-md-down">
            <label class="md-check m-y-xs"  data-target="folded">
              <input type="checkbox">
              <i class="green"></i>
              <span class="hidden-folded">Folded Aside</span>
            </label>
            <label class="md-check m-y-xs" data-target="boxed">
              <input type="checkbox">
              <i class="green"></i>
              <span class="hidden-folded">Boxed Layout</span>
            </label>
            <label class="m-y-xs pointer" ui-fullscreen>
              <span class="fa fa-expand fa-fw m-r-xs"></span>
              <span>Fullscreen Mode</span>
            </label>
          </p>
          <p>Colors:</p>
          <p data-target="themeID">
            <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'primary', accent:'accent', warn:'warn'}">
              <input type="radio" name="color" value="1">
              <i class="primary"></i>
            </label>
            <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'accent', accent:'cyan', warn:'warn'}">
              <input type="radio" name="color" value="2">
              <i class="accent"></i>
            </label>
            <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'warn', accent:'light-blue', warn:'warning'}">
              <input type="radio" name="color" value="3">
              <i class="warn"></i>
            </label>
            <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'success', accent:'teal', warn:'lime'}">
              <input type="radio" name="color" value="4">
              <i class="success"></i>
            </label>
            <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'info', accent:'light-blue', warn:'success'}">
              <input type="radio" name="color" value="5">
              <i class="info"></i>
            </label>
            <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'blue', accent:'indigo', warn:'primary'}">
              <input type="radio" name="color" value="6">
              <i class="blue"></i>
            </label>
            <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'warning', accent:'grey-100', warn:'success'}">
              <input type="radio" name="color" value="7">
              <i class="warning"></i>
            </label>
            <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'danger', accent:'grey-100', warn:'grey-300'}">
              <input type="radio" name="color" value="8">
              <i class="danger"></i>
            </label>
          </p>
          <p>Themes:</p>
          <div data-target="bg" class="row no-gutter text-u-c text-center _600 clearfix">
            <label class="p-a col-sm-6 light pointer m-0">
              <input type="radio" name="theme" value="" hidden>
              Light
            </label>
            <label class="p-a col-sm-6 grey pointer m-0">
              <input type="radio" name="theme" value="grey" hidden>
              Grey
            </label>
            <label class="p-a col-sm-6 dark pointer m-0">
              <input type="radio" name="theme" value="dark" hidden>
              Dark
            </label>
            <label class="p-a col-sm-6 black pointer m-0">
              <input type="radio" name="theme" value="black" hidden>
              Black
            </label>
          </div>
        </div>
      </div>

      <div class="switcher box-color black lt" id="sw-demo">
        <a href ui-toggle-class="active" target="#sw-demo" class="box-color black lt text-color sw-btn">
          <i class="fa fa-list text-white"></i>
        </a>
        <div class="box-header">
          <h2>Demos</h2>
        </div>
        <div class="box-divider"></div>
        <div class="box-body">
          <div class="row no-gutter text-u-c text-center _600 clearfix">
            <a href="dashboard.html"
            class="p-a col-sm-6 primary">
            <span class="text-white">Default</span>
          </a>
          <a href="dashboard.0.html"
          class="p-a col-sm-6 success">
          <span class="text-white">Zero</span>
        </a>
        <a href="dashboard.1.html"
        class="p-a col-sm-6 blue">
        <span class="text-white">One</span>
      </a>
      <a href="dashboard.2.html"
      class="p-a col-sm-6 warn">
      <span class="text-white">Two</span>
    </a>
    <a href="dashboard.3.html"
    class="p-a col-sm-6 danger">
    <span class="text-white">Three</span>
  </a>
  <a href="dashboard.4.html"
  class="p-a col-sm-6 green">
  <span class="text-white">Four</span>
</a>
<a href="dashboard.5.html"
class="p-a col-sm-6 info">
<span class="text-white">Five</span>
</a>
<div
class="p-a col-sm-6 lter">
<span class="text">...</span>
</div>
</div>
</div>
</div>
</div>
<!-- / -->

<!-- ############ LAYOUT END-->

</div>
<!-- build:js scripts/app.html.js -->
<!-- jQuery -->
<script src="libs/jquery/jquery/dist/jquery.js"></script>
<!-- Bootstrap -->
<script src="libs/jquery/tether/dist/js/tether.min.js"></script>
<script src="libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
<!-- core -->
<script src="libs/jquery/underscore/underscore-min.js"></script>
<script src="libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js"></script>
<script src="libs/jquery/PACE/pace.min.js"></script>

<script src="assets/scripts/config.lazyload.js"></script>

<script src="assets/scripts/palette.js"></script>
<script src="assets/scripts/ui-load.js"></script>
<script src="assets/scripts/ui-jp.js"></script>
<script src="assets/scripts/ui-include.js"></script>
<script src="assets/scripts/ui-device.js"></script>
<script src="assets/scripts/ui-form.js"></script>
<script src="assets/scripts/ui-nav.js"></script>
<script src="assets/scripts/ui-screenfull.js"></script>
<script src="assets/scripts/ui-scroll-to.js"></script>
<script src="assets/scripts/ui-toggle-class.js"></script>

<script src="assets/scripts/app.js"></script>


<!-- ajax -->
<script src="libs/jquery/jquery-pjax/jquery.pjax.js"></script>
<script src="assets/scripts/ajax.js"></script>

<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>

<script src="mqtt_devices/AJAX/airconditioner.js"></script>

</body>
</html>
