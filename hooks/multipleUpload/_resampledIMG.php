<?php
function make_thumb($source, $fileName, $ext, &$folder, $page = 0)
{
    $exit = false;
    $currDir = dirname(__FILE__);
    $base_dir = realpath("{$currDir}/../..");
    header('Content-type: images');

    $fo = $base_dir . "/" . $folder->folder;
    $source = $fo . $folder->original . '/' . $source;
    $target = $fo . $folder->original . '/' . $fileName . '_th.jpg';


    if ($folder->type === 'mov') {
        return make_thumb_mov($source, $target);
    }

    $specs = array ("width" =>"200","height"=>"200","identifier"=>"_th");
    if (createThumbnail($source, $specs)) return true;
    return false;
    
}

function make_thumb_mov($source, $target)
{
    require 'vendor/autoload.php';
    if (class_exists('FFMpeg\FFMpeg')){
        $source = $fo . $folder->original . '/' . $source;
        $ffmpeg = FFMpeg\FFMpeg::create();
        $video = $ffmpeg->open($source);
        $video
            ->filters()
            ->resize(new FFMpeg\Coordinate\Dimension(320, 240))
            ->synchronize();
        $video
            ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(10))
            ->save($target);
        return true;

    }else{
        return false;
    }
}
