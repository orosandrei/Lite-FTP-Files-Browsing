<!DOCTYPE html>
<html>
	<head> 
		<title>Ftp List - File and Folders Explorer</title> 
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
		<style type="text/css"> 
		<!-- 
		*, html, body { font-family: Verdana, Arial; font-size: 14px; line-height:21px; color: #333; text-decoration: none; margin: 0; padding: 0; } 
		a { color: #333; text-decoration: none; } 
		ul { margin-top: 20px; }
		ul li a:hover { background: #dedede;}
		ul li a { float: left; width: 100%; padding: 5px; }
		ul li { float: left; width: 100%; list-style:none; background: #fff; vertical-align: middle; }
		ul li:nth-child(even) { background: #efefef; }
		.folder-list-wrap { padding: 20px; }
		--> 
		</style> 
	</head> 
	<body> 
		
	<? 
	// the folder where the current script is located 
	$config_root = str_replace(pathinfo(__FILE__, PATHINFO_FILENAME).'.php', '', $_SERVER['SCRIPT_URI']); 

	// icon classes from  fontawesome
	$config_icon = array(
						'back' => "fa fa-arrow-circle-o-left fa-3x",
						'home' => "fa fa-home fa-3x",
						'folder' => "fa fa-folder-o fa-3x",
						'file' => "fa fa-file-o fa-2x", 
						'file_archive' => "fa fa-file-archive-o fa-2x", 
						'file_text' => "fa fa-file-text-o fa-2x", 
						'file_image' => "fa fa-file-image-o fa-2x", 
						'file_audio' => "fa fa-file-audio-o fa-2x", 
						'file_video' => "fa fa-file-video-o fa-2x", 
						'file_pdf' => "fa fa-file-pdf-o fa-2x", 
						'file_doc' => "fa fa-file-word-o fa-2x", 
						'file_xls' => "fa fa-file-excel-o fa-2x", 
						'file_ppt' => "fa fa-file-powerpoint-o fa-2x",
						'file_code' => "fa fa-file-code-o fa-2x",	
					);

	// returns the extension of a file 
	function getFileExt($filename) 
	{
		return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
	} 

	// helper function to compare folder items - sort folders 1st, then by type, then alphabetically
	function folderItemsCompare($x,$y)
	{
		$fileExtensionX = getFileExt($x);
		$fileExtensionY = getFileExt($y);

		$result = 	is_dir($x) ? (is_dir($y) ? strnatcasecmp ($x,$y) : -1) : (is_dir($y) ? 1 : (strcasecmp($fileExtensionX, $fileExtensionY) == 0 ? strnatcasecmp($x, $y) : strcasecmp($fileExtensionX, $fileExtensionY)));
		
		return $result;
	}	

	// returns the files from the $path
	function getFiles($path) 
	{ 
		chdir($path);
		$items = array_diff(scandir('.'), array('.', '..'));

		//sort folders 1st, then by type, then alphabetically
		usort($items, folderItemsCompare);
		
		//build list with full items info
		for($i=0; $i < count($items); $i++)
		{		
			$items[$i] = array('type' => filetype($items[$i]), 'name' => $items[$i], 'size' => filesize($items[$i]), 'extension' => getFileExt($items[$i]));
		}
		
		return $items; 
	} 
	
	//get Mb and Kb from bytes
	function formatBytes($size, $precision = 2)
	{
		$base = log($size) / log(1024);
		$suffixes = array('bytes', 'Kb', 'Mb', 'Gb', 'Tb');   
		
		$nr = round(pow(1024, $base - floor($base)), $precision);
		$res = (is_nan($nr) ? '0 bytes' : $nr).' '.$suffixes[floor($base)];
		
		return $res;
	}
	
	// selects the file icon	
	function getIconType($ext, $iconType)
	{
		$ic = $iconType['file'];
		switch($ext) {
			case "txt":
				$ic = $iconType['file_text'];
				break;
			case "csv":
				$ic = $iconType['file_text'];
				break;
			case "lst":
				$ic = $iconType['file_text'];
				break;
			case "log":
				$ic = $iconType['file_text'];
				break;						
			case "zip":
				$ic = $iconType['file_archive'];
				break;						
			case "rar":
				$ic = $iconType['file_archive'];
				break;						
			case "jpg":
				$ic = $iconType['file_image'];
				break;						
			case "jpeg":
				$ic = $iconType['file_image'];
				break;						
			case "png":
				$ic = $iconType['file_image'];
				break;						
			case "bmp":
				$ic = $iconType['file_image'];
				break;						
			case "gif":
				$ic = $iconType['file_image'];
				break;						
			case "psd":
				$ic = $iconType['file_image'];
				break;						
			case "ai":
				$ic = $iconType['file_image'];
				break;						
			case "tiff":
				$ic = $iconType['file_image'];
				break;						
			case "ico":
				$ic = $iconType['file_image'];
				break;						
			case "mp3":
				$ic = $iconType['file_audio'];
				break;
			case "wav":
				$ic = $iconType['file_audio'];
				break;
			case "wma":
				$ic = $iconType['file_audio'];
				break;
			case "mpeg":
				$ic = $iconType['file_video'];
				break;
			case "mpg":
				$ic = $iconType['file_video'];
				break;
			case "mov":
				$ic = $iconType['file_video'];
				break;
			case "avi":
				$ic = $iconType['file_video'];
				break;						
			case "wmv":
				$ic = $iconType['file_video'];
				break;
			case "mkv":
				$ic = $iconType['file_video'];
				break;
			case "pdf":
				$ic = $iconType['file_pdf'];
				break;
			case "doc":
				$ic = $iconType['file_doc'];
				break;
			case "docx":
				$ic = $iconType['file_doc'];
				break;
			case "xls":
				$ic = $iconType['file_xls'];
				break;
			case "xlsx":
				$ic = $iconType['file_xls'];
				break;
			case "ppt":
				$ic = $iconType['file_ppt'];
				break;
			case "pptx":
				$ic = $iconType['file_ppt'];
				break;
			case "php":
				$ic = $iconType['file_code'];
				break;
			case "html":
				$ic = $iconType['file_code'];
				break;
			case "htm":
				$ic = $iconType['file_code'];
				break;
			case "css":
				$ic = $iconType['file_code'];
				break;
			case "js":
				$ic = $iconType['file_code'];
				break;
			case "cs":
				$ic = $iconType['file_code'];
				break;
			case "c":
				$ic = $iconType['file_code'];
				break;
			case "asm":
				$ic = $iconType['file_code'];
				break;
			case "sql":
				$ic = $iconType['file_code'];
				break;
			case "xml":
				$ic = $iconType['file_code'];
				break;
			case "java":
				$ic = $iconType['file_code'];
				break;
			case "jsp":
				$ic = $iconType['file_code'];
				break;
			case "jsf":
				$ic = $iconType['file_code'];
				break;
			case "py":
				$ic = $iconType['file_code'];
				break;
		}	
		return $ic;
	}


	// navigation
	if(isset($_GET['dir'])) 
		$folder = $_GET['dir'];
	else 
		$folder = "./"; 

	
	// list items
	$items = getFiles($folder); 
	?> 		
		<div class="folder-list-wrap">
			<a href="javascript:window.history.back()" title="Back"><i class="<?=$config_icon['back']?>"></i></a>
			<a href="<?=$_SERVER['PHP_SELF']?>" title="Root"><i class="<?=$config_icon['home']?>"></i></a>
			<hr/>
			<ul>
	<?
	foreach ($items as $f) 
	{ 				
		if($f['type'] == 'dir') 
		{ 
			$cmd = '?dir='.$folder.$f['name'].'/'; 
			$icon = $config_icon['folder'];
			$anchor_target = "_self";
		}
		else 
		{
			$cmd = $config_root.$folder.$f['name'];
			$icon = getIconType($f['extension'], $config_icon);
			$anchor_target = "_blank";							
			$size = ', '.formatBytes($f['size']);
		}
	?>
				<li><a href="<?=$cmd?>" target="<?=$anchor_target?>" title="<?=$f['type'].$size?>"><i class="<?=$icon?>"></i> <?=$f['name']?></a></li>
	<?
	}	
	?> 
			</ul>
		</div>
	</body> 
</html>