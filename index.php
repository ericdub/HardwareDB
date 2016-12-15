<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">


    <title>InfoSys IP Inventory</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="theme.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body role="document">


    <!-- Fixed navbar -->
   <nav class="navbar navbar-inverse navbar-fixed-top">
     <div class="container">
       <div class="navbar-header">
         <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
           <span class="sr-only">Toggle navigation</span>
           <span class="icon-bar"></span>
           <span class="icon-bar"></span>
           <span class="icon-bar"></span>
         </button>
         <a class="navbar-brand" href="#">Sort/Filter:</a>
       </div>
       <div id="navbar" class="navbar-collapse collapse">
         <ul class="nav navbar-nav">
           <li class="active"><a href="?order=HardwareName">By Name</a></li>
           <li><a href="?order=HardwareLocationDept">By Department</a></li>
           <li><a href="?order=HardwareIP">By IP</a></li>
           <li class="dropdown">
             <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">By Location <span class="caret"></span></a>
             <ul class="dropdown-menu">
               <li><a href="?location=3">Buttonwood</a></li>
               <li><a href="?location=4">Crossroads</a></li>
               <li><a href="?location=1">Downtown</a></li>

               <li><a href="?location=7">Eastland</a></li>
                <li><a href="?location=6">Edgewood</a></li>
                <li><a href="?location=2">Smiley</a></li>
                <li><a href="?location=5">Southwest</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="?location=8">Mobile Device</a></li>

             </ul>
           </li>
            <li><a href="?ipstatus=open&order=HardwareIP">Unused IPs</a></li>
            <li>
              <form class="form-inline" method="post" style="padding-top: 7px;
    padding-bottom: 7px;">
              <div class="form-group">
                <label class="sr-only" for="searchTerm">Search Term</label>
                <div class="input-group">
                  <div class="input-group-addon">Look for: </div>
                  <input type="text" class="form-control" id="searchTerm" name="searchTerm" placeholder="255.255.255.255, BwTeller1">

                </div>
              </div>
              <button type="submit" class="btn btn-primary">Search</button>
            </form>
            </li>
         </ul>
       </div><!--/.nav-collapse -->
     </div>
   </nav>

    <div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>MCU IT IP/Hardware Directory</h1>

      </div>



      <div class="row">

        <div class="col-md-12">

          <?php





          $sortorder = isset($_GET['sortorder'])?$_GET['sortorder']:'asc';

          echo '<table class="table table-striped">
           <tr><th><a href="?order=HardwareName&sortorder='.(($sortorder=='desc')?'asc':'desc').'">Name</a></th><th><a href="?order=HardwareIP&sortorder='.(($sortorder=='desc')?'asc':'desc').'">IP</a></th>
             <th><a href="?order=HardwareLocationDept&sortorder='.(($sortorder=='desc')?'asc':'desc').'">Department</a></th>
             <th><a href="?order=HardwareLocation&sortorder='.(($sortorder=='desc')?'asc':'desc').'">Location</a></th></tr>
          ';
                    $order = $_GET['order'];

                    $location = $_GET['location'];
                    $department = $_GET['department'];
                    $ipStatus = $_GET['ipstatus'];
                    $searchTerm = $_POST['searchTerm'];
                    $dbName = "C:/some/path/xxxxxxx.mdb";

                    if (!file_exists($dbName)) {
                        die("Could not find database file.");
                    }
                    $db = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=$dbName; Uid=; Pwd=;");


                    if(!empty($searchTerm)){

                      $sql  = "SELECT HardwareID, HardwareIP,   HardwareCurrentUser, HardwareName, HardwareLocation,
                      HardwareLocationDept FROM tblHardwares  WHERE HardwareIP LIKE '%". $searchTerm."%' OR HardwareName LIKE '%". $searchTerm."%' ORDER BY HardwareName";


                    } else {

                          $sql  = "SELECT HardwareID, HardwareIP, HardwareCurrentUser, HardwareName, HardwareLocation, HardwareLocationDept FROM tblHardwares  ";

                          if($ipStatus == 'open') {

                             $sql .= "WHERE HardwareName IS NULL AND HardwareIP <> ''";
                             $order = "HardwareID";
                          } else {

                             $sql .= "WHERE HardwareName <> '' ";

                          }

                          if(!empty($location)){
                            $sql .= " AND HardwareLocation = $location";

                          }

                          if(!empty($department)){
                            $sql .= " AND HardwareLocationDept = '$department'";

                          }



                            if(!empty($order)){



                              $sql .= " ORDER BY ".$order. " ".$sortorder;


                            } else {
                              $sql .= " ORDER BY HardwareName, HardwareLocation, HardwareID ".$sortorder;
                            }

                }

          $result = $db->query($sql);

          

          while ($row = $result->fetch()) {

              $hardwareName = $row["HardwareName"];
              $hardwareIP = $row["HardwareIP"];

              $hardwareDepartment = $row["HardwareLocationDept"];
              $hardwareLocation = $row["HardwareLocation"];

              if ($hardwareLocation == 1) {
                $hardwareLocationAlpha = "Downtown";
              } elseif
               ($hardwareLocation == 2) {
                $hardwareLocationAlpha = "Smiley";
              } elseif
               ($hardwareLocation == 3) {
                $hardwareLocationAlpha = "Buttonwood";
              } elseif
                ($hardwareLocation == 4) {
                $hardwareLocationAlpha = "Crossroads";
              } elseif
              ($hardwareLocation == 5) {
                $hardwareLocationAlpha = "Southwest";
              } elseif
               ($hardwareLocation == 6) {
              $hardwareLocationAlpha = "Edgewood";
            } elseif
               ($hardwareLocation == 7) {
                $hardwareLocationAlpha = "Eastland";
              } elseif
                ($hardwareLocation == 8) {
                 $hardwareLocationAlpha = "Mobile";
              }
              elseif
                ($hardwareLocation == 9) {
                 $hardwareLocationAlpha = "Downtown RE";
              }

              echo '<tr><td>'.$hardwareName. '</td><td> '.$hardwareIP. '</td><td><a href="?department='.$hardwareDepartment.'">'.$hardwareDepartment. '</a></td><td><a href="?location='.$hardwareLocation.'">'.$hardwareLocationAlpha. '</td></tr>' ;
          }

          echo '</table>';



          odbc_close ($db);

          ?>
        </div>
      </div>





    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <script src="assets/js/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
