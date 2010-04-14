<?php
/*
Plugin Name: German Financial News
Plugin URI: http://www.arbeitsgemeinschaft-finanzen.de/plugins.php
Description: A customizeable widget which displays the latest news from the financial sector, provided by <a href="http://www.arbeitsgemeinschaft-finanzen.de/">http://www.arbeitsgemeinschaft-finanzen.de/</a>
Version: 0.1
Author: Thomas Nissen
Author URI: http://www.arbeitsgemeinschaft-finanzen.de/
License: GPL3
*/

function finanznews()
{
  $options = get_option("widget_finanznews");
  if (!is_array($options)){
    $options = array(
      'title' => 'German Financial News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Objekt erzeugen 
  $rss = simplexml_load_file( 
  'http://www.arbeitsgemeinschaft-finanzen.de/feed.xml'); 
  ?> 
  
  <ul> 
  
  <?php 
  // maximale Anzahl an News, wobei 0 (Null) alle anzeigt
  $max_news = $options['news'];
  // maximale Länge, auf die ein Titel, falls notwendig, gekürzt wird
  $max_length = $options['chars'];
  
  // RSS Elemente durchlaufen 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Titel in Zwischenvariable speichern
    $title = $i->title;
    // Länge des Titels ermitteln
    $length = strlen($title);
    // wenn der Titel länger als die vorher definierte Maximallänge ist,
    // wird er gekürzt und mit "..." bereichert, sonst wird er normal ausgegeben
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>" target="_blank"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  <li><a href="http://www.arbeitsgemeinschaft-finanzen.de/" target="_blank">Alle Nachrichten anzeigen</a></li>
  
  </ul>
<?php  
}

function widget_finanznews($args)
{
  extract($args);
  
  $options = get_option("widget_finanznews");
  if (!is_array($options)){
    $options = array(
      'title' => 'German Financial News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  finanznews();
  echo $after_widget;
}

function finanznews_control()
{
  $options = get_option("widget_finanznews");
  if (!is_array($options)){
    $options = array(
      'title' => 'German Financial News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['finanznews-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['finanznews-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['finanznews-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['finanznews-CharCount']);
    update_option("widget_finanznews", $options);
  }
?> 
  <p>
    <label for="finanznews-WidgetTitle">Widget Title: </label>
    <input type="text" id="finanznews-WidgetTitle" name="finanznews-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="finanznews-NewsCount">Max. News: </label>
    <input type="text" id="finanznews-NewsCount" name="finanznews-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="finanznews-CharCount">Max. Characters: </label>
    <input type="text" id="finanznews-CharCount" name="finanznews-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="finanznews-Submit"  name="finanznews-Submit" value="1" />
  </p>
  
<?php
}

function finanznews_init()
{
  register_sidebar_widget(__('German Financial News'), 'widget_finanznews');    
  register_widget_control('German Financial News', 'finanznews_control', 300, 200);
}
add_action("plugins_loaded", "finanznews_init");
?>
