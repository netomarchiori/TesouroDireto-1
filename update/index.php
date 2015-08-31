<html>
<head>
  <meta http-equiv="refresh" content="300">
</head>
<body>
  <?php
  echo "Last reload: ".date('Y-m-d H:i:s');
   ?>
  <pre><?php

  require_once("../db/db.php");
  require_once("../dropbox_token.php");
  $db = new TesouroDB("../db/Tesouro.db");

  // dropbox
  require_once "../dropbox/autoload.php";
  use \Dropbox as dbx;
  $dbxClient = new dbx\Client($accessToken, "PHP-Example/1.0");


  $f = fopen("Tesouro.db", "w+b");
  if (flock($f, LOCK_EX)) {  // acquire an exclusive lock
    ftruncate($f, 0);      // truncate file
    $fileMetadata = $dbxClient->getFile("/Tesouro.db", $f);
    fflush($f);            // flush output before releasing the lock
    flock($f, LOCK_UN);    // release the lock
  } else {
    echo "Couldn't get the lock!";
  }
  fclose($f);

  $results = $db->exec("ATTACH '".getcwd()."\Tesouro.db' as toMerge;
      BEGIN;
        insert INTO taxa (titulo_id, valor, timestamp) SELECT titulo_id, valor, timestamp from toMerge.taxa;
      COMMIT;
      DETACH toMerge;");

  require_once("simple_html_dom.php");
  $html = file_get_html('http://www.tesouro.fazenda.gov.br/tesouro-direto-precos-e-taxas-dos-titulos');

  $results = $db->query('SELECT * FROM "titulo"');
  while ($row = $results->fetchArray()) {
    foreach($html->find('.listing0') as $e1) {
      if(strpos($e1->innertext, $row['nome']) !== false) {
          $taxa = str_replace(",", ".", $e1->parent()->children(2)->innertext());
          echo $row['id']. "\t" . $taxa. "<br>";
          $stmt = $db->prepare('INSERT INTO taxa (titulo_id, valor, timestamp) VALUES (:titulo_id, :taxa, :timestamp)');
          $stmt->bindValue(':titulo_id', $row['id'], SQLITE3_INTEGER);
          $stmt->bindValue(':taxa', strval($taxa));
          $stmt->bindValue(':timestamp', time());
          $stmt->execute();
          break;
      }
    }
  }

  $f = fopen("../db/Tesouro.db", "rb");
  if (flock($f, LOCK_EX)) {  // acquire an exclusive lock
    $dbxClient->uploadFile("/Tesouro.db", dbx\WriteMode::force(), $f);
    fflush($f);            // flush output before releasing the lock
    flock($f, LOCK_UN);    // release the lock
  } else {
    echo "Couldn't get the lock!";
  }
  fclose($f);

  $db->close();

  unlink("Tesouro.db");
   ?>
  </pre>
</body>
</html>
