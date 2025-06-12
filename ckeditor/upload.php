<?php
$up_url = '/userfiles'; // 기본 업로드 URL
$up_dir = '../userfiles'; // 기본 업로드 폴더
 
// 업로드 DIALOG 에서 전송된 값
$funcNum = $_GET['CKEditorFuncNum'] ;
$CKEditor = $_GET['CKEditor'] ;
$langCode = $_GET['langCode'] ;
 
if(isset($_FILES['upload']['tmp_name']))
{
    $file_name = time() ."_". $_FILES['upload']['name'];
    $ext = strtolower(substr($file_name, (strrpos($file_name, '.') + 1)));
 
    if ('jpg' != $ext && 'jpeg' != $ext && 'gif' != $ext && 'png' != $ext)
    {
        echo '이미지만 가능';
        return false;
    }
 
    $save_dir = sprintf('%s/%s', $up_dir, $file_name);
    $save_url = sprintf('%s/%s', $up_url, $file_name);
 
    if (move_uploaded_file($_FILES["upload"]["tmp_name"],$save_dir)){
		// rotateImage($save_dir);
        echo "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '$save_url', '업로드완료');</script>";
	}
}


function rotateImage( $filename )
{
    if( empty($filename) || (FALSE == is_file( $filename )) ) return;
    
    if( function_exists('exif_read_data') && function_exists('imagecreatefromjpeg') && function_exists('imagerotate') )
    {
        //이미지 정보 가져오기.(정보가 없는 이미지는 패스)
        $exifData = exif_read_data( $filename );
        
        // 시계방향으로 90도 돌려줘야 정상인데 270도 돌려야 정상적으로 출력됨
        if( isset($exifData['Orientation']) )
        {
            if($exifData['Orientation'] == 6)
            {
                $degree = 270;
            }
            // 반시계방향으로 90도 돌려줘야 정상
            else if ($exifData['Orientation'] == 8)
            {
                $degree = 90;
            }
            else if ($exifData['Orientation'] == 3)
            {
                $degree = 180;
            }

            if($degree)
            {
                if($exifData['FileType'] == 1)
                {
                    $source = imagecreatefromgif( $filename );
                    $source = imagerotate ($source , $degree);
                    imagegif($source, $filename);
                }
                else if($exifData['FileType'] == 2)
                {
                    $source = imagecreatefromjpeg( $filename );
                    $source = imagerotate ($source , $degree);
                    imagejpeg($source, $filename);
                }
                else if($exifData['FileType'] == 3)
                {
                    $source = imagecreatefrompng( $filename );
                    $source = imagerotate ($source , $degree);
                    imagepng($source, $filename);
                }
                
                imagedestroy($source);
            }
        }
    }
}
?>
