<?php

namespace App\Service;

use App\Entity\Meal;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;
use Symfony\Component\HttpFoundation\File\File;

class PhotoService
{
    private string $uploadsDir;
    private string $uploadsUriPrefix;
    private array $sizes = [
        '300'  => [300, 100],
        '800' => [800,  600],
    ];

    public function __construct(string $uploadsDir, string $uploadsUriPrefix)
    {
        $this->uploadsDir = $uploadsDir;
        $this->uploadsUriPrefix = $uploadsUriPrefix;
    }

    public function getPortage(string $size): ?string
    {
        return $this->doGet('portage', $size);
    }

    public function get(Meal $meal, string $size): ?string
    {
        return $this->doGet($meal->getId(), $size);
    }

    public function upload(Meal $meal, File $file): void
    {
        $this->doUpload($file, $meal->getId());
    }

    public function uploadPortage(File $file): void
    {
        $this->doUpload($file, 'portage');
    }

    private function doGet(string $identifier, string $size): ?string
    {
        if (!isset($this->sizes[$size])) {
            return null;
        }

        $filename = $identifier.'-'.$size.'.jpg';

        if (file_exists($this->uploadsDir.'/'.$filename)) {
            return $this->uploadsUriPrefix.'/'.$filename;
        }

        return null;
    }

    private function doUpload(File $file, string $identifier): void
    {
        $imagine = new Imagine();
        $mode    = ImageInterface::THUMBNAIL_OUTBOUND;

        foreach ($this->sizes as $name => $size) {
            $path = $this->uploadsDir.'/'.$identifier.'-'.$name.'.jpg';

            $dir = dirname($path);
            if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
            }

            $imagine->open($file)
                ->thumbnail(new Box($size[0], $size[1]), $mode)
                ->save($path)
            ;
        }
    }
}
