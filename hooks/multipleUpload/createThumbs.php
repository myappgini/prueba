<?php
function make_thumb($source, $fileName, $ext, $folder, $type)
{
    $source = $folder . $source;
    $target = $folder . $fileName . '_th.' . $ext;
    $specs = array("width" => "200", "height" => "200", "identifier" => "_th");

    if ($type === 'mov') return make_thumb_mov($source, $target);
    if ($type === 'img') return createThumbnail($source, $specs);

    return false;
}

function make_thumb_mov($source, $target)
{
    require 'vendor/autoload.php';
    if (class_exists('FFMpeg\FFMpeg')) {
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
    } else {
        return false;
    }
}
