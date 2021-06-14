<?php
namespace SiteBundle\Entity\Traits;

use Symfony\Component\HttpFoundation\File\UploadedFile;

trait ImageUpload
{
    protected static $thumbsDir = 'thumbnails';

    public function getUploadRootDir()
    {
        // absolute path to your directory where images must be saved
        return __DIR__.'/../../../../../public/'.$this->getUploadDir();
    }

    public function getUploadDir()
    {
        return 'uploads/'. $this->upload_dir;
    }

    public function getAbsolutePath($field = 'image')
    {
        return null === $this->$field ? null : $this->getUploadRootDir().'/'.$this->$field;
    }

    public function getWebPath($field = 'image')
    {
        return null === $this->$field ? null : '/'.$this->getUploadDir().'/'.$this->$field;
    }

    public function getGalleryPath($fileName)
    {
        return '/uploads/'. $this->upload_dir . '/gallery/'. $fileName;
    }



    /**
     * Returns thumb file if exists
     * @param string $field web path to original file (relative, ex: uploads/members/cropped/azda4qs.jpg)
     * @param integer $width desired thumb's width
     * @param integer $height desired thumb's height
     * @return string thumbnail path if thumbnail exists, if not returns original file path
     */
    public function getThumb($field = 'image', $width, $height)
    {
        $origFilePath = $this->getWebPath($field);
        $pathInfo = explode('/', $origFilePath);
        if($pathInfo)
        {
            $filename = $pathInfo[count($pathInfo) - 1];
            if (count($pathInfo) > 1) {
                array_splice($pathInfo, -1);
                $uploadDir = implode('/', $pathInfo) . '/';
            } else {
                $uploadDir = '';
            }

            $thumbSrc = $uploadDir . self::$thumbsDir . '/' . $width . 'x' . $height . '-' .$filename;

            // return $this->webDir.'/'.$thumbSrc;
            return $thumbSrc;

            // return file_exists($this->webDir.'/'.$thumbSrc) ? $thumbSrc : $uploadDir . $filename;
        }

        return $origFilePath;
    }
}