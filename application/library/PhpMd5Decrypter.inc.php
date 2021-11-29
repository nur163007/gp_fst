<?php
/**
 * This class checks the strength of a MD5 password by trying to decrypt it.
 *
 *@Author Rochak Chauhan
 */
class PhpMd5Decrypter{
	
	function decrypt($md5){
		$md5=trim($md5);
		if(strlen($md5)!=32){ die("Invalid MD5 Hash");}
		$url="http://www.md5online.org/";
		$matches=array();
		$html=file_get_contents($url); 
		$pattern='/<input type="hidden" name="a" value="(.*)">/Uis';
		preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);		
		$a=$matches[0][1];
		
		
		$res=$this->postDataViaCurl($md5,$a);				
		$returnArray=array();
		$pattern2='/Found (.*) <b>(.*)<\/b><\/span>/Uis';
		preg_match_all($pattern2, $res, $returnArray, PREG_SET_ORDER);		
		
		$nt=strip_tags(@$returnArray[0][2]);
		$nt=trim($nt);
		if(empty($nt)){return false;}
		return $nt;
	}
	
	/**
	 *Function to post variables to a remote file using cURL
	 *
	 *@author Rochak Chauhan
	 *@param string $url
	 *
	 *@return string
	 */
	private function postDataViaCurl($md5,$a){
		$url="http://www.md5online.org";
		$cookiejar = "text.txt";
		
		$parameters=array();
		$parameters['md5']=$md5;
		$parameters['search']="0";
		$parameters['action']="decrypt";
		$parameters[]="Decrypt";
		$parameters['a']=$a;
		
		
		$ch = curl_init() or die("Sorry you need to have cURL extension Enabled");		
		$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
		$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection: keep-alive";
		$header[] = "Keep-Alive: 300";
		$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$header[] = "Accept-Language: en-us,en;q=0.5";
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.12) Gecko/20080201 Firefox/2.0.0.12");
		curl_setopt($ch, CURLOPT_REFERER, $url);
		curl_setopt($ch, CURLOPT_POST, 1 );
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiejar);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiejar);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$postResult = curl_exec($ch);
		if (curl_errno($ch)) {
			return false;
		}
		curl_close($ch);
		return $postResult;
	}
	
	
}
?>