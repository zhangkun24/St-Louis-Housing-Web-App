<?php
require 'database.php';
// It may take a whils to crawl a site ...
set_time_limit(100000000);

// Inculde the phpcrawl-mainclass
include("libs/PHPCrawler.class.php");

// Extend the class and override the handleDocumentInfo()-method 
class MyCrawler extends PHPCrawler 
{
  function handleDocumentInfo($DocInfo) 
  {
	  require 'database.php';
    // Just detect linebreak for output ("\n" in CLI-mode, otherwise "<br>").
    if (PHP_SAPI == "cli") $lb = "\n";
    else $lb = "<br />";

    // Print the URL and the HTTP-status-Code
    //echo "Page requested: ".$DocInfo->url." (".$DocInfo->http_status_code.")".$lb;
   // echo "url:".$DocInfo->url.$lb;
	//$url=$DocInfo->url;
	//$pat="/\/missouri\/st\.-louis\/schools\/\?page=\d+/";
	//if(preg_match($pat, $url)>0){
		$this->parseSonglist($DocInfo);
	//}
    // Print the refering URL
    //echo "Referer-page: ".$DocInfo->referer_url.$lb;
    
    // Print if the content of the document was be recieved or not
    if ($DocInfo->received == true){
     // echo "Content received: ".$DocInfo->bytes_received." bytes".$lb;
	 // echo "content".$lb;
	  //$DocInfo->content;
	 // echo $lb;
  }
    else
      echo "Content not received".$lb; 
    //echo "=========content=============";
    // Now you should do something with the content of the actual
    // received page or file ($DocInfo->source), we skip it in this example 
    //echo $DocInfo->content.$lb;
    echo $lb;
    
    flush();
  }
	public function parseSonglist($DocInfo){
		$content=$DocInfo->content;
		$songlistArrr=array();
		//$songlistArrr['raw_url']=$DocInfo->url;
		//$matchess=array();
		//$pat="/\"name\":\"([^\"]+)\",\"/";
		//$ret=preg_match_all($pat,$page,$matchess);
		//$songlistArrr['name']=array();
		//for($i=0;$i<count($matchess[0]);$i++){
		//	$song_name=$matchess[1][$i];
		//	array_push($songlistArrr['name'],array('name'=>$song_name));
		//}
		
		
		//if($ret>0){
		//	$songlistArr['name']=$matches[1];
		//}
		//else{
		//	$songlistArr['name']='';
		//}
		
		//$pat="/\"street\":\"([.\"]*)\"/";
		//匹配id
		$pat="/\"id\":(.*?),/";
		$matches00=array();
		preg_match_all($pat, $content, $matches00);
		$songlistArr['id']=array();
		//匹配学校名字
		$pat="/\"name\":\"(.*?)\"/";
		$matches0=array();
		preg_match_all($pat, $content, $matches0);
		$songlistArr['name']=array();
		//匹配街道
		$pat="/\"street\":\"(.*?)\"/";
		$matches1=array();
		preg_match_all($pat, $content, $matches1);
		$songlistArr['street']=array();
		//for($i=0; $i<count($matches[0]);$i++){
		//	$song_street=$matches[1][$i];
		//	array_push($songlistArr['street'],$song_street);
		//}
		//匹配zip
		$pat="/\"zipcode\":\"(.*?)\"/";
		$matches2=array();
		preg_match_all($pat, $content,$matches2);
		$songlistArr['zipcode']=array();
		//匹配评分
		$pat="/\"communityRatingStars\":(.*?),/";
		$matches3=array();
		preg_match_all($pat, $content,$matches3);
		$songlistArr['rating']=array();
		//公立私立类型
		$pat="/\"schoolType\":\"(.*?)\"/";
		$matches4=array();
		preg_match_all($pat, $content,$matches4);
		$songlistArr['type']=array();
		//纬度
		$pat="/\"lat\":(.*?),/";
		$matches5=array();
		preg_match_all($pat, $content,$matches5);
		$songlistArr['lat']=array();
		//经度
		$pat="/\"lng\":(.*?),/";
		$matches6=array();
		preg_match_all($pat, $content,$matches6);
		$songlistArr['lng']=array();		
		//合成
		for($i=0;$i<count($matches0[0]);$i++){
			$song_street=$matches00[1][$i]."   ".$matches0[1][$i]."   ".$matches1[1][$i]."   ".$matches2[1][$i]."   ".$matches3[1][$i]."   ".$matches4[1][$i]."   ".$matches5[1][$i]."   ".$matches6[1][$i];
			//$song_zipcode=$matches[1][$i];
			//array_push($songlistArr['zipcode'], $song_zipcode);
			array_push($songlistArr['street'],$song_street);
			  $mysqli = new mysqli('localhost', 'root', 'Tianpei', 'cse530');
 
              if($mysqli->connect_errno) {
	          printf("Connection Failed: %s\n", $mysqli->connect_error);
	         exit;
}
			$stmt = $mysqli->prepare("insert into school (id, name, street, zip, rating, type, latitude, longtitude) values (?, ?, ?, ?, ?, ?, ?, ?)");
			if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
			}			
			$stmt->bind_param('issiisdd', $matches00[1][$i], $matches0[1][$i], $matches1[1][$i],$matches2[1][$i],$matches3[1][$i],$matches4[1][$i],$matches5[1][$i],$matches6[1][$i]);
			$stmt->execute();
		}
		
		
		
		print_r($songlistArr['street']);

		$stmt->close();
		
	}


  
}

// Now, create a instance of your class, define the behaviour
// of the crawler (see class-reference for more options and details)
// and start the crawling-process.

$crawler = new MyCrawler();

// URL to crawl
$start_url="http://www.greatschools.org/missouri/st.-louis/schools/";
$crawler->setURL("$start_url");

// Only receive content of files with content-type "text/html"
$crawler->addContentTypeReceiveRule("#text/html#");

// Ignore links to pictures, dont even request pictures 链接扩展
//$crawler->addURLFilterRule("#\.(jpg|jpeg|gif|png)$# i");
//$crawler->addURLFollowRule("#http://www\.greatschools\.org/missouri/st\.-louis/\S+/$# i");
$crawler->addURLFollowRule("#/missouri/st\.-louis/schools/\?page=\d+$# i");
// Store and send cookie-data like a browser does
$crawler->enableCookieHandling(true);

// Set the traffic-limit to 1 MB (in bytes,
// for testing we dont want to "suck" the whole site)
$crawler->setTrafficLimit(0);

// Thats enough, now here we go
$crawler->go();

// At the end, after the process is finished, we print a short
// report (see method getProcessReport() for more information)
$report = $crawler->getProcessReport();

if (PHP_SAPI == "cli") $lb = "\n";
else $lb = "<br />";
    
echo "Summary:".$lb;
//echo "Links followed: ".$report->links_followed.$lb;
//echo "Documents received: ".$report->files_received.$lb;
//echo "Bytes received: ".$report->bytes_received." bytes".$lb;
//echo "Process runtime: ".$report->process_runtime." sec".$lb; 
?>