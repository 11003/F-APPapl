<?php
//创建文件
class File {
	private $_dir;

	const EXT = '.txt';

	public function __construct() {
		$this->_dir = dirname(__FILE__) . '/files/';
	}
	public function cacheData($key, $value = '', $cacheTime = 0) {
		$filename = $this->_dir  . $key . self::EXT;

		if($value !== '') { // 将value值写入缓存
			if(is_null($value)) {
				return @unlink($filename);//删除文件
			}
			$dir = dirname($filename);//获取目录
			if(!is_dir($dir)) {		//判断目录是否存在
				mkdir($dir, 0777); //存在就创建
			}

			$cacheTime = sprintf('%011d', $cacheTime);
			return file_put_contents($filename,$cacheTime . json_encode($value));
		}

		if(!is_file($filename)) {  //判断文件是否存在
			return FALSE;
		} 
		$contents = file_get_contents($filename);
		$cacheTime = (int)substr($contents, 0 ,11);
		$value = substr($contents, 11);
		if($cacheTime !=0 && ($cacheTime + filemtime($filename) < time())) {
			unlink($filename);
			return FALSE;
		}
		return json_decode($value, true);
		
	}
}

$file = new File();

echo $file->cacheData('test1');