<?php
/**
 * Created by Cold-Fire.
 * Date: 26.5.2019.
 * Time: 21:19
 * IMAGES manipulation class with GD library
 * use Image class instead ImageBase class
 */

namespace yii\helpers;


class ImageBase
{
    /**
     * Count pixel in image
     *
     * @param $img
     * @return float|int
     */
    public static function getPxNumber($img)
    {
        return getimagesize($img)[0] * getimagesize($img)[1];
    }

    /**
     * Get image size in KB
     *
     * @param $img
     * @return float
     */
    public static function getSizeKb($img)
    {
        return round(filesize($img) / 1024);
    }

    /**
     * Create blank transparent image
     *
     * @param string $imgOut
     * @param int    $ww
     * @param int    $wh
     * @return void
     */
    public static function imageTransparent($imgOut, $ww, $wh)
    {
        $img = imagecreatetruecolor($ww, $wh);
        imagesavealpha($img, true);
        $color = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $color);
        imagepng($img, $imgOut);
    }

    /**
     * Place logo on image
     *
     * @param string $imgIn
     * @param string $imgOut
     * @param string $water
     * @param int    $ww
     * @param int    $wh
     * @return void
     */
    public static function imageLogo($imgIn, $imgOut, $water, $ww, $wh)
    {
        $ext = self::getExtension($imgIn);
        self::checkExtension($ext);

        $wx = self::getWidth($imgIn) - ($ww + 10);
        $wy = self::getHeight($imgIn) - ($wh + 10);

        $im = null;
        $stamp = null;
        if ($ext === 'png') {
            $stamp = imagecreatefrompng($water);
            $im = imagecreatefrompng($imgIn);
            imagecopy($im, $stamp, $wx, $wy, 0, 0, round($ww), round($wh));
            imagepng($im, $imgOut);
        }
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $stamp = imagecreatefrompng($water);
            $im = imagecreatefromjpeg($imgIn);
            imagecopy($im, $stamp, $wx, $wy, 0, 0, round($ww), round($wh));
            imagejpeg($im, $imgOut);
        }
        if ($ext === 'gif') {
            $stamp = imagecreatefrompng($water);
            $im = imagecreatefromgif($imgIn);
            imagecopy($im, $stamp, $wx, $wy, 0, 0, round($ww), round($wh));
            imagegif($im, $imgOut);
        }
        imagedestroy($im);
        imagedestroy($stamp);
    }

    /**
     * Get image extension
     *
     * @param string $img
     * @return string
     */
    public static function getExtension($img)
    {
        $extensionWithDot = image_type_to_extension(getimagesize($img)[2]);
        if ($extensionWithDot === '.jpeg') {
            $extensionWithDot = '.jpg';
        }

        return strtolower(str_replace('.', '', $extensionWithDot));
    }

    /**
     * Check if file image
     *
     * @param string $extension
     * @return void
     */
    public static function checkExtension($extension)
    {
        if ($extension !== 'png' && $extension !== 'jpg' && $extension !== 'jpeg' && $extension !== 'gif' && $extension !==
            'JPG' && $extension !== 'PNG') {
            echo 'Wrong extension';
            die;
        }
    }

    /**
     * Get image width
     *
     * @param string $img
     * @return mixed
     */
    public static function getWidth($img)
    {
        return getimagesize($img)[0];
    }

    /**
     * Get image height
     *
     * @param string $img
     * @return mixed
     */
    public static function getHeight($img)
    {
        return getimagesize($img)[1];
    }

    /**
     * @param string $imgIn
     * @param string $imgOut
     * @param string $water
     * @param int    $ww
     * @param int    $wh
     * @return void
     */
    public static function imageWatermark($imgIn, $imgOut, $water, $ww, $wh)
    {
        $ext = self::getExtension($imgIn);
        self::checkExtension($ext);

        $waterWidth = round($ww);
        $waterHeight = round($wh);

        $waterPerWidth = round(self::getWidth($imgIn) / $waterWidth);
        $waterPerHeight = round(self::getHeight($imgIn) / $waterHeight);
        $count_water = $waterPerWidth * $waterPerHeight;

        $im = null;
        $stamp = null;
        if ($ext === 'png') {
            $stamp = imagecreatefrompng($water);
            $im = imagecreatefrompng($imgIn);

            $wx = 0;
            $wy = 0;
            $cnt = 0;
            for ($i = 0; $i < $count_water; ++$i) {
                imagecopy($im, $stamp, $wx, $wy, 0, 0, $waterWidth, $waterHeight);
                $wx += $waterWidth;
                ++$cnt;
                if ($cnt >= $waterPerWidth) {
                    $wx = 0;
                    $wy += $waterHeight;
                    $cnt = 0;
                }
            }
            imagepng($im, $imgOut);
        } elseif ($ext === 'jpg') {
            $stamp = imagecreatefrompng($water);
            $im = imagecreatefromjpeg($imgIn);

            $wx = 0;
            $wy = 0;
            $cnt = 0;
            for ($i = 0; $i < $count_water; ++$i) {
                imagecopy($im, $stamp, $wx, $wy, 0, 0, $waterWidth, $waterHeight);
                $wx += $waterWidth;
                ++$cnt;
                if ($cnt >= $waterPerWidth) {
                    $wx = 0;
                    $wy += $waterHeight;
                    $cnt = 0;
                }
            }
            imagejpeg($im, $imgOut);
        } elseif ($ext === 'jpeg') {
            $stamp = imagecreatefrompng($water);
            $im = imagecreatefromjpeg($imgIn);

            $wx = 0;
            $wy = 0;
            $cnt = 0;
            for ($i = 0; $i < $count_water; ++$i) {
                imagecopy($im, $stamp, $wx, $wy, 0, 0, $waterWidth, $waterHeight);
                $wx += $waterWidth;
                ++$cnt;
                if ($cnt >= $waterPerWidth) {
                    $wx = 0;
                    $wy += $waterHeight;
                    $cnt = 0;
                }
            }
            imagejpeg($im, $imgOut);
        } elseif ($ext === 'gif') {
            $stamp = imagecreatefrompng($water);
            $im = imagecreatefromgif($imgIn);

            $wx = 0;
            $wy = 0;
            $cnt = 0;
            for ($i = 0; $i < $count_water; ++$i) {
                imagecopy($im, $stamp, $wx, $wy, 0, 0, $waterWidth, $waterHeight);
                $wx += $waterWidth;
                ++$cnt;
                if ($cnt >= $waterPerWidth) {
                    $wx = 0;
                    $wy += $waterHeight;
                    $cnt = 0;
                }
            }
            imagegif($im, $imgOut);
        }
        imagedestroy($im);
        imagedestroy($stamp);
    }

    /**
     * @param string $imgIn
     * @param string $imgOut
     * @param int    $x
     * @param int    $y
     * @return void
     */
    public static function imageScale($imgIn, $imgOut, $x, $y)
    {
        $ext = self::getExtension($imgIn);
        self::checkExtension($ext);

        $imageNew = null;
        $image = null;
        if ($ext === 'png') {
            $filename = $imgIn;
            list($width, $height) = getimagesize($filename);
            $newWidth = $x;
            $newHeight = $y;
            $imageNew = imagecreatetruecolor($newWidth, $newHeight);
            $image = imagecreatefrompng($filename);
            imagecopyresampled(
                $imageNew,
                $image,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $width,
                $height
            );
            imagepng($imageNew, $imgOut . '.' . $ext);
        }
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $filename = $imgIn;
            list($width, $height) = getimagesize($filename);
            $newWidth = $x;
            $newHeight = $y;
            $imageNew = imagecreatetruecolor($newWidth, $newHeight);
            $image = imagecreatefromjpeg($filename);
            imagecopyresampled(
                $imageNew,
                $image,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $width,
                $height
            );
            imagejpeg($imageNew, $imgOut . '.' . $ext);
        }
        if ($ext === 'gif') {
            $filename = $imgIn;
            list($width, $height) = getimagesize($filename);
            $newWidth = $x;
            $newHeight = $y;
            $imageNew = imagecreatetruecolor($newWidth, $newHeight);
            $image = imagecreatefromgif($filename);
            imagecopyresampled(
                $imageNew,
                $image,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $width,
                $height
            );
            imagegif($imageNew, $imgOut . '.' . $ext);
        }
        imagedestroy($imageNew);
        imagedestroy($image);
    }

    /**
     * @param string $img
     * @param int    $maxsize
     * @param string $destination
     * @return void
     */
    public static function resizeImageProp($img, $maxsize, $destination)
    {
        $ck = 0;
        $ext = self::getExtension($img);
        self::checkExtension($ext);

        $destination = $destination . '.' . $ext;

        $widthOrig = self::getWidth($img);
        $heightOrig = self::getHeight($img);

        $exif = @exif_read_data($img);

        $source = null;
        $rotate = null;
        if (!empty($exif['Orientation'])) {
            if ($ext === 'png') {
                switch ($exif['Orientation']) {
                    case 8:
                        $source = imagecreatefrompng($img);
                        $rotate = imagerotate($source, 90, 0);
                        imagepng($rotate, 'rotate.png');
                        $img = 'rotate.png';
                        $ck = 1;
                        break;
                    case 3:
                        $source = imagecreatefrompng($img);
                        $rotate = imagerotate($source, 180, 0);
                        imagepng($rotate, 'rotate.png');
                        $img = 'rotate.png';
                        $ck = 1;
                        break;
                    case 6:
                        $source = imagecreatefrompng($img);
                        $rotate = imagerotate($source, -90, 0);
                        imagepng($rotate, 'rotate.png');
                        $img = 'rotate.png';
                        $ck = 1;
                        break;
                    default:
                        $source = imagecreatefrompng($img);
                        $rotate = imagerotate($source, 0, 0);
                        imagepng($rotate, 'rotate.png');
                        $img = 'rotate.png';
                        $ck = 1;
                }
            }
            if ($ext === 'jpg' || $ext === 'jpeg') {
                switch ($exif['Orientation']) {
                    case 8:
                        $source = imagecreatefromjpeg($img);
                        $rotate = imagerotate($source, 90, 0);
                        imagejpeg($rotate, 'rotate.jpg');
                        $img = 'rotate.jpg';
                        $ck = 1;
                        break;
                    case 3:
                        $source = imagecreatefromjpeg($img);
                        $rotate = imagerotate($source, 180, 0);
                        imagejpeg($rotate, 'rotate.jpg');
                        $img = 'rotate.jpg';
                        $ck = 1;
                        break;
                    case 6:
                        $source = imagecreatefromjpeg($img);
                        $rotate = imagerotate($source, -90, 0);
                        imagejpeg($rotate, 'rotate.jpg');
                        $img = 'rotate.jpg';
                        $ck = 1;
                        break;
                    default:
                        $source = imagecreatefromjpeg($img);
                        $rotate = imagerotate($source, 0, 0);
                        imagejpeg($rotate, 'rotate.jpg');
                        $img = 'rotate.jpg';
                        $ck = 1;
                }
            }
            if ($ext === 'gif') {
                switch ($exif['Orientation']) {
                    case 8:
                        $source = imagecreatefromgif($img);
                        $rotate = imagerotate($source, 90, 0);
                        imagegif($rotate, 'rotate.gif');
                        $img = 'rotate.gif';
                        $ck = 1;
                        break;
                    case 3:
                        $source = imagecreatefromgif($img);
                        $rotate = imagerotate($source, 180, 0);
                        imagegif($rotate, 'rotate.gif');
                        $img = 'rotate.gif';
                        $ck = 1;
                        break;
                    case 6:
                        $source = imagecreatefromgif($img);
                        $rotate = imagerotate($source, -90, 0);
                        imagegif($rotate, 'rotate.gif');
                        $img = 'rotate.gif';
                        $ck = 1;
                        break;
                    default:
                        $source = imagecreatefromgif($img);
                        $rotate = imagerotate($source, 0, 0);
                        imagegif($rotate, 'rotate.gif');
                        $img = 'rotate.gif';
                        $ck = 1;
                }
            }
            $widthOrig = self::getWidth('rotate.' . $ext);
            $heightOrig = self::getHeight('rotate.' . $ext);
        }

        $ratio_orig = $widthOrig / $heightOrig;
        $height = $maxsize / $ratio_orig;
        $imagesFin = imagecreatetruecolor($maxsize, $height);

        imagesavealpha($imagesFin, true);
        $transColour = imagecolorallocatealpha($imagesFin, 0, 0, 0, 127);
        imagefill($imagesFin, 0, 0, $transColour);

        $image = null;
        if ($ext === 'png') {
            if ($ck === 1) {
                imagedestroy($source);
                imagedestroy($rotate);
            }
            $image = imagecreatefrompng($img);
            imagecopyresampled(
                $imagesFin,
                $image,
                0,
                0,
                0,
                0,
                $maxsize,
                $height,
                $widthOrig,
                $heightOrig
            );
            imagepng($imagesFin, $destination);
        }
        if ($ext === 'jpg' || $ext === 'jpeg' || $ext === 'JPG') {
            if ($ck === 1) {
                imagedestroy($source);
                imagedestroy($rotate);
            }
            $image = imagecreatefromjpeg($img);
            imagecopyresampled(
                $imagesFin,
                $image,
                0,
                0,
                0,
                0,
                $maxsize,
                $height,
                $widthOrig,
                $heightOrig
            );
            imagejpeg($imagesFin, $destination);
        }
        if ($ext === 'gif') {
            if ($ck === 1) {
                imagedestroy($source);
                imagedestroy($rotate);
            }
            $image = imagecreatefromgif($img);
            imagecopyresampled(
                $imagesFin,
                $image,
                0,
                0,
                0,
                0,
                $maxsize,
                $height,
                $widthOrig,
                $heightOrig
            );
            imagegif($imagesFin, $destination);
        }
        $un = 'rotate.' . $ext;
        if (file_exists($un)) {
            unlink($un);
        }

        imagedestroy($image);
        imagedestroy($imagesFin);
    }

    /**
     * @param string $img
     * @param int    $width
     * @param int    $height
     * @param string $destination
     * @return bool
     */
    public static function resizePng($img, $width, $height, $destination)
    {
        try {
            $imagesFin = imagecreatetruecolor($width, $height);

            imagesavealpha($imagesFin, true);
            $transColour = imagecolorallocatealpha($imagesFin, 0, 0, 0, 127);
            imagefill($imagesFin, 0, 0, $transColour);

            $image = @imagecreatefrompng($img);
            if (!$image) {
                return false;
            }

            $widthOrig = imagesx($image);
            $heightOrig = imagesy($image);
            imagecopyresampled(
                $imagesFin,
                $image,
                0,
                0,
                0,
                0,
                $width,
                $height,
                $widthOrig,
                $heightOrig
            );
            imagepng($imagesFin, $destination);
            imagedestroy($image);
            imagedestroy($imagesFin);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param string $img
     * @param string $desImg
     * @param int    $thumbWidth
     * @param int    $thumbHeight
     * @return void
     */
    public static function cropImageProp($img, $desImg, $thumbWidth, $thumbHeight)
    {
        $ext = self::getExtension($img);
        self::checkExtension($ext);

        $imgIn = imagecreatefromjpeg($img);
        $width = self::getWidth($img);
        $height = self::getHeight($img);
        $originalAspect = $width / $height;
        $thumbAspect = $thumbWidth / $thumbHeight;
        if ($originalAspect >= $thumbAspect) {
            $newHeight = $thumbHeight;
            $newWidth = $width / ($height / $thumbHeight);
        } else {
            $newWidth = $thumbWidth;
            $newHeight = $height / ($width / $thumbWidth);
        }
        $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);

        imagecopyresampled($thumb, $imgIn, 0 - ($newWidth - $thumbWidth) / 2, 0 - ($newHeight -
                $thumbHeight) / 2, 0, 0, $newWidth, $newHeight, $width, $height);
        switch ($ext) {
            case 'png':
                imagepng($thumb, "$desImg.$ext", 80);
                break;

            case 'jpg':
            case 'jpeg':
                imagejpeg($thumb, "$desImg.$ext", 80);
                break;

            case 'gif':
                imagegif($thumb, "$desImg.$ext");
                break;
        }
        imagedestroy($imgIn);
        imagedestroy($thumb);
    }

    /**
     * @param string $img
     * @param string $savePath
     * @param string $sliceName
     * @param int    $sx
     * @param int    $sy
     * @return void
     */
    public static function sliceImage($img, $savePath, $sliceName, $sx, $sy)
    {
        $ext = self::getExtension($img);
        self::checkExtension($ext);

        set_time_limit(0);
        $ext = self::getExtension($img);
        $width = self::getWidth($img);
        $height = self::getHeight($img);

        $destWidth = $width / $sx;
        $destHeight = $height / $sy;

        $cw = 0;
        $ch = 0;
        $px = 1;
        $py = 1;
        $counter = 1;
        $numberOfSlices = $sx * $sy;
        $savePath .= '/';

        $dest = null;
        $src = null;
        for ($c = 1; $c <= $numberOfSlices; ++$c) {
            //**********************************************
            if ($ext === 'png') {
                $src = imagecreatefrompng($img);
                $dest = imagecreatetruecolor($destWidth, $destHeight);
                imagesavealpha($dest, true);
                $transColour = imagecolorallocatealpha($dest, 0, 0, 0, 127);
                imagefill($dest, 0, 0, $transColour);
                unset($transColour);
                imagecopy(
                    $dest,
                    $src,
                    0,
                    0,
                    ($cw += $destWidth) - $destWidth,
                    $ch,
                    $destWidth,
                    $destHeight
                );
                imagepng($dest, $savePath . $py . '_' . ($px++) . '_' . $sliceName . '.' . $ext);
            }
            if ($ext === 'jpg' || $ext === 'jpeg') {
                $src = imagecreatefromjpeg($img);
                $dest = imagecreatetruecolor($destWidth, $destHeight);
                imagecopy(
                    $dest,
                    $src,
                    0,
                    0,
                    ($cw += $destWidth) - $destWidth,
                    $ch,
                    $destWidth,
                    $destHeight
                );
                imagejpeg($dest, $savePath . $py . '_' . ($px++) . '_' . $sliceName . '.' .
                    $ext);
            }
            if ($ext === 'gif') {
                $src = imagecreatefromgif($img);
                $dest = imagecreatetruecolor($destWidth, $destHeight);
                imagecopy(
                    $dest,
                    $src,
                    0,
                    0,
                    ($cw += $destWidth) - $destWidth,
                    $ch,
                    $destWidth,
                    $destHeight
                );
                imagegif($dest, $savePath . $py . '_' . ($px++) . '_' . $sliceName . '.' . $ext);
            }
            //**********************************************
            if (($counter++) === $sx) {
                $cw = 0;
                $px = 1;
                ++$py;
                $ch += $destHeight;
                $counter = 1;
            }
        }
        imagedestroy($dest);
        imagedestroy($src);
    }

    /**
     * @param string $img
     * @param string $imgOut
     * @return void
     */
    public static function imageGrayscale($img, $imgOut)
    {
        $ext = self::getExtension($img);
        self::checkExtension($ext);

        $im = null;
        if ($ext === 'png') {
            $im = imagecreatefrompng($img);
            imagefilter($im, IMG_FILTER_GRAYSCALE);
            imagepng($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $im = imagecreatefromjpeg($img);
            imagefilter($im, IMG_FILTER_GRAYSCALE);
            imagejpeg($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'gif') {
            $im = imagecreatefromgif($img);
            imagefilter($im, IMG_FILTER_GRAYSCALE);
            imagegif($im, $imgOut . '.' . $ext);
        }
        imagedestroy($im);
    }

    /**
     * @param string $img
     * @param string $imgOut
     * @return void
     */
    public static function imageNegate($img, $imgOut)
    {
        $ext = self::getExtension($img);
        self::checkExtension($ext);
        $im = null;

        if ($ext === 'png') {
            $im = imagecreatefrompng($img);
            imagefilter($im, IMG_FILTER_NEGATE);
            imagepng($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $im = imagecreatefromjpeg($img);
            imagefilter($im, IMG_FILTER_NEGATE);
            imagejpeg($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'gif') {
            $im = imagecreatefromgif($img);
            imagefilter($im, IMG_FILTER_NEGATE);
            imagegif($im, $imgOut . '.' . $ext);
        }
        imagedestroy($im);
    }

    /**
     * @param string $img
     * @param string $imgOut
     * @param int    $lvl
     * @return void
     */
    public static function imageBrightness($img, $imgOut, $lvl)
    {
        $ext = self::getExtension($img);
        self::checkExtension($ext);
        $im = null;

        if ($ext === 'png') {
            $im = imagecreatefrompng($img);
            imagefilter($im, IMG_FILTER_BRIGHTNESS, $lvl);
            imagepng($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $im = imagecreatefromjpeg($img);
            imagefilter($im, IMG_FILTER_BRIGHTNESS, $lvl);
            imagejpeg($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'gif') {
            $im = imagecreatefromgif($img);
            imagefilter($im, IMG_FILTER_BRIGHTNESS, $lvl);
            imagegif($im, $imgOut . '.' . $ext);
        }
        imagedestroy($im);
    }

    /**
     * @param string $img
     * @param string $imgOut
     * @param int    $lvl
     * @return void
     */
    public static function imageContrast($img, $imgOut, $lvl)
    {
        $ext = self::getExtension($img);
        self::checkExtension($ext);
        $im = null;

        if ($ext === 'png') {
            $im = imagecreatefrompng($img);
            imagefilter($im, IMG_FILTER_CONTRAST, $lvl);
            imagepng($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $im = imagecreatefromjpeg($img);
            imagefilter($im, IMG_FILTER_CONTRAST, $lvl);
            imagejpeg($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'gif') {
            $im = imagecreatefromgif($img);
            imagefilter($im, IMG_FILTER_CONTRAST, $lvl);
            imagegif($im, $imgOut . '.' . $ext);
        }
        imagedestroy($im);
    }

    /**
     * @param string $img
     * @param string $imgOut
     * @param int    $r
     * @param int    $g
     * @param int    $b
     * @param int    $alpha
     * @return void
     */
    public static function imageColorize($img, $imgOut, $r, $g, $b, $alpha)
    {
        $ext = self::getExtension($img);
        self::checkExtension($ext);
        $im = null;

        if ($ext === 'png') {
            $im = imagecreatefrompng($img);
            imagefilter($im, IMG_FILTER_COLORIZE, $r, $g, $b, $alpha);
            imagepng($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $im = imagecreatefromjpeg($img);
            imagefilter($im, IMG_FILTER_COLORIZE, $r, $g, $b, $alpha);
            imagejpeg($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'gif') {
            $im = imagecreatefromgif($img);
            imagefilter($im, IMG_FILTER_COLORIZE, $r, $g, $b, $alpha);
            imagegif($im, $imgOut . '.' . $ext);
        }
        imagedestroy($im);
    }

    /**
     * @param string $img
     * @param string $imgOut
     * @return void
     */
    public static function imageEdge($img, $imgOut)
    {
        $ext = self::getExtension($img);
        self::checkExtension($ext);
        $im = null;

        if ($ext === 'png') {
            $im = imagecreatefrompng($img);
            imagefilter($im, IMG_FILTER_EDGEDETECT);
            imagepng($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $im = imagecreatefromjpeg($img);
            imagefilter($im, IMG_FILTER_EDGEDETECT);
            imagejpeg($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'gif') {
            $im = imagecreatefromgif($img);
            imagefilter($im, IMG_FILTER_EDGEDETECT);
            imagegif($im, $imgOut . '.' . $ext);
        }
        imagedestroy($im);
    }

    /**
     * @param string $img
     * @param string $imgOut
     * @param int    $multiple
     * @return void
     */
    public static function imageEmboss($img, $imgOut, $multiple)
    {
        $ext = self::getExtension($img);
        self::checkExtension($ext);
        $im = null;

        if ($ext === 'png') {
            for ($m = 0; $m < $multiple; ++$m) {
                $im = imagecreatefrompng($img);
                imagefilter($im, IMG_FILTER_EMBOSS);
            }
            imagepng($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'jpg' || $ext === 'jpeg') {
            for ($m = 0; $m < $multiple; ++$m) {
                $im = imagecreatefromjpeg($img);
                imagefilter($im, IMG_FILTER_EMBOSS);
            }
            imagejpeg($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'gif') {
            for ($m = 0; $m < $multiple; ++$m) {
                $im = imagecreatefromgif($img);
                imagefilter($im, IMG_FILTER_EMBOSS);
            }
            imagegif($im, $imgOut . '.' . $ext);
        }
        imagedestroy($im);
    }

    /**
     * @param string $img
     * @param string $imgOut
     * @param int    $multiple
     * @return void
     */
    public static function imageGaussian($img, $imgOut, $multiple)
    {
        $ext = self::getExtension($img);
        self::checkExtension($ext);
        $im = null;

        if ($ext === 'png') {
            for ($m = 0; $m < $multiple; ++$m) {
                $im = imagecreatefrompng($img);
                imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
            }
            imagepng($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'jpg' || $ext === 'jpeg') {
            for ($m = 0; $m < $multiple; ++$m) {
                $im = imagecreatefromjpeg($img);
                imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
            }
            imagejpeg($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'gif') {
            for ($m = 0; $m < $multiple; ++$m) {
                $im = imagecreatefromgif($img);
                imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
            }
            imagegif($im, $imgOut . '.' . $ext);
        }
        imagedestroy($im);
    }

    /**
     * @param string $img
     * @param string $imgOut
     * @param int    $multiple
     * @return void
     */
    public static function imageBlurs($img, $imgOut, $multiple)
    {
        $ext = self::getExtension($img);
        self::checkExtension($ext);
        $im = null;

        if ($ext === 'png') {
            for ($m = 0; $m < $multiple; ++$m) {
                $im = imagecreatefrompng($img);
                imagefilter($im, IMG_FILTER_SELECTIVE_BLUR);
            }
            imagepng($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'jpg' || $ext === 'jpeg') {
            for ($m = 0; $m < $multiple; ++$m) {
                $im = imagecreatefromjpeg($img);
                imagefilter($im, IMG_FILTER_SELECTIVE_BLUR);
            }
            imagejpeg($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'gif') {
            for ($m = 0; $m < $multiple; ++$m) {
                $im = imagecreatefromgif($img);
                imagefilter($im, IMG_FILTER_SELECTIVE_BLUR);
            }
            imagegif($im, $imgOut . '.' . $ext);
        }
        imagedestroy($im);
    }

    /**
     * @param string $img
     * @param string $imgOut
     * @param int    $multiple
     * @return void
     */
    public static function imageSketchy($img, $imgOut, $multiple)
    {
        $ext = self::getExtension($img);
        self::checkExtension($ext);
        $im = null;

        if ($ext === 'png') {
            for ($m = 0; $m < $multiple; ++$m) {
                $im = imagecreatefrompng($img);
                imagefilter($im, IMG_FILTER_MEAN_REMOVAL);
            }
            imagepng($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'jpg' || $ext === 'jpeg') {
            for ($m = 0; $m < $multiple; ++$m) {
                $im = imagecreatefromjpeg($img);
                imagefilter($im, IMG_FILTER_MEAN_REMOVAL);
            }
            imagejpeg($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'gif') {
            for ($m = 0; $m < $multiple; ++$m) {
                $im = imagecreatefromgif($img);
                imagefilter($im, IMG_FILTER_MEAN_REMOVAL);
            }
            imagegif($im, $imgOut . '.' . $ext);
        }
        imagedestroy($im);
    }

    /**
     * @param string $img
     * @param string $imgOut
     * @param int    $lvl
     * @return void
     */
    public static function imageSmooth($img, $imgOut, $lvl)
    {
        $ext = self::getExtension($img);
        self::checkExtension($ext);
        $im = null;

        if ($ext === 'png') {
            $im = imagecreatefrompng($img);
            imagefilter($im, IMG_FILTER_SMOOTH, $lvl);
            imagepng($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $im = imagecreatefromjpeg($img);
            imagefilter($im, IMG_FILTER_SMOOTH, $lvl);
            imagejpeg($im, $imgOut . '.' . $ext);
        }
        if ($ext === 'gif') {
            $im = imagecreatefromgif($img);
            imagefilter($im, IMG_FILTER_SMOOTH, $lvl);
            imagegif($im, $imgOut . '.' . $ext);
        }
        imagedestroy($im);
    }

    /**
     * @param string $img
     * @param bool   $gs
     * @return array
     */
    public static function imageAverageRgb($img, $gs = false)
    {
        $ext = self::getExtension($img);
        self::checkExtension($ext);

        $filename = $img;
        list($width, $height) = getimagesize($filename);
        $newWidth = 1;
        $newHeight = 1;
        $imageNew = null;

        if ($ext === 'png') {
            $imageNew = imagecreatetruecolor($newWidth, $newHeight);
            $image = imagecreatefrompng($filename);
            imagecopyresampled(
                $imageNew,
                $image,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $width,
                $height
            );
        }
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $imageNew = imagecreatetruecolor($newWidth, $newHeight);
            $image = imagecreatefromjpeg($filename);
            imagecopyresampled(
                $imageNew,
                $image,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $width,
                $height
            );
        }
        if ($ext === 'gif') {
            $imageNew = imagecreatetruecolor($newWidth, $newHeight);
            $image = imagecreatefromgif($filename);
            imagecopyresampled(
                $imageNew,
                $image,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $width,
                $height
            );
        }
        if ($gs) {
            imagefilter($imageNew, IMG_FILTER_GRAYSCALE);
        }
        $rgb = imagecolorat($imageNew, 0, 0);
        $colors = imagecolorsforindex($imageNew, $rgb);
        imagedestroy($imageNew);

        return $colors;
    }

    /**
     * @param string $originalImage
     * @param string $outputImage
     * @param int    $quality
     * @return void
     */
    public static function all2jpg($originalImage, $outputImage, $quality = 80)
    {
        $ext = self::getExtension($originalImage);
        self::checkExtension($ext);
        $imageTmp = null;

        if ($ext === 'png') {
            $imageTmp = imagecreatefrompng($originalImage);
        }
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $imageTmp = imagecreatefromjpeg($originalImage);
        }
        if ($ext === 'gif') {
            $imageTmp = imagecreatefromgif($originalImage);
        }
        imagejpeg($imageTmp, $outputImage, $quality);
        imagedestroy($imageTmp);
    }

    /**
     * @param string $base64ImageString
     * @param string $outputFileWithoutExtension
     * @param string $pathWithEndSlash
     * @return string|null
     */
    public static function saveBase64Image($base64ImageString, $outputFileWithoutExtension, $pathWithEndSlash = '')
    {
        $outputFileWithExtension = null;
        $split = explode(',', substr($base64ImageString, 5), 2);
        $data = $split[1];

        $outputFileWithExtension = $outputFileWithoutExtension . '.' . self::base64ImageExt($base64ImageString);
        file_put_contents($pathWithEndSlash . $outputFileWithExtension, base64_decode($data));

        return $outputFileWithExtension;
    }

    /**
     * @param string $base64ImageString
     * @return string|null
     */
    public static function base64ImageExt($base64ImageString)
    {
        $split = explode(',', substr($base64ImageString, 5), 2);
        $mime = $split[0];
        $extension = null;

        $mimeSplitWithoutBase64 = explode(';', $mime, 2);
        $mime_split = explode('/', $mimeSplitWithoutBase64[0], 2);
        if (count($mime_split) === 2) {
            $extension = $mime_split[1];
            if ($extension === 'jpeg') {
                $extension = 'jpg';
            }
        }

        return $extension;
    }
}
