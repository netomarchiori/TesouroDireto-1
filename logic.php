<?php
//error_reporting(0);
require_once("/db/db.php");
$db = new TesouroDB('db/Tesouro.db');

$r = $db->query("SELECT ti.id, ti.nome, ti.tipo, ti.ano, ti.vencimento, ti.cor, ti.cor_HEX, ta.valor FROM titulo ti INNER JOIN taxa ta ON (ti.id = ta.titulo_id) GROUP BY titulo_id ORDER BY ti.id ASC, ta.timestamp DESC");
while($row = $r->fetchArray()){
  $i = $row['id'];
  $titulos[$i]['id'] = $row['id'];
  $titulos[$i]['nome'] = $row['nome'];
  $titulos[$i]['tipo'] = $row['tipo'];
  $titulos[$i]['ano'] = $row['ano'];
  $titulos[$i]['vencimento'] = $row['vencimento'];
  $titulos[$i]['valor'] = $row['valor'];
  $titulos[$i]['cor'] = $row['cor'];
  $titulos[$i]['cor_HEX'] = $row['cor_HEX'];
}
if(isset($titulos[$_GET['id']])){
  $id = $_GET['id'];
} else {
  $id = 1;
}

$r = $db->query("SELECT * FROM (SELECT * from taxa WHERE titulo_id=".$id." ORDER BY timestamp DESC limit 15) order by timestamp ASC");
$i=0;
while ($row = $r->fetchArray()) {
  //var_dump($row);
  $labels[]=date("Y-m-d", $row['timestamp']);
  $data[]=$row['valor'];
}

function hex2rgba($color, $opacity = false) {

 $default = 'rgb(0,0,0)';

 //Return default if no color provided
 if(empty($color))
          return $default;

 //Sanitize $color if "#" is provided
        if ($color[0] == '#' ) {
         $color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity){
         if(abs($opacity) > 1)
         $opacity = 1.0;
         $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
         $output = 'rgb('.implode(",",$rgb).')';
        }

        //Return rgb(a) color string
        return $output;
}
/*
$results = $db->query('SELECT * FROM titulo');
while ($row = $results->fetchArray()) {
  $stmt = $db->prepare('SELECT * FROM taxa where titulo_id=:titulo_id');
  $stmt->bindValue(':titulo_id', $row['id'], SQLITE3_INTEGER);
  $results2 = $stmt->execute();
  while($row2 = $results2->fetchArray()){
    echo $row2['valor'].' ';
  }
  echo '<br>';
}
*/
?>
