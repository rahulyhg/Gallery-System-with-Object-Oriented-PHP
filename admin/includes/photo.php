<?php



class Photo extends Db_object{

	//table and fields
	protected static $db_table = "photos";
    protected static $db_table_fields = array('id','title','caption','description','filename','alternate_text','type','size');
    public $id;
    public $title;
	public $caption;
    public $description;
    public $filename;
	public $alternate_text;
    public $type;
    public $size;
    public $tmp_path;
    public $upload_directory = "images";
   

	public function picture_path(){
		
		return $this->upload_directory .DS. $this->filename;
		
	}
	
	
	
	
	
	//Save file info to database
	public function save(){
		
		if($this->id){
			$this->update();
		}
		
		else{
			
			if(!empty($this->errors)){
				return false;
			}
			
			if(empty($this->filename)){
				$this->errors[] = "the file isn't available";
				
				return false;
				
			}
			
			$target_path = SITE_ROOT. DS. 'admin'. DS. $this->picture_path() ;
			
			if(file_exists($target_path)){
				$this->errors[] = "The file {$target_path} exists";
				return false;
			}
			
			//PHP function that will that takes filename tmp path and the destination
			if(move_uploaded_file($this->tmp_path,$target_path)){
				
				if($this->create()){
					unset($this->tmp_path);
					return true;
				}

				
			}else{
				
				$this->errors[]=" Problem with the folder permissions ";
				return false;
			}
			
			
			$this->create();
			
		}
		
		
	}
	
	//Delete the photo if it's there
	public function delete_photo(){
		
		if($this->delete()){
		
			$target_path = SITE_ROOT .DS. 'admin' .DS. $this->picture_path();
			
			//Predifined function for deleting a file unlink
			return unlink($target_path)? true : false;
			
		
		}else{
			
			return false;
			
			
		}
	}
	
	
	public static function display_sidebar_data($photo_id){
		
		$photo = Photo::find_by_id($photo_id);
		
		$output = "<a class='thumbnail' href='#'><img width='100' src='{$photo->picture_path()}'></a>";
		$output .= "<p>{$photo->filename}</p>";
		$output .= "<p>{$photo->type}</p>";
		$output .= "<p>{$photo->size}</p>";
		
		echo $output;
		
	}
	
	
	




}


?>