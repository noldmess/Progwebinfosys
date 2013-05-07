<?php
namespace  Wiki;

class Image{
	
	private $folder;
	
	public function  __construct(){
		$this->folder = 'art_images/';
	}
	
	public function startUpload($fileArray, $id){
		
		if($fileArray['error'] !== 0){
            return;
        }
		
		if(!$this->checkFileSize($fileArray["size"])){
			return;	
		}
		if(isset($fileArray["type"])){
			$type = $fileArray["type"];
		}else{
			$type = false;
		}
		if(!$this->checkType($type, $fileArray["tmp_name"])){
			return;	
		}
		
		$this->upload($fileArray["tmp_name"], $id);
	}
	
	public function checkFileSize($size){
		if($size >= 1024*1024*10){
			return false;	
		}
		return true;
	}
	
	public function checkType($type, $name){
		if($type == false){
			$image = getimagesize($name);
			$type = $img['mime'];
		}
		$splitted = explode('/', $type);
		if($splitted[0] !== 'image'){
			return false;
		}
		
		return true;
		
	}
	
	public function upload($tmp, $id){
		$name = $id.'.png';
		$path = './'. $this->folder . $name;
		
		if(!move_uploaded_file($tmp, $path)){
			echo "Error moving image!";
		}
	}
	
	public function deleteImage($id){
		$name = $id.'.png';
		$path = './'. $this->folder . $name;
		if(is_file($path)){
			unlink($path);
		}
	}
}
