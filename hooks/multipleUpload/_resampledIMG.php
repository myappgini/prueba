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

    if (extension_loaded('imagick')) {
        try {
            $im = new \imagick();
            $color = new ImagickPixel();

            if (strtolower($ext) === 'pdf') {

                /*************************************
                check ImageMagick security policy 'PDF' blocking conversion
                https://stackoverflow.com/questions/52998331/imagemagick-security-policy-pdf-blocking-conversion
                in /etc/ImageMagick-7/policy.xml and that makes it work again,
                but not sure about the security implications of that.
                 **************************************/

                $im->readImage($source . '[' . $page . ']');
            } else {
                $im->readImage($source);
            }
            $im->setResolution(72, 72);
            $im->setImageFormat("JPG");
            $im->thumbnailImage(300, 0); // width and height
            $d = $im->getImageGeometry();

            if (strtolower($ext) === 'pdf') {
                $quantum = $im->getQuantum();
            }

            $im->writeimage($target);
            $im->clear();
            $im->destroy();
            $w = $d['width'];
            $h = $d['height'];
            if (strtolower($ext) !== 'pdf') {
                $folder->size = $w . "x" . $h;
            } else {
                //pierde rendimiento.
                $Image = new Imagick($source);
                $num_page = $Image->getnumberimages();
                $folder->size = $num_page === 1? 'one page': $num_page .' pages';
            }
        } catch (ImagickException $e) {
            $a = dirname(__FILE__);
            header('Content-Type: application/json');
            header($_SERVER['SERVER_PROTOCOL'] . 'error; Content-Type: application/json', true, 400);
            echo json_encode(array(
                "error" => $e->getMessage() . $a
            ));
            die();
        }
        return true;
    } else {
        //throw new RuntimeException('i can\'t make a thumb imagenMagick not installed ');
        return false;
    }
    return false;
}

function make_thumb_mov($source, $target)
{
    require 'vendor/autoload.php';
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
}
