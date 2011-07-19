<?php

// Init owncloud
require_once('../../lib/base.php');

// We send json data
// header( "Content-Type: application/json" );
// Firefox and Konqueror tries to download application/json for me.  --Arthur
header( "Content-Type: text/plain" );

// Check if we are a user
if( !OC_USER::isLoggedIn()){
	echo json_encode( array( "status" => "error", "data" => array( "message" => "Authentication error" )));
	exit();
}

$files=$_FILES['files'];

$dir = $_POST['dir'];
if(!empty($dir)) $dir .= '/';
$error='';
$result=array();
if(strpos($dir,'..') === false){
	$fileCount=count($files['name']);
	for($i=0;$i<$fileCount;$i++){
		$target='/' . stripslashes($dir) . $files['name'][$i];
		if(OC_FILESYSTEM::fromUploadedFile($files['tmp_name'][$i],$target)){
			$result[]=array( "status" => "success", 'mime'=>OC_FILESYSTEM::getMimeType($target),'size'=>OC_FILESYSTEM::filesize($target),'name'=>$files['name'][$i]);
		}
	}
	echo json_encode($result);
	exit();
}else{
	$error='invalid dir';
}

echo json_encode(array( 'status' => 'error', 'data' => array('error' => $error, "file" => $fileName)));

?>
