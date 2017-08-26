<html>
  <body>
    <center>


<?php

  include 'simple_html_dom.php';

  define('BASE_LINK', 'https://piknu.com');
  define('USER_TAB', '/u/');


  function printStats($stats)
  {

    //

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

  function scanLikes($likes, $stats)
  {
    foreach ($likes->find('li') as $like) {
      $userName = $like->find('a', 0)->href;
      $userPhoto = $like->find('a', 0)->find('img',0)->src;

      if(isset($stats[$userName])) {
        $stats[$userName]['count']++;
      }else {
        $stats[$userName]['count'] = 1;
        $stats[$userName]['image'] = $userPhoto;
      }
    }
    return $stats;
  }

  function scanPhotoPage($photoPage, $stats)
  {
    $photoHtml = file_get_html($photoPage);

    // TODO: numberoflikes has a <p> with total page likes
    $likesDiv = $photoHtml->find('div[id="numberoflikes"]', 0)->find('div[class="clearfix"]', 0);
    return scanLikes($likesDiv, $stats);

  }

  function scanPage($html, $stats)
  {
    $photoGrid = $html->find('div[id="photogrid"]', 0);

    foreach ($photoGrid->find('div[class="photo"]') as $photo) {
      $photoPageReference = $photo->find('a[href^="/m"]', 0)->href;
      $photoPage = BASE_LINK.$photoPageReference;
      $stats = scanPhotoPage($photoPage, $stats);
    }

    return $stats;
  }
  $stats = array();
  $user = $_GET['user'];
  echo "<h1> $user's Statistics </h1>";
  $userHtml = getBaseHtml($user);
  $stats = scanPage($userHtml, $stats);

  echo '<table border="1"><tr><th>Photo</th><th>User</th><th>Likes</th></tr>';
  foreach ($stats as $user => $info) {
    $image = $info['image'];
    $count = $info['count'];
    echo "<tr>
      <td><img src='$image'/></td>
      <td>$user</td>
      <td>$count</td>
    </tr>";
  }
  echo "</table>";

?>
