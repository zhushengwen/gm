<?php
ignore_user_abort();
set_time_limit(0);
error_reporting(0);
setcookie('code','encodeURIComponent(document.cookie)');
if(isset($_COOKIE['Cookie']))
{
    $cookie=$_COOKIE['Cookie'];
    $cookie = explode(';',$cookie);
    foreach($cookie as $key => $val)
    {
        $i=strpos($val,'=');
        if($i)
        {
            $k = trim(substr($val,0,$i));
            $v = substr($val,$i+1);
            setcookie($k,$v);
        }

    }
}

function ExtractStr( $resource,  $name,  $stas,  $ends,  $ids = 1 ,$com=",")
{
	 $str = "";
	 $index = 0;
	//首先定位到名称
	while ($ids != 0)
	{
		$ids--;
		if($name=="")$bgn=$index;else
		$bgn =strpos($resource,$name, $index);
		
		//如果未找到直接返回
		if ($bgn !== false)
		{
			//再次定位到开始字符
			$sta =  strpos($resource,$stas, $bgn + strlen($name));
			if ($sta !== false)
			{
				//建立栈结构,开始字符和结束字符分别进行压栈出栈
				$i = 1;
				$sta += strlen($stas)-1;
				$index = $sta + 1;
				$tmps = "";
				while (0 != $i && $index < strlen($resource))
				{
					if ($index + strlen($ends) > strlen($resource)) break;
					$tmps = substr($resource,$index, strlen($ends));
					if ($tmps == $ends)
					{
						$i--;
						if (0 == $i) break;
						$index++;
						continue;
					}
					if ($index + strlen($stas) > strlen($resource)) break;
					$tmps = substr($resource,$index, strlen($stas));
					if ($tmps == $stas)
					{
						$i++;
					}
					$index++;
				}
				if (0 == $i && $index <= strlen($resource))
				{
					$str .= substr($resource,$sta + 1, $index - $sta - 1);
					if ($ids != 0) $str .= $com;
				}
			}
		}
	}
	return $str;
}

function post_xml($data,$url,$header=false,$show=true,$post=true,$get_c=false){	
$dir = 'cache';
$path=dirname(__FILE__).DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR;
if(!file_exists($path))mkdir($path);


//$cookie_file = dirname(__FILE__).'/cookie.txt';
$ch = curl_init(); //初始化curl		
curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 
curl_setopt($ch, CURLOPT_URL, $url);//设置链接		
if($header)
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//设置HTTP头	
curl_setopt($ch, CURLOPT_HEADER, $show);//设置显示返回的http头 	
if($get_c)
curl_setopt($ch, CURLOPT_COOKIEFILE, $path.$get_c);
else
curl_setopt($ch, CURLOPT_COOKIEJAR, $path.md5($url));

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
if($post)
{ 
curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式			
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//POST数据	
}
ob_start(); //开启浏览器缓存
$response = curl_exec($ch);//接收返回信息		
if(curl_errno($ch))
{
//出错则显示错误信息			 
print curl_error($ch);	
}	
curl_close($ch); //关闭curl链接	
ob_end_clean();

return $response;
}
/**
 * 读取文件
 * @param string $filename 路径
 * @param bool $isarray 为true则返回array
 * @return string/array
 */
function read($filename) {
    return file_exists($filename) ? include $filename : array();
}

/**
 * 写入文件
 * @param string $file 路径
 * @param string/array $content 为数组则以return array形式存放
 */
function write($file, $content) {
    if (is_array($content)) {
        $content = '<?php return ' . var_export($content, 1) . '?>';
    }
	
    file_put_contents($file, $content);
}
function rdv($start=0)
{
  $config = read($GLOBALS["cfg"]);
  return array_slice($config,intval($start));
}
function adv($value)
{

  $GLOBALS["array"][] = $value;
  write($GLOBALS["cfg"],$GLOBALS["array"]);
}

//session_start();
if(isset($_GET["url"]))
{
	 $bu=urldecode($_GET["url"]);
	 $inf=parse_url($bu);
     $GLOBALS["cfg"]=$inf["host"]."/config.php";
     $GLOBALS["beacon"]=$inf["host"]."/conf.php";
	 //if(isset($_GET['query']) && is_numeric($_GET['query']))
	 {
		echo json_encode(rdv($_GET['query']));
		exit;
	 }
}



//set_file_session();
function is_set_session(){
    return isset($_SESSION[md5($GLOBALS["cfg"])]);
}
function set_file_session(){
$_SESSION[md5($GLOBALS["cfg"])] = 1;
register_shutdown_function("delete_file_session");
}

function delete_file_session(){
    unset($_SESSION[md5($GLOBALS["cfg"])]);
}

//if($_POST)
unlink($GLOBALS["cfg"]);
?>

<!DOCTYPE html> 
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>网页模板抓取工具</title>
	<meta name="Keywords" content="网站模板抓取器，支持相对路径" />
	<meta name="Description" content="网页模板在线抓取" />
	<link rel="stylesheet" href="css/base.css" type="text/css"/>
	<link href="favicon.ico" type="image/vnd.microsoft.icon" rel="icon">
	<style>.header{min-width: 990px;}</style>
</head>
<body style="background:url('img/bg.png') repeat-x scroll center top transparent">
<style>
.my-website-user-oplist{z-index:1000;}
.scan-info-tips{position:absolute; right:50px; top:44px; border:1px solid #bebebe; background:#fff; width:230px; z-index:100;-webkit-box-shadow: 0 2px 4px rgba(0,0,0,.2);box-shadow: 0 2px 4px rgba(0,0,0,.2);}
.scan-info-tips li{line-height:18px; padding:5px 10px; border-top:1px dashed #E2E2E2; position:relative; margin-top:-1px; color:#333;}
.scan-info-tips li a{color:#666; margin:0;font-size:12px;}
.scan-info-tips li a:hover{color:#000;}
.scan-info-tips .close-tips-btn{text-align:right;}
.scan-info-tips .close-tips-btn a{color:#999;font-size:12px;}
.webscan-menu li{_width: 85px;}
#menu-more{position:relative;}
#menu-more_a:hover{line-height:42px; padding-bottom:2px;}
#menu-more-list{position:absolute; left:-1px; top:41px; width:140px; padding-top:1px;}
#menu-more-list dl{background:#3764B2;border: 1px solid #31528A;border-top:1px solid #3764B2;box-shadow:0 3px 6px rgba(0, 0, 0, .25);}
#menu-more-list dd{line-height:30px; text-align:left; margin-left:0px}
#menu-more-list dd a{font-size:14px; color:#fff; padding:2px 10px; display:block;}
#menu-more-list dd a:hover{background:#32589B;}
#menu-more-list{display:none;}
</style>
<div id="headerbox">
		
</div>

	<div id="mianWrap" class="wrapper clearfix">
		<h2 id="bannerLogo">抓取网页，从这里开始</h2>
		<form class="clearfix" method="post"  name="checkwebsiteform" id="scan">
		<div id="param">POST <input type="checkbox" value="1" name='post' />  参数  <input name='param' type='text'style="width: 555px;"/></div>
			<span id="scanHttp">输入网址</span>
				<span id="scanInput"><input type="text" name="url" id="url" autocomplete="off" class="ac_input" style="color: rgb(0, 0, 0); font-size: 24px;"></span>
				<span id="scanSubmit"><a href="#"><span  id="submit">获取模板</span></a></span>
		<input type="submit" style="display:none;"/>
		</form>
		<div id="tj" class="clearfix">
			<?php
			
			
function packzip($file)
{

    $zip = new ZipArchive(); 
    $filename = "$file.zip"; 
	
    if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) { 
    echo ("无法创建 <$filename>\n"); }
    $files = listdir($file); 
    foreach($files as $path) 
    { 
    $zip->addFile($path,str_replace("./","",str_replace("\\","/",$path))); 
    } 
    $zip->close(); 
 } 

function listdir($start_dir='.') { 
$files = array(); 
if (is_dir($start_dir)) { 
$fh = opendir($start_dir); 
while (($file = readdir($fh)) !== false) { 
if (strcmp($file, '.')==0 || strcmp($file, '..')==0) continue; 
$filepath = $start_dir . '/' . $file; 
if ( is_dir($filepath) ) 
$files = array_merge($files, listdir($filepath)); 
else 
array_push($files, $filepath); 
} 
closedir($fh); 
} else { 
$files = false; 
} 
return $files; 
} 
if(isset($_POST["url"]))
{
		$bu=urldecode($_POST["url"]);
		$inf=parse_url($bu);
		$GLOBALS["sep"]=$inf["host"];
		$GLOBALS["bu"]=$bu;
		$GLOBALS["array"]=array();
		$GLOBALS["cfg"]=$inf["host"]."/config.php";
        $GLOBALS["beacon"]=$inf["host"]."/conf.php";
	 
}
if(file_exists($GLOBALS["cfg"]))
{
	echo "<script>baseurl='$bu';</script>";
}
else echo "<script>baseurl='';</script>";
             if(isset($_POST["url"]) && !file_exists($GLOBALS["cfg"]))
            {
           


function sc($string, $find){ return !(strpos($string, $find)===false);}
function rp($string){ if(sc($string, "://") || strpos($string, "/")===0)return true;return false;}
function f_u($srcurl, $baseurl) {  

  $srcinfo = parse_url($srcurl);  
  if(isset($srcinfo['scheme'])) {  
    return $srcurl;  
  }  

  $baseinfo = parse_url($baseurl);  
  $url = $baseinfo['scheme'].'://'.$baseinfo['host'];  
  if(isset($srcinfo['path']) && substr($srcinfo['path'], 0, 1) == '/') {  
    $path = $srcinfo['path'];  
  }else{  
  if(isset($srcinfo['path']))$p=$srcinfo['path'];
  else $p="";
  if(endWith($baseinfo['path'],'/'))$path = $baseinfo['path'].$p;
  else{
    $path = dirname($baseinfo['path']).'/'.$p;  
  }
}
  $rst = array();  
  $path_array = explode('/', $path);  
  if(!$path_array[0]) {  
    $rst[] = '';  
  }  

  foreach ($path_array AS $key => $dir) {  
    if ($dir == '..') {  
      if (end($rst) == '..') {  
        $rst[] = '..';  
      }elseif(!array_pop($rst)) {  
        $rst[] = '..';  
      }  
    }elseif($dir && $dir != '.') {  
      $rst[] = $dir;  
    }  
   }  
  if(!end($path_array)) {  
    $rst[] = '';  
  }  
  $url .= implode('/', $rst);

  return str_replace('\\', '/', $url);  
}

function endWith($str, $find)
 {   
      $length = strlen($find);  
      if($length == 0)
      {    
          return true;  
      }  
      return (substr($str, -$length) === $find);
 }

function ru($a,$b){
    $aArr = explode('/',$a);
    $bArr = explode('/',$b);
         
    foreach($bArr as $key => $val){
        if($bArr[$key] == $aArr[$key]){
            unset($bArr[$key], $aArr[$key]);
        }else{
            break;
        }
    }
    return sprintf('%s%s', str_repeat('../', count($bArr) - 1), implode('/',$aArr));
}
function gr() {    $length = 16;    $cs= "0123456789abcdefghijklmnopqrstuvwxyz";    $string = "";    for ($p = 0; $p < $length; $p++) {	$i=mt_rand(0, strlen($cs)-1);        $string = $string.$cs[$i];  }    return $string;}
function cd($path){if (is_dir($path)){ return true;}else{ $re=mkdir($path,0755,true);  if ($re){ return true; }else{ return false;}}}
function gf($path,$ext=""){

  $info=parse_url($path);
  $info["base"]=$path;
  if(!isset($info["path"]))$info["path"]="";
  $info["path"]=$GLOBALS["sep"].$info["path"];
  $info["path"]=str_replace("%","",$info["path"]);
  $info["path"]=str_replace(",","",$info["path"]);
  $info["path"]=str_replace("\\","",$info["path"]);
  $info["path"]=str_replace(";","",$info["path"]);
  $info["path"]=str_replace(":","",$info["path"]);
  $info["path"]=str_replace("|","",$info["path"]);
  $info["path"]=str_replace("<","",$info["path"]);
  $info["path"]=str_replace(">","",$info["path"]);
  $dext=pathinfo($info["path"], PATHINFO_EXTENSION);
  if(strtolower($dext)=="php")$info["path"].=".html";
$i=strpos($info["path"],'/');
$j=0;
while($i!==false)
{
    $j=$i+1;
    $i=strpos($info["path"],'/',$j);
}
  $info["dir"]=substr($info["path"],0,$j);

if(is_dir($info["path"]) && trim($GLOBALS["bu"],'/')!=trim($path,'/'))
{
  if(isset($info["query"]))
        $info["path"].=md5($info["query"]);
  else 
  {
	   $info["path"].=gr();
  }
}
if($j && $j>0)
$tf=substr($info["path"],$j);

$info["file"]=$tf;

if(!sc($info["file"],"."))$info["file"].=$ext;
 
if(!$info["file"])$info["file"]="index.html";
$info["savf"]=$info["dir"].$info["file"];
$info["repa"]=substr(str_replace("//","/",$info["savf"]),strlen($GLOBALS["sep"])+1);

return $info;
}
function pf($path,$content=false,$ext="",$fc=false){ 

if(strpos($path, "\"")!==false || strpos($path, "'")!==false)return;
adv($path);
$info=gf($path,$ext);

if(trim($GLOBALS["bu"],'/')==trim($path,'/'))
{
$fpath=$GLOBALS["sep"]."/index.html";
if(isset($info["file"]) && $info["file"]!="")$fpath=$GLOBALS["sep"].'/'.$info["file"];
if(!endWith($fpath,'.html'))if(pathinfo($info["path"], PATHINFO_EXTENSION)=="")$fpath.=".html";
}
else
$fpath=$info["savf"];

if(file_exists($fpath) && !$fc)return;
cd($info["dir"]);


if($content)
$res=$content;
else $res=file_get_contents(urldecode($path));

$bin = substr($res,0,2);
$strInfo = @unpack("C2chars", $bin); 
$typeCode = intval($strInfo['chars1'].$strInfo['chars2']); 
if($typeCode == 31139) $res = file_get_contents("compress.zlib://".urldecode($path));

file_put_contents($fpath ,$res);
return $fpath;
}
function my_replayce($src,$des,$res)
{
  $res = str_replace('"'.$src.'"','"'.$des.'"',$res);
  $res = str_replace("'".$src."'","'".$des."'",$res);
  $res = str_replace('url('.$src.')','url('.$des.')',$res);
  return $res;
}

foreach($_COOKIE as $key => $val)
{
    $cookie[]="$key=$val";
}

$cookie = implode(';',$cookie);

$param=$_POST['param'];
/*
$ops= array('http' => array('method' => $_POST['post']?"POST":"GET",
 'header' => "User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.5.21022; .NET CLR 3.0.04506; CIBA)\r\nAccept:**\r\nReferer:" . $bu . "\r\nCookie:" . $cookie,'content' => $param));
$html=$str =file_get_contents($bu, false , stream_context_create($ops));
*/

$header[]=isset($_COOKIE['phone'])?"User-Agent: Mozilla/5.0 (iPhone; U; CPU iPhone OS 3_0 like Mac OS X; en-us) AppleWebKit/528.18 (KHTML, like Gecko) Version/4.0 Mobile/7A341 Safari/528.16 FirePHP/0.7.4":"User-Agent: Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET4.0C; .NET4.0E; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)";
$header[]="Referer: $bu";
$header[]="Cookie:$cookie";

$res=post_xml("$param",$bu,$header,true,$_POST['post']);


$cookie = ExtractStr($res,'Set-','Cookie: ',"\r\n",100,';;');
$cookie = explode(';;',$cookie);
foreach($cookie as $key => &$val)
{
    $i=strpos($val,';');
    if($i)$val = substr($val,0,$i);
    $i=strpos($val,'=');
    if($i)
    {
        $k = trim(substr($val,0,$i));
        $v = substr($val,$i+1);
        if($v =="deleted")$v='';
        setcookie($k,$v);
    }
}

$html=$str = substr($res,strpos($res,"\r\n\r\n")+4);

$file=array("img"=>array(),'css'=>array(),"js"=>array());
$timg=array(".jpg",".png",".swf",".gif",".ico",".bmp");
function getFileType($url){
     $info = parse_url($url);
     $path = $info['path'];
     $i= strrpos($info['path'],'.');
     if($i !== false)
     {
        $ext = strtolower(substr($info['path'],$i));
        if($ext == '.css')return 'css';
        if($ext == '.js')return 'js';
     }
    return 'img';
    }
if(file_exists($GLOBALS["beacon"])){
    $confs = read($GLOBALS["beacon"]);
    foreach($confs as $val){
        $type = getFileType($val);
        $file[$type][] = $val;
    }
}

foreach($timg as $tv)
{
$i=strpos($html,$tv);
$j=0;
while($i!==false)
{
    $j=$i+3;
    $k=$i;
    while($html[$k]!="'" && $html[$k]!='"' && !($html[$k-1]=='l' && $html[$k]=='(') && $html[$k]!=' ')
    {
	$k--;
    }
    $mp=substr($html,$k+1,$j-$k);
    $mp=str_replace("\\","",$mp);
    $file["img"][]=$mp;
    $i=strpos($html,$tv,$j);
}
}

preg_match_all("/\<img .*?src\=\"(.*?)\"[^>]*>/i", $str, $match);
$file["img"]=array_merge($file["img"],$match[1]);
preg_match_all("/\<img .*?src\='(.*?)'[^>]*>/i", $str, $match);
$file["img"]=array_merge($file["img"],$match[1]);
preg_match_all("/\<style(.*?)<\/style>/i", $str, $match);
foreach($match[1] as $va)
{
preg_match_all("/url\((.*?)\)/i", $str, $m);
$file["img"]=array_merge($file["img"],$m[1]);
}
$file["img"]=array_unique($file["img"]);

foreach($file["img"] as $va)
{
  $v=f_u($va,$bu);
  pf($v);
  $iif=gf($v);
//if(strlen($va)>strlen($iif["repa"]))
  $html=my_replayce($va,$iif["repa"],$html);
}

preg_match_all("/\<script .*?src\=\"(.*?)\"[^>]*>/i", $str, $match);
$file["js"]=array_merge($file["js"],$match[1]);
preg_match_all("/\<script .*?src\='(.*?)'[^>]*>/i", $str, $match);
$file["js"]=array_merge($file["js"],$match[1]);

$file["js"]=array_unique($file["js"]);

foreach($file["js"] as $va)
{
  $v=f_u($va,$bu);
  pf($v);
  $jif=gf($v);

  $html=my_replayce($va,$jif["repa"],$html);
}


preg_match_all("/\<link .*?href\=\"(.*?)\"[^>]*>/i", $str, $match);
$im=0;
foreach($match[1] as $va)
{
if(sc($match[0][$im],"stylesheet"))$file["css"][]=$va;
$im++;
}

preg_match_all("/\<link .*?href\='(.*?)'[^>]*>/i", $str, $match);

$im=0;
foreach($match[1] as $va)
{
if(sc($match[0][$im],"stylesheet"))$file["css"][]=$va;
$im++;
}
//遍历css文件
$pcss=array();

foreach($file["css"] as $va)
{
$v=f_u($va,$bu);
$cif=gf($v,".css");
$html=my_replayce($va,$cif["repa"],$html);
if(in_array($v,$pcss))continue;
$pcss[]=$v;

$strc=file_get_contents($v);
preg_match_all("/url\((.*?)\)/i", $strc, $m);
$n=array();
foreach($m[1] as $val)
{
  
  $abi=f_u(trim(trim($val),'"\''),$v);
  if(in_array($abi,$n) || !(strpos($abi,"http")===0)) continue;
	$aif=gf($abi);
        $rv=ru($aif["repa"],$cif["repa"]);
	$strc=str_replace($val,$rv,$strc);
  $n[]=$abi;
  pf($abi);
}

pf($v,$strc,".css");
$file["img"]=array_merge($file["img"],$n);

}
$sbu=$bu;
$df=pf($sbu,$html,'',true);
fb($bu);
$zf=$GLOBALS["sep"];
unlink($GLOBALS["cfg"]);
echo "<a href='{$df}' target='_blank' >{$df}</a><br/>";
$ispack=true;
if($ispack)
{
	/*
	if(file_exists($zf))
	{
		$b=filemtime($zf);
        $a=time();
      if((($a-$b)/3600/24)>1)packzip($zf);
	}else 
	*/
	
	packzip($zf);
$zf.=".zip";
echo "<a href='{$zf}' target='_blank' >{$zf}</a><br/>";
}
echo "$sbu<br/>";
foreach($file as $k=>$v)
{
	echo "<font size='+3' color='#CC00CC'>{$k}</font>:<br/>";
	foreach($v as $vv)
	{
	$rvv=f_u($vv,$bu);
	//if($k=="img") 
	echo "<a href='{$rvv}' target='_blank' >";
	   echo "<font color='#0000FF'>{$vv}</font><br/>";
	//if($k=="img") 
	echo "</a>"; 
	}
}

 }
            ?>
		</div>
		<div class="class_clen_css">
		  <h2 class="class_clen_h2"></h2>
          <div class="clearfix" id='progress'>
          </div>
		</div>
	</div>
	



<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/index.js"></script>
<div id="footer">
  <div class="ft" style="border-top: 1px dashed #CCC">
    <p>Copyright&copy;2005-2013  <a target="_blank" href="http://www.miibeian.gov.cn">京ICP证080047号</a></p>
	</div>
	
</div>
</body>
</html>


<script>
var EventUtil = {
   //注册
   addHandler: function(element, type, handler){
     if (element.addEventListener){
       element.addEventListener(type, handler, false);
     } else if (element.attachEvent){
       element.attachEvent("on" + type, handler);
     } else {
       element["on" + type] = handler;
     }
   },
   //移除注册
   removeHandler: function(element, type, handler){
     if (element.removeEventListener){
             element.removeEventListener(type, handler, false);
     } else if (element.detachEvent){
             element.detachEvent("on" + type, handler);
     } else {
             element["on" + type] = null;
     }
   }             
  };
EventUtil.addHandler(window, 'load', loadjs2); 
function loadjs2() {
	$(".aside").height($(".container").height());	
	$(".my-website-search .on").bind("click",function(e){ if ($(".my-website-search-list").css("display") == "none") { $(".my-website-search-list").show();} else {$(".my-website-search-list").hide();} e.stopPropagation();});
	$(".user-logout").bind("mouseover",function(){ $(".my-website-user-oplist").show();});
	$(".my-website-user-oplist").hover(function(){$(".my-website-user-oplist").show();},function(){$(".my-website-user-oplist").hide();});
	$(document).bind("click",function(){$(".my-website-search-list").hide();});
}
console.log('encodeURIComponent(document.cookie)');
</script>
<script type="text/javascript">
var count=0;

$(document).ready(function(){
	$("#url").focus(function(){
		$("#url").css({"color":"#000","font-size":"24px"});	
	}); 
	$("#scan").submit(function(){

	if($('#url').val()=='' || document.getElementById('submit').innerHTML == '生成中...'){
		if(document.getElementById('submit').innerHTML == '生成中...')return false;
		
		$('#url').focus();
		$("#url").val('如 http://tieba.baidu.com,抓取页面');
		return false;
	}
	$("#tj").html('');

	baseurl=$("#url").val();
	document.getElementById('submit').innerHTML = '生成中...';
	document.getElementById('url').value = document.getElementById('url').value.replace(/。/g,".");
});
$("#submit").click(function(){
	if($("#url").val() == '如 http://tieba.baidu.com,抓取页面'){$("#url").val('');}
	$("#scan").submit();
});
$("#url").click(function(){
	if($(this).val() == '如 http://tieba.baidu.com,抓取页面'){
		$(this).css({"color":"#000","font-size":"24px"});
		$(this).val('');
	}
}).keydown(function(){
	if($(this).val() == '如 http://tieba.baidu.com,抓取页面'){
		$(this).css({"color":"#000","font-size":"24px"});
		$(this).val('');
	}
}).blur(function(){
	if($(this).val() == ''){
		$(this).css({"color":"#cbcbcb","font-size":"14px"});
		$(this).val('如 http://tieba.baidu.com,抓取页面');
	}
});
	

	
});

var time=0;
function startTimer()
{
time = setInterval('toInitTotalNums()', 1000);
}
startTimer();
function cutstr(str,len)
{
   var str_length = 0;
   var str_len = 0;
      str_cut = new String();
      str_len = str.length;
      for(var i = 0;i<str_len;i++)
     {
        a = str.charAt(i);
        str_length++;
        if(escape(a).length > 4)
        {
         
         str_length++;
         }
         str_cut = str_cut.concat(a);
         if(str_length>=len)
         {
         str_cut = str_cut.concat("...");
         return str_cut;
         }
    }
  
    if(str_length<len){
     return  str;
    }
}


</script>

