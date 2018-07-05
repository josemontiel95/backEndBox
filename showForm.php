<?php
  include_once("configSystem.php");
  $u = $dbS->qAll("
    SELECT
      *
    FROM
      Tables
    WHERE
      ES_PUBLICA=1
    ");
  $activeTable="";
  $LlavePrimaria="";
  $LlavePrimariaNombre="";
  /*
  = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 
  = 0 = 0 = 0 = 0 = 0 = 0 =      GET      = 0 = 0 = 0 = 0 = 0 = 0 
  = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 
  */
  if(!empty($_GET)){
    //Present the table indicated by $_GET['ID_Table'], decurl() para perder el codificado
    $activeTable= decurl($_GET['ID_Table']);
    $LlavePrimaria= decurl($_GET['LlavePrimaria']);

  }
  else{
    //Present first table.
    foreach ($u as $row) {
      $activeTable= $row['ID_Table'];
      break;
    }
  }
  if($activeTable != ""){
    /*
    = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 
    =                 **** Query Construction ****
    = Considerations:
    =   1. Completety generic
    =       1.1 Consider many columns
    =       1.2 Consider many Foreign Keys
    =   2. There are 3 parts of a SELECT query
    =       2.1  SELECT -->Colunms with renaming for 
    =                      PUBLIC_NAMES AND ONLY 
    =                      DISPLAY PUBLIC_COLUMNS
    =       2.2  FROM   -->Tables
    =       2.3  WHERE  -->JOIN TABLES from the FROM statemente
    =
    = As point 2 points out, we will construct our query in tree parts 
    =
    = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 = 0 
    */

    $s= $dbS->qAll("
      SELECT 
        ID_Column,
        TABLE_NAME_ID,
        COLUMN_NAME,
        DATA_TYPE,
        PUBLIC_NAME,
        COLUMN_KEY,
        REFERENCED_TABLE_NAME,
        PUBLIC_REFERENCED_COLUMN,
        REFERENCED_COLUMN_NAME,
        CHARACTER_MAXIMUM_LENGTH,
        IS_NULLABLE,
        TIENE_ENUMS,
        ES_PUBLICA
      FROM 
        Columns 
      WHERE 
        TABLE_NAME_ID = '1QQ'
      ",
      array($activeTable)
      );
    $SELECT="SELECT ";
    $FROMARR= array();
    $FROM=" FROM ".$activeTable;
    $FROMARR[]=$activeTable;
    $WHERE_Flag= "";
    $WHERE=" WHERE ";
    $SELECT_Flag= "";
    $headers= array();
    $size= array();
    $ReferencedTables= array();
    $PublicReferencedColumns= array();
    $ReferencedColumns= array();
    $IS_NULLABLE= array();
    $COLUMN_ID=array();



    foreach ($s as $row) {
      if($row['IS_NULLABLE']==="NO" && $row['TIENE_ENUMS']==="NO" && $row['DATA_TYPE']!=="date"){
        $IS_NULLABLE[$row['PUBLIC_NAME']]=$row['IS_NULLABLE'];
      }
      /*
      ========= 0 = 0 = 0 = 0 
      =========   SELECT
      ========= 0 = 0 = 0 = 0 
      */
      if($row['COLUMN_KEY']!="MUL"){
        if($row['COLUMN_KEY']==="PRI"){
          $LlavePrimariaNombre= $row['COLUMN_NAME'];
        }
        if($SELECT_Flag==""){
          $SELECT_Flag= ".";
          $tmp= $row['TABLE_NAME_ID'].".".$row['COLUMN_NAME']." as '".$row['PUBLIC_NAME']."'";
          $headers[$row['PUBLIC_NAME']]= $row['DATA_TYPE'];
          if($row['TIENE_ENUMS']==="YES"){
            $headers[$row['PUBLIC_NAME']]= "enum";
            $COLUMN_ID[$row['PUBLIC_NAME']]=$row['ID_Column'];
          }
          $size[$row['PUBLIC_NAME']]=$row['CHARACTER_MAXIMUM_LENGTH'];
          $SELECT= $SELECT." ".$tmp;
        }
        else{
          $tmp= $row['TABLE_NAME_ID'].".".$row['COLUMN_NAME']." as '".$row['PUBLIC_NAME']."'";
          $headers[$row['PUBLIC_NAME']]= $row['DATA_TYPE'];
          if($row['TIENE_ENUMS']==="YES"){
            $headers[$row['PUBLIC_NAME']]= "enum";
            $COLUMN_ID[$row['PUBLIC_NAME']]=$row['ID_Column'];
          }
          $size[$row['PUBLIC_NAME']]=$row['CHARACTER_MAXIMUM_LENGTH'];
          $SELECT= $SELECT.", ".$tmp;
        }
      }
      else{
        $t= $dbS->qvalue("
          SELECT 
            PUBLIC_NAME 
          FROM 
            Columns 
          WHERE 
            COLUMN_NAME = '1QQ' AND
            TABLE_NAME_ID = '1QQ'
            ",
            array(
              $row['PUBLIC_REFERENCED_COLUMN'],
              $row['REFERENCED_TABLE_NAME']
              )
            );
        if($SELECT_Flag==""){
          $SELECT_Flag= ".";
          $tmp= $row['REFERENCED_TABLE_NAME'].".".$row['PUBLIC_REFERENCED_COLUMN']." as '".$t."'";
          $headers[$t]= "MUL";
          $ReferencedTables[$t]         = $row['REFERENCED_TABLE_NAME'];
          $PublicReferencedColumns[$t]  = $row['PUBLIC_REFERENCED_COLUMN'];
          $ReferencedColumns[$t]        = $row['REFERENCED_COLUMN_NAME'];
          $SELECT= $SELECT." ".$tmp;
        }
        else{
          $tmp= $row['REFERENCED_TABLE_NAME'].".".$row['PUBLIC_REFERENCED_COLUMN']." as '".$t."'";
          $headers[$t]= "MUL";
          $ReferencedTables[$t]         = $row['REFERENCED_TABLE_NAME'];
          $PublicReferencedColumns[$t]  = $row['PUBLIC_REFERENCED_COLUMN'];
          $ReferencedColumns[$t]        = $row['REFERENCED_COLUMN_NAME'];
          $SELECT= $SELECT.", ".$tmp;
        }
        /*
        ========= 0 = 0 = 0 = 0 
        =========   FROM
        ========= 0 = 0 = 0 = 0 
        */
        if(!in_array($row['REFERENCED_TABLE_NAME'], $FROMARR)){  //No concatenes la misma tabla dos veces.
           $FROM = $FROM.", ".$row['REFERENCED_TABLE_NAME'];
           $FROMARR[]=$row['REFERENCED_TABLE_NAME'];
        }
        /*
        ========= 0 = 0 = 0 = 0 
        =========   WHERE
        ========= 0 = 0 = 0 = 0 
        */
        if($WHERE_Flag==""){
          $WHERE_Flag= ".";
          $WHERE = $WHERE." ".$row['TABLE_NAME_ID'].".".$row['COLUMN_NAME']." = ".$row['REFERENCED_TABLE_NAME'].".".$row['REFERENCED_COLUMN_NAME'];
        }
        else{
          $WHERE = $WHERE." AND ".$row['TABLE_NAME_ID'].".".$row['COLUMN_NAME']." = ".$row['REFERENCED_TABLE_NAME'].".".$row['REFERENCED_COLUMN_NAME'];
        }
      }
    }
    
    echo'<script>console.log("'.$SELECT.'")</script>';
    echo'<script>console.log("'.$FROM.'")</script>';
    echo'<script>console.log("'.$WHERE.'")</script>';
    foreach ($headers as $title) {
      echo'<script>console.log("'.$title.'")</script>';
    }
    $query = " ";

    /*
      Construct query.
      Different to the tableShower.php, this has always a WHERE clouse, specifying
      the row to edit, we make use and obviete thae existance of a primary key.
    */ 
    if($WHERE_Flag==""){
      $WHERE = $WHERE." ".$activeTable.".".$LlavePrimariaNombre." = ".$LlavePrimaria;
      $query= $query.$SELECT.$FROM.$WHERE;
    }
    else{
      $WHERE = $WHERE." AND ".$activeTable.".".$LlavePrimariaNombre." = ".$LlavePrimaria;
      $query= $query.$SELECT.$FROM.$WHERE;
    }
    
    echo'<script>console.log("'.$query.'")</script>';

    /*
      ========= 0 = 0 = 0 = 0 
      =========   GET DB Details
      ========= 0 = 0 = 0 = 0 
    */
    $dbName= $dbS->qvalue("
      SELECT 
        BaseDeDatos
      FROM 
        BaseDeDatos
      ");
    $dbU = new MySQLSystem($dbName);
    $uu= $dbU->qarrayA($query);
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?= $activeTable?></title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="css/colorpicker.css" />
<link rel="stylesheet" href="css/datepicker.css" />
<link rel="stylesheet" href="css/jquery.timepicker.css" />
<link rel="stylesheet" href="css/uniform.css" />
<link rel="stylesheet" href="css/select2.css" />
<link rel="stylesheet" href="css/bootstrap-wysihtml5.css" />
<link rel="stylesheet" href="css/fullcalendar.css" />
<link rel="stylesheet" href="css/matrix-style.css" />
<link rel="stylesheet" href="css/matrix-media.css" />
<link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" href="css/jquery.gritter.css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>


</head>
<body>

<!--Header-part-->
<div id="header">
  <h1><a href="dashboard.html">Matrix Admin</a></h1>
</div>
<!--close-Header-part--> 


<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li class=""><a title="" href="menuAdmin.php"><i class="icon icon-cog"></i> <span class="text">Settings</span></a></li>
  </ul>
</div>
<!--close-top-Header-menu-->
<!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
  <ul>
    <?php
      foreach ($u as $row) {
        if($row['ID_Table']==$activeTable){
          echo'<li class="active"><a href="tableShower.php?ID_Table='.encurl($row['ID_Table']).'"><i class="icon icon-th"></i> <span>'.$row['PUBLIC_NAME'].'</span></a> </li>';
        }
        else{
          echo'<li><a href="tableShower.php?ID_Table='.encurl($row['ID_Table']).'"><i class="icon icon-th"></i> <span>'.$row['PUBLIC_NAME'].'</span></a> </li>';
        }
      }
    ?>
  </ul>
</div>
<!--sidebar-menu-->

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Tables</a> </div>
    <h1>Editando <?= $activeTable?></h1>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">   
    <hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Personal-info</h5>
          </div>
          <div class="widget-content nopadding">
            <form action="catcherForm.php?ID_Table=<?=encurl($activeTable)?>" method="post" class="form-horizontal">

              <?php
                $types= new Types();

                /*
                 Iterate the generic query.
                 All rows are on $uu, that is an array if columns of 
                 the first (and only) row.

                 ColumnName is the name of the column, but is the public information,
                 This can be configured by the user.

                 headers is and array that has a very different use that the name implies.
                 headers was reused from the tableShower. At the time of reuse, I thought 
                 that this would be the only array and lazy my i did not change the name.

                 Well, headers is an array that saves the datatype that will be used later on 
                 a switch case to identify input type.
                */

                foreach ($uu as $ColumnName => $ColumnData) {
                  echo '<div id="E'.$ColumnName.'" class="control-group">';
                    echo '<label for="'.$ColumnName.'" class="control-label">'.$ColumnName.' :</label>';
                    echo '<div class="controls">';
                      switch($headers[$ColumnName]){
                        case "int":
                        case "decimal":
                        case "numeric":
                        case "smallint":
                          //get number
                          echo $types->getNumber($ColumnName, $ColumnData);
                          break;

                        case "float":
                        case "real":
                        case "double precision":
                          //getDecimal
                          echo $types->getDecimal($ColumnName, $ColumnData);
                          break;
                        case "char":
                        case "varchar":{
                          $size1=$size[$ColumnName];
                          echo $types->getChar($ColumnName, $size1, $ColumnData);
                          break;
                        }
                        case "text":{
                          echo $types->getText($ColumnName, $ColumnData);
                          break;
                        }
                        case "date":{
                          echo $types->getDate($ColumnName, $ColumnData);
                          break;
                        }
                        case "enum":{
                          $enum= $dbS->qAll("
                            SELECT 
                              ID_InputSpecifics,
                              COLUMN_ID,
                              Input,
                              InputPublic
                            FROM
                              InputSpecifics
                            WHERE
                              COLUMN_ID= '1QQ'
                            ",
                            array(
                              $COLUMN_ID[$ColumnName]
                              )
                            );
                          if($enum!="empty"){
                            echo '<select name="'.encurl($ColumnName).'">';
                              foreach ($enum as $row) {
                                if($row['Input']==$ColumnData){
                                  echo '<option selected value="'.$row['Input'].'">'.$row['InputPublic'].'</option>';
                                }
                                else{
                                  echo '<option value="'.$row['Input'].'">'.$row['InputPublic'].'</option>';
                                }
                              }
                            echo '</select>'; 
                          }
                          else{

                          }
                          break;

                        }
                        case "MUL":{
                          echo '<script>console.log("'.$ReferencedColumns[$ColumnName].'");</script>';
                          echo '<script>console.log("'.$PublicReferencedColumns[$ColumnName].'");</script>';
                          echo '<script>console.log("'.$ReferencedTables[$ColumnName].'");</script>';
                          if($ReferencedColumns[$ColumnName]!=$PublicReferencedColumns[$ColumnName]){
                            $fk= $dbU->qAll("
                              SELECT 
                                1QQ,
                                1QQ
                              FROM
                                1QQ
                              ",
                              array(
                                $ReferencedColumns[$ColumnName],
                                $PublicReferencedColumns[$ColumnName],
                                $ReferencedTables[$ColumnName]
                                )
                              );
                            echo '<select name="'.encurl($ColumnName).'">';
                            foreach ($fk as $row) {
                              if($row[$PublicReferencedColumns[$ColumnName]]==$ColumnData){
                                echo '<option selected value="'.$row[$ReferencedColumns[$ColumnName]].'">'.$row[$PublicReferencedColumns[$ColumnName]].'</option>';
                              }
                              else{
                                echo '<option value="'.$row[$ReferencedColumns[$ColumnName]].'">'.$row[$PublicReferencedColumns[$ColumnName]].'</option>';
                              }
                            }
                            echo '</select>'; 
                          }
                          else{
                            $fk= $dbU->qAll("
                              SELECT 
                                1QQ
                              FROM
                                1QQ
                              ",
                              array(
                                $ReferencedColumns[$ColumnName],
                                $ReferencedTables[$ColumnName]
                                )
                              );
                            echo '<select name="'.encurl($ColumnName).'">';
                            foreach ($fk as $row) {
                              if($row[$PublicReferencedColumns[$ColumnName]]==$ColumnData){
                                echo '<option selected value="'.$row[$ReferencedColumns[$ColumnName]].'">'.$row[$ReferencedColumns[$ColumnName]].'</option>';
                              }
                              else{
                                echo '<option value="'.$row[$ReferencedColumns[$ColumnName]].'">'.$row[$ReferencedColumns[$ColumnName]].'</option>';
                              }
                            }
                            echo '</select>'; 
                          }
                          break;
                        }
                      }
                    echo '</div>';
                  echo '</div>';
                }


              ?>
              <div class="form-actions">
                <button type="submit" disabled="true" id="sendButton" class="btn btn-success">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

<!--Footer-part-->

<div class="row-fluid">
  <div id="footer" class="span12"> 2017 &copy; ??. Brought to you by Asher and Montiel </div>
</div>

<!--end-Footer-part-->
<script src="js/excanvas.min.js"></script> 
<script src="js/jquery.min.js"></script> 
<script src="js/jquery.ui.custom.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/jquery.flot.min.js"></script> 
<script src="js/jquery.flot.resize.min.js"></script> 
<script src="js/jquery.peity.min.js"></script> 
<script src="js/fullcalendar.min.js"></script> 
<script src="js/matrix.js"></script> 
<script src="js/matrix.dashboard.js"></script> 
<script src="js/jquery.gritter.min.js"></script> 
<script src="js/matrix.interface.js"></script> 
<script src="js/matrix.chat.js"></script> 
<script src="js/jquery.validate.js"></script> 
<script src="js/matrix.form_validation.js"></script> 
<script src="js/jquery.wizard.js"></script> 
<script src="js/jquery.uniform.js"></script> 
<script src="js/select2.min.js"></script> 
<script src="js/matrix.popover.js"></script> 
<script src="js/jquery.dataTables.min.js"></script> 
<script src="js/matrix.tables.js"></script> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
  <?php
    echo "$(document).ready(function(){ \n";
      echo "  $('#sendButton').prop('disabled', false); \n";  
    foreach ($IS_NULLABLE as $key => $value) {
      echo "    $('#".$key."').keyup(function(){ \n";
      echo "        $('#sendButton').prop('disabled', false); \n";  
        foreach ($IS_NULLABLE as $key2 => $value2) {
          echo "        if($('#".$key2."').val().length == 0){ \n";
          echo "            $('#sendButton').prop('disabled', true); \n";
          echo "            $('#E".$key2."').addClass('error'); \n";
          echo "        } else{ \n";
          echo "            $('#E".$key2."').removeClass('error'); \n";
          echo "        } \n";
        }
      echo "    }) \n";
    }
      echo "}); \n";
   ?>

</script>
</body>
</html>
