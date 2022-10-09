<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta http-equiv="Cache-Control" content="must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Postgres viewer</title>

<style type="text/css">
html {
    font-family:"Roboto",Tahoma,"Lucida Grande",Arial;
    font-size:10px;
    width:100%;
    height:80%;
}
body {
    overflow-y:inherit;
}
::-webkit-scrollbar-track { background:rgba(0, 0, 0, 0.1); }
::-webkit-scrollbar-thumb { background:rgba(0, 0, 0, 0.5); }

::-webkit-scrollbar-thumb {
    border-radius:9px;
}
::-webkit-scrollbar-thumb:hover {}

div.header { 
    margin:10px auto;
    padding:5px 10px 5px 10px;
    border:1px solid black;
    vertical-align:middle;
}
h1 {
    text-align:center;
    vertical-align:middle;
}
.black { color:black;}
.red { background-color:red; color:white; }
h2 {
    font-size:normal;
}
.number {
    background-color : #00C;
    color : #fff;
    padding:10px;
    margin-top:5px;
}
.info {
}
.debug {
    background-color : #000;
    color : #fff;
    padding:5px;
    font-size:1.8em;
}
.main {
    margin:20px auto;
    margin-bottom:20px;
    padding:10px;
    border:1px solid #ccc;
    border-radius:12px;
    max-height:380px;
    width:1200px;
    overflow-x:auto; overflow-y:auto; 
}
sizable-container {
    padding:10px;
}

#data-table::-webkit-scrollbar {
    width:10px;
    height:0px;
}

table.data {
    width: 100%;
    height:100%;
    border-collapse:collapse;
}
th {
    background-color : #0cc;
    color : #000;
    padding:5px;
    border:1px solid gray;
    font-weight:200;
    font-size:1.2em;
    cursor: pointer;
}
td {
    padding:5px;
    font-size:1.1em;
    font-weight:normal;
    border:1px solid #ccc;
    height: -webkit-fill-available;
    width: -webkit-fill-available;
}
.x0 { background-color : #aaa; }
.x1 { background-color : #fcc; }
.x2 { background-color : #eee; }
.x3 { background-color : #777; color:#fff; }

.x0, .x1, .x2 { text-align:center; }

.black { color:black;}
.red { background-color:#d70a0a; color:white; }

tr.odd  { background-color:#ececec; }
tr.even { background-color:#f7f7f7; }

</style>
</head>

<!-- Параметры соединения -->

<?php
$connection = "host=localhost dbname=debug user=mkaro password=admin";
$table = '"DIC_Sellers_tb"';
$limit = 100;
$IsDebug = 1;
$IsDeepDebug = 0;
?>

<!---->

<body>
<hr>
<div class="header red">
    <h1>POSTGRES DATABASE DATA VIEWER</h1>
</div>

<?php
  $db = pg_connect($connection) or die("Could not connect");
  
  $sql_meta = str_replace('"', '', $table);

  $meta = pg_meta_data($db, $sql_meta);

  $columns = array();

  foreach($meta as $key => $value) {
    array_push($columns, $key);
  }
  unset($key);
  unset($value);
  
  $sql_data = 'SELECT * FROM public.' . $table . (isset($limit) ? ' LIMIT ' . strval($limit) : '');

  $res = pg_query($db, $sql_data) or die("Cannot execute query: $sql_data\n");
?>

<div class="info">

<h2>Структура таблицы:</h2>

<?php

  if ($IsDebug) {
    print ("<span class=\"debug\">$sql_meta</span><br><br>");
  }

  if ($IsDeepDebug) {
    if (is_array($meta)) {
        echo '<pre>';
        var_dump($meta);
        echo '</pre>';
    }
  }
?>

<h2>Данные:</h2>

<?php
  if ($IsDebug) {
    print ("<span class=\"debug\">$sql_data</span><br><br>");
    if ($IsDeepDebug) {
        print_r($columns);
    }
}
?>

</div>
<div class="main">
  <div class="sizable-container">

    <table class="data" id="data-table" border=0>
    <thead>

<?php
    foreach($columns as $column) {
?>
        <th><?php echo $column; ?></th>
<?php
    unset($column);
}
?>
    </thead>

<?php

$i = 0;
while($row=pg_fetch_assoc($res)) {
    ++$i;
    if ($IsDeepDebug) {
        echo '<span class="number">' . strval($i) . '</span>';
        echo '<pre>';
        var_dump($row);
        echo '</pre>';
    }
?>

    <tr class="<?php print(($i % 2 == 0) ? 'even' : 'odd');?>">

<?php
$ids = array("Type","TID", "Price");
$mds = array("MD");
$prices = array("Price", "Total", "Tax");

    foreach($columns as $column) {
      if (in_array($column, $ids)) {
        echo '<td class="x1">';
      } 
      elseif (in_array($column, $mds)) {
        echo '<td class="x2">';
      } 
      elseif (in_array($column, $prices)) {
        echo '<td class="x3">';
      }
      else {
        echo '<td>';
      }
        echo $row[$column];
?>
        </td>
<?php
    }
?>
    </tr>
<?php
}
?>
    </table>

    </div>
</div>

<br>

</body>
</html>
