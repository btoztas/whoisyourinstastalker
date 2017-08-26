<html>
  <body>
    <center>


<?php

  include 'simple_html_dom.php';

  define('BASE_LINK', 'https://piknu.com');
  define('USER_TAB', '/u/');


  function printStats()
  {
    echo '<table border="1"><tr><th>Photo</th><th>User</th><th>Likes</th></tr>';


  }

  function getNextPageHtml()
  {

    //

  }


  function getBaseHtml($user)
  {
    $userPage = BASE_LINK.USER_TAB.$user;
    return file_get_html($userPage);
  }

  function scanLikes($likes)
  {
    foreach ($likes->find('li') as $like) {
      $userName = $like->find('a', 0)->href;
      $userPhoto = $like->find('a', 0)->find('img',0)->src;

      if(isset($GLOBALS['like'][$userName])) {
        $GLOBALS['like'][$userName]['count']++;
      }else {
        $GLOBALS['like'][$userName]['count'] = 1;
        $GLOBALS['like'][$userName]['image'] = $userPhoto;
      }
    }
  }

  function scanPhotoPage($photoPage)
  {
    $photoHtml = file_get_html($photoPage);

    // TODO: numberoflikes has a <p> with total page likes
    $likesDiv = $photoHtml->find('div[id="numberoflikes"]', 0)->find('div[class="clearfix"]', 0);
    scanLikes($likesDiv);

  }

  function scanPage($html)
  {
    $photoGrid = $html->find('div[id="photogrid"]', 0);

    foreach ($photoGrid->find('div[class="photo"]') as $photo) {
      $photoPageReference = $photo->find('a[href^="/m"]', 0)->href;
      $photoPage = BASE_LINK.$photoPageReference;
      scanPhotoPage($photoPage);
    }

  }

  $user = $_GET['user'];
  echo "<h1> $user's Statistics </h1>";
  $userHtml = getBaseHtml($user);
  scanPage($userHtml);
  var_dump($GLOBALS['like']);

?>
