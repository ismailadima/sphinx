<?php

require_once('c:/sphinx/share/doc/api/sphinxapi.php');

$data = null;
$query = '';

if(isset($_GET['q'])){
  
  $sphinxClient = new SphinxClient();
  $sphinxClient->SetServer("localhost",9312);
  $sphinxClient->SetRankingMode ( SPH_RANK_SPH04 );
  $query = $sphinxClient->EscapeString($_GET['q']);
  $data = $sphinxClient->query($query);


}
  
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sphinx</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

  </head>
  <body>
    <br>
    <br>
    <div class="container">
      <form class="navbar-form navbar-left" role="search">
        <div class="form-group">
          <img src="images/kucing.png" width="100px" height="100px">
          <input placeholder="Search" type="text" class="form-control" name="q" value="<?= $query ?>" style="width: 500px;">
          <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Search</button>
        </div>
      </form>
    </div>
    <br>
    <br>
    <br>
    <div class="container">
      <div class="list-group">
      <?php if($data['total_found']!= '0'){ 
        $matches = $data['matches'];
          foreach ($matches as $match) {
      ?>
          <a target="_blank" href="<?= $match['attrs']['link'] ?>" class="list-group-item" style="border: none;">
            <h4 class="list-group-item-heading" style="color: #0000FF;"><?= $match['attrs']['title'] ?></h4>
            <p class="list-group-item-text" style="color: #008000;"><?= $match['attrs']['link'];?></p>
            <p class="list-group-item-text" ><?= $match['attrs']['description'] ?></p>
            <p class="list-group-item-text">Keywords: <?= $match['attrs']['keywords'] ?></p>
          </a>
          <br>
      <?php
        }} else {
      ?>
        <h4>Pencarian dengan keyword : <b><?php echo $_GET['q']; ?></b> tidak ditemukan.</h4>
      <?php
        }
      ?>
      </div>
    </div>
    <script src="bootstrap/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>