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
	$stats[$userName]['name']  = $userName;
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

  function cmp($a, $b)
  {
    if ($a['count'] == $b['count']) {
      return 0;
    }
    return ($a['count'] > $b['count'] ) ? -1 : 1;
  }

  usort($your_data, "cmp");
  $stats = array();
  $user = $_GET['user'];
  $date = date('Y-m-d H:i:s');
  $ip = $_SERVER['REMOTE_ADDR'];
  exec("echo [$date] // $ip // $user >> users.log");
  echo "<h1> $user's Statistics </h1>";
  $userHtml = getBaseHtml($user);
  $stats = scanPage($userHtml, $stats);

  echo '<table border="1"><tr><th>Photo</th><th>User</th><th>Likes</th></tr>';
  usort($stats, "cmp"); 
  foreach ($stats as $info) {
    $name  = $info['name'];
    $name  = preg_replace('/\/u\//', '', $name);
    $image = $info['image'];
    $count = $info['count'];
    echo "<tr>
      <td><img src='$image'/></td>
      <td><a href='https://instagram.com/$name'>$name</a></td>
      <td>$count</td>
    </tr>";
  }
  echo "</table>";

?>
