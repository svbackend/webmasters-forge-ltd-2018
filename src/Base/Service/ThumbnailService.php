<?php

/*
 * Actually it's not my class/code, idk who is author, but I really love it,
 * and I probably will publish it as composer package after some clean up
 */

declare(strict_types=1);

namespace Base\Service;

/**
 * Change size of images on the fly
 *
 * Examples:
 * echo ThumbnailService::init()->image('uploads/pic.jpg')->crop(500, 500); // Url to resized image
 * echo ThumbnailService::init()->image('uploads/pic.jpg')->quality(95)->fit(500, 500);
 * echo ThumbnailService::init()->image('uploads/pic.jpg')->fitWidth(500);
 * echo ThumbnailService::init()->image('uploads/pic.jpg')->fitHeight(500));
 * echo ThumbnailService::init()->image('uploads/pic.jpg')->background('ffcccc')->place(500, 500);
 *
 * todo maybe time to use this class as composer package?
 */
class ThumbnailService
{
    /**
     * @var string Full path to site root
     */
    private $_siteRoot;

    /**
     * @var string Path to cache folder
     */
    private $_cachePath;

    /**
     * @var string Url to cache folder
     */
    private $_cacheUrl;

    /**
     * @var int JPEG quality of generated images
     */
    private $_quality;

    /**
     * @var string Url to original image
     */
    private $_imageUrl;

    /**
     * @var string Path to default image (Used as fallback)
     */
    private $_defaultImage;

    /**
     * @var array
     * @example ['r' => 255, 'g' => 255, 'b' => 255]
     */
    private $_bg;
    private $_im;
    private $_newImageUrl;
    private $_newImagePath;

    /**
     * @var array mime-types of supported images
     */
    private $_mimeTypesImages = ['image/gif', 'image/jpeg', 'image/png'];

    public function getThumb($file, $width, $height)
    {
        return $this->image($file)->crop($width, $height);
    }

    public function __construct(string $siteRootPath, string $cachePath, string $cacheUrl, ?string $defaultImagePath, int $quality = 90)
    {
        if ($quality > 100 || $quality <= 0) {
            throw new \InvalidArgumentException(
                sprintf('Image Quality can\'t be less than 1 and greater than 100, %s given', $quality)
            );
        }

        $this->_siteRoot = $siteRootPath;
        $this->_cachePath = $cachePath;
        $this->_cacheUrl = $cacheUrl;
        $this->_defaultImage = $defaultImagePath;
        $this->_quality = $quality;
        $this->_bg = ['r' => 255, 'g' => 255, 'b' => 255];
    }

    /**
     * Set image by url
     *
     * @param string $imageUrl
     *
     * @return $this
     */
    public function image($imageUrl)
    {
        $this->_imageUrl = $imageUrl;
        return $this;
    }

    /**
     * Set cache path
     *
     * @param string $path
     *
     * @return $this
     */
    public function cachePath($path)
    {
        $this->_cachePath = $path;
        return $this;
    }

    /**
     * Set cache url
     *
     * @param string $url
     *
     * @return $this
     */
    public function cacheUrl($url)
    {
        $this->_cacheUrl = $url;
        return $this;
    }

    /**
     * Set jpeg quality
     *
     * @param int $quality
     *
     * @return $this
     */
    public function quality($quality)
    {
        $this->_quality = (int)$quality;
        if ($this->_quality > 100)
            $this->_quality = 100;
        if ($this->_quality < 1)
            $this->_quality = 1;
        return $this;
    }

    /**
     * Set default image
     *
     * @param string $image
     *
     * @return $this
     */
    public function defaultImage($image)
    {
        $this->_defaultImage = $image;
        return $this;
    }

    /**
     * Set background color
     *
     * @param string $hex
     *
     * @return $this
     */
    public function background($hex)
    {
        $this->_bg = $this->_hex2rgb($hex);
        return $this;
    }

    /**
     * Change size to provided parameters
     *
     * @param int $width
     * @param int $height
     * @param bool $force_resize
     *
     * @return string url to generated image
     */
    public function crop($width, $height, $force_resize = false)
    {
        return $this->_resize($width, $height, true, '', $force_resize);
    }

    public function fit($width, $height, $force_resize = false)
    {
        return $this->_resize($width, $height, false, '', $force_resize);
    }

    public function fitWidth($width, $force_resize = false)
    {
        return $this->_resize($width, $width, false, 'w', $force_resize);
    }

    public function fitHeight($height, $force_resize = false)
    {
        return $this->_resize($height, $height, false, 'h', $force_resize);
    }

    public function place($width, $height, $force_resize = false)
    {
        return $this->_resize($width, $height, false, 'p', $force_resize);
    }

    public static function getMime($file)
    {
        $ext = substr($file, strlen($file) - 3);
        if ($ext == 'jpg') {
            return 'image/jpeg';
        }
        if ($ext == 'png') {
            return 'image/png';
        }
        if ($ext == 'gif') {
            return 'image/gif';
        }
        return '';
    }

    private function _resize($width, $height, $crop = true, $fitwh = '', $force_resize = false)
    {
        $orig_image_url = $this->_imageUrl;
        $width = (int)$width;
        $height = (int)$height;

        if ($width <= 0 || $height <= 0) {
            $full_path = $this->_defaultImage;
        } else {
            $this->_clearImagePath();
            $full_path = $this->_getFullPath();
            if (!is_file($full_path))
                $full_path = $this->_defaultImage;
        }

        if (!$full_path || !is_file($full_path))
            return $orig_image_url;

        $mimeType = self::getMime($orig_image_url);
        if ($mimeType === null) {
            return '';
        }

        if (!in_array($mimeType, $this->_mimeTypesImages)) {
            return '1';
        }

        $sizes = getimagesize($full_path);
        if (!$sizes) {
            return '2';
        }
        $w = $sizes[0];
        $h = $sizes[1];
        if ($w == 0 || $h == 0) {
            return '3';
        }

        if (!$force_resize) {
            if (
                ($fitwh == 'w' && $width == $w) ||
                ($fitwh == 'h' && $height == $h) ||
                ($fitwh != 'w' && $fitwh != 'h' && $width == $w && $height == $h)
            ) {
                return $orig_image_url;
            }
        }

        $new_filename = md5($full_path . filesize($full_path)) . '.jpg';
        $new_folder_p1 = $width . 'x' . $height;
        if (!$crop && $fitwh == 'w')
            $new_folder_p1 = $width;
        if (!$crop && $fitwh == 'h')
            $new_folder_p1 = $height;
        $new_folder_p1 .= '-q' . $this->_quality;
        if ($this->_bg['r'] != 255 || $this->_bg['g'] != 255 || $this->_bg['b'] != 255)
            $new_folder_p1 .= '-' . $this->_rgb2hex($this->_bg);
        if ($crop)
            $new_folder_p1 .= '-crop';
        if (!$crop && $fitwh == 'w')
            $new_folder_p1 .= '-fitw';
        if (!$crop && $fitwh == 'h')
            $new_folder_p1 .= '-fith';
        if (!$crop && $fitwh == 'p')
            $new_folder_p1 .= '-pl';
        $new_folder_p2 = substr($new_filename, 0, 2);
        $new_folder_url = $this->_cacheUrl . '/' . $new_folder_p1 . '/' . $new_folder_p2;
        $new_folder_path = $this->_cachePath . DIRECTORY_SEPARATOR . $new_folder_p1 . DIRECTORY_SEPARATOR . $new_folder_p2;

        if (!is_dir($new_folder_path))
            if (!mkdir($new_folder_path, 0755, true)) return $orig_image_url;

        $this->_newImageUrl = $new_folder_url . '/' . $new_filename;
        $this->_newImagePath = $new_folder_path . DIRECTORY_SEPARATOR . $new_filename;

        if (is_file($this->_newImagePath))
            return $this->_newImageUrl;

        $this->_im = false;
        switch ($mimeType) {
            case 'image/gif':
                $this->_im = \imagecreatefromgif($full_path);
                break;
            case 'image/jpeg':
                $this->_im = \imagecreatefromjpeg($full_path);
                break;
            case 'image/png':
                $this->_im = \imagecreatefrompng($full_path);
                break;
            default :
                return $orig_image_url;
        }
        if ($this->_im !== false) {
            $dst_x = 0;
            $dst_y = 0;
            $x = 0;
            $y = 0;

            if ($crop) {
                $ratio = max($width / $w, $height / $h);
                $new_w = round($w * $ratio);
                $new_h = round($h * $ratio);
                $x = round(($w - $width / $ratio) / 2);
                $y = round(($h - $height / $ratio) / 2);
            }
            elseif ($fitwh == 'w') {
                $new_w = $width;
                $new_h = $new_w / $w * $h;
                $width = $new_w;
                $height = $new_h;
            }
            elseif ($fitwh == 'h') {
                $new_h = $height;
                $new_w = $new_h * $w / $h;
                $width = $new_w;
                $height = $new_h;
            }
            elseif ($fitwh == 'p') {
                $ratio = min($width / $w, $height / $h);
                $new_w = round($w * $ratio);
                $new_h = round($h * $ratio);
                $dst_x = round(($width - $new_w) / 2);
                $dst_y = round(($width - $new_h) / 2);
            }
            else {
                $ratio = min($width / $w, $height / $h);
                $new_w = round($w * $ratio);
                $new_h = round($h * $ratio);
                $width = $new_w;
                $height = $new_h;
            }
            $this->_saveImage($width, $height, $dst_x, $dst_y, (int)$x, (int)$y, (int)$new_w, (int)$new_h, $w, $h);

            if (is_file($this->_newImagePath))
                return $this->_newImageUrl;
        }
        return $orig_image_url;
    }

    private function _clearImagePath()
    {
        $this->_imageUrl = trim(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $this->_imageUrl), DIRECTORY_SEPARATOR);
    }

    private function _getFullPath()
    {
        return $this->_siteRoot . DIRECTORY_SEPARATOR . $this->_imageUrl;
    }
    /**
     * @param int $width
     * @param int $height
     * @param int $dst_x
     * @param int $dst_y
     * @param int $x
     * @param int $y
     * @param int $new_w
     * @param int $new_h
     * @param int $w
     * @param int $h
     */
    private function _saveImage($width, $height, $dst_x, $dst_y, $x, $y, $new_w, $new_h, $w, $h)
    {
        $new_im = imagecreatetruecolor($width, $height);
        $bg_color = imagecolorallocate($new_im, $this->_bg['r'], $this->_bg['g'], $this->_bg['b']);
        imagefill($new_im, 0, 0, $bg_color);
        imagecopyresampled($new_im, $this->_im, $dst_x, $dst_y, $x, $y, $new_w, $new_h, $w, $h);
        imagejpeg($new_im, $this->_newImagePath, $this->_quality);
        imagedestroy($this->_im);
        imagedestroy($new_im);
    }

    private function _hex2rgb($hex)
    {
        $hex = str_replace('#', '', $hex);
        if (!preg_match('/^[0-9a-f]+$/', $hex))
            return ['r' => 255, 'g' => 255, 'b' => 255];
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return ['r' => $r, 'g' => $g, 'b' => $b];
    }

    private function _rgb2hex($rgb)
    {
        $hex = '';
        $hex .= str_pad(dechex($rgb['r']), 2, '0', STR_PAD_LEFT);
        $hex .= str_pad(dechex($rgb['g']), 2, '0', STR_PAD_LEFT);
        $hex .= str_pad(dechex($rgb['b']), 2, '0', STR_PAD_LEFT);
        return $hex;
    }
}