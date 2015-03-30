<?php 
define('DEBUG_CONSOLE_HIDE',1);
$json = json_decode(file_get_contents('php://input'));
if($json){
    $entries = $json->log->entries;
    foreach($entries as $entry)
    {
        $urls[]=$entry->request->url;
    }
    if(isset($urls[0]))
    {
         $info = parse_url($urls[0]);
         //$dir = '../../gm/'.$info['host'];
         $dir = $info['host'];
         if(!file_exists($dir) && $info['host'])mkdir($dir);
         $file = $dir.'/conf.php';
         $content = '<?php return ' . var_export($urls, 1) . ';?>';
         file_put_contents($file, $content);
    }
}
    