<?php

namespace AppBundle\Service;

use AppBundle\Entity\Meal;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;
use Symfony\Component\HttpFoundation\File\File;

class PhotoService
{
    /**
     * @var string
     */
    private $uploadsDir;

    /**
     * @var string
     */
    private $uploadsUriPrefix;

    private $sizes = array(
        '300'  => array(300, 100),
        '800' => array(800,  600),
    );

    public function __construct($uploadsDir, $uploadsUriPrefix)
    {
        $this->uploadsDir = $uploadsDir;
        $this->uploadsUriPrefix = $uploadsUriPrefix;
    }

    public function get(Meal $meal, $size)
    {
        $filename = $meal->getId().'-'.$size.'.jpg';

        if (file_exists($this->uploadsDir.'/'.$filename)) {
            return $this->uploadsUriPrefix.'/'.$filename;
        }

        return null;
    }

    public function upload(Meal $meal, File $file)
    {
        $imagine = new Imagine();
        //$mode    = ImageInterface::THUMBNAIL_INSET;
        $mode    = ImageInterface::THUMBNAIL_OUTBOUND;

        foreach ($this->sizes as $name => $size) {
            $path = $this->uploadsDir.'/'.$meal->getId().'-'.$name.'.jpg';

            $dir = dirname($path);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $imagine->open($file)
                ->thumbnail(new Box($size[0], $size[1]), $mode)
                ->save($path)
            ;
        }
    }
}
