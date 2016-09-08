<?php
/**
 * Created by PhpStorm.
 * User: roy
 * Date: 16-8-30
 * Time: 下午2:47
 */


$file = $_FILES['Filedata'];
if($file['error'] === 0){
    if($file['type'] == 'application/octet-stream'){
        $root_path = $_SERVER['DOCUMENT_ROOT']."/anchor/Uploads";
        $relative_path = date('Ymd').'/';
        $save_path = $root_path.'/image/'.$relative_path;
        if(!is_dir($save_path)){
            $r = mkdir($save_path,0755,true);
            if(!$r){
                echo json_encode(array('status'=>3,'info'=>'创建目录失败'.$root_path));exit;
            }
        }
        $thumb_path = $root_path.'/thumb/'.$relative_path;
        if(!is_dir($thumb_path)){
            $r = mkdir($thumb_path,0755,true);
            if (!$r){
                echo json_encode(array('status'=>3,'info'=>'创建目录失败'.$thumb_path));exit;
            }
        }
        $tmp_name = $file['tmp_name'];
        $name = $file['name'];
        $file_name = md5_file($tmp_name);
        $suffix = getFileSuffix($name);

        $im = null;
        if ($suffix == 'jpg' || $suffix == 'jpeg'){
            $im = imagecreatefromjpeg($tmp_name);
        }elseif ($suffix == 'gif'){
            $im = imagecreatefromgif($tmp_name);
        }elseif ($suffix == 'png'){
            $im = imagecreatefrompng($tmp_name);
        }
        if ($im){
            $photo1 = $file_name.'1';
            $width1 = 200;
            $height1 = 200;
            ResizeImage($im, $width1, $height1, $thumb_path, $photo1, $suffix);
            //ImageDestroy($im);
            imagedestroy($im);
        }

        $m = move_uploaded_file($tmp_name,"$save_path$file_name.$suffix");
        $path = "image/$relative_path$file_name.$suffix";
        $photo1_path = 'thumb/'.$relative_path.$file_name.'1.'.$suffix;
        if($m){
            echo json_encode(array('status'=>0,'info'=>'上传成功','path'=>$path,'thumb'=>$photo1_path,'name'=>$name,'size'=>$file['size']));
            //echo json_encode(array('status'=>0,'info'=>'上传成功','path'=>$path,'photo1'=>$photo1_path,'photo2'=>$photo2_path,'photo3'=>$photo3_path));
        }else{
            echo json_encode(array('status'=>4,'info'=>'上传失败'));
        }
    }else{
        json_encode(array('status'=>2,'info'=>'上传文件不合法'));
    }
}else{
    json_encode(array('status'=>1,'info'=>'上传出错'));
}



/**
 * 获取文件后缀
 */
function getFileSuffix($filename)
{
    $fileinfo = pathinfo($filename);
    $suffix = $fileinfo['extension'];
    return strtolower($suffix);
}

/**
 * 生成缩略图
 */
function ResizeImage($im,$maxwidth,$maxheight,$path,$name,$suffix)
{
    $width = imagesx($im);
    $height = imagesy($im);
    if (($maxwidth && $width > $maxwidth) || ($maxheight && $height > $maxheight)) {
        if ($maxwidth && $width > $maxwidth) {
            $widthratio = $maxwidth / $width;
            $RESIZEWIDTH = true;
        }
        if ($maxheight && $height > $maxheight) {
            $heightratio = $maxheight / $height;
            $RESIZEHEIGHT = true;
        }
        if ($RESIZEWIDTH && $RESIZEHEIGHT) {
            if ($widthratio < $heightratio) {
                $ratio = $widthratio;
            } else {
                $ratio = $heightratio;
            }
        } elseif ($RESIZEWIDTH) {
            $ratio = $widthratio;
        } elseif ($RESIZEHEIGHT) {
            $ratio = $heightratio;
        }
        $newwidth = $width * $ratio;
        $newheight = $height * $ratio;
        if (function_exists("imagecopyresampled")) {
            $newim = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        } else {
            $newim = imagecreate($newwidth, $newheight);
            imagecopyresized($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        }
        ImageJpeg($newim, $path.$name.".$suffix");
        ImageDestroy($newim);
    } else {
        ImageJpeg($im, $path.$name.".$suffix");
    }
    return true;
}