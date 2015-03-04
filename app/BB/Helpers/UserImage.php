<?php namespace BB\Helpers;

use BB\Exceptions\UserImageFailedException;
use Intervention\Image\Facades\Image;

class UserImage {

    protected static $bucket = 'buildbrighton-bbms';

    public function __construct()
    {

    }

    public function uploadPhoto($userId, $filePath, $newImage=false)
    {
        $tmpFilePath = storage_path("tmp")."/".$userId.".png";
        $tmpFilePathThumb = storage_path("tmp")."/".$userId."-thumb.png";


        try {
            $this->correctImageRotation($filePath);
        } catch (\Exception $e) {
            \Log::exception($e);
            //Continue on - this isnt that important
        }

        //Generate the thumbnail and larger image
        Image::make($filePath)->fit(500)->save($tmpFilePath);
        Image::make($filePath)->fit(200)->save($tmpFilePathThumb);

        if ($newImage) {
            $newFilename      = \App::environment() . '/user-photo/' . md5($userId) . '-new.png';
            $newThumbFilename = \App::environment() . '/user-photo/' . md5($userId) . '-thumb-new.png';
        } else {
            $newFilename      = \App::environment() . '/user-photo/' . md5($userId) . '.png';
            $newThumbFilename = \App::environment() . '/user-photo/' . md5($userId) . '-thumb.png';
        }

        $s3 = \AWS::get('s3');
        try {
            $s3->putObject(array(
                'Bucket'        => self::$bucket,
                'Key'           => $newFilename,
                'Body'          => file_get_contents($tmpFilePath),
                'ACL'           => 'public-read',
                'ContentType'   => 'image/png',
                'ServerSideEncryption' => 'AES256',
            ));
        } catch(\Exception $e) {
            \Log::exception($e);
            throw new UserImageFailedException();
        }

        try {
            $s3->putObject(array(
                'Bucket'        => self::$bucket,
                'Key'           => $newThumbFilename,
                'Body'          => file_get_contents($tmpFilePathThumb),
                'ACL'           => 'public-read',
                'ContentType'   => 'image/png',
                'ServerSideEncryption' => 'AES256',
            ));
        } catch(\Exception $e) {
            \Log::exception($e);
            throw new UserImageFailedException();
        }

        \File::delete($tmpFilePath);
        \File::delete($tmpFilePathThumb);
    }

    /**
     * Delete an old profile image and replace it with a new one.
     * @param $userId
     */
    public function approveNewImage($userId) {

        $sourceFilename      = \App::environment() . '/user-photo/' . md5($userId) . '-new.png';
        $sourceThumbFilename = \App::environment() . '/user-photo/' . md5($userId) . '-thumb-new.png';

        $targetFilename      = \App::environment() . '/user-photo/' . md5($userId) . '.png';
        $targetThumbFilename = \App::environment() . '/user-photo/' . md5($userId) . '-thumb.png';

        $s3 = \AWS::get('s3');
        $s3->copyObject(array(
            'Bucket'     => self::$bucket,
            'Key'        => $targetFilename,
            'CopySource' => self::$bucket."/".$sourceFilename,
            'ACL'           => 'public-read',
            'ContentType'   => 'image/png',
            'ServerSideEncryption' => 'AES256',
        ));
        $s3->deleteObject(array(
            'Bucket' => self::$bucket,
            'Key'    => $sourceFilename
        ));

        $s3->copyObject(array(
            'Bucket'     => self::$bucket,
            'Key'        => $targetThumbFilename,
            'CopySource' => self::$bucket."/".$sourceThumbFilename,
            'ACL'           => 'public-read',
            'ContentType'   => 'image/png',
            'ServerSideEncryption' => 'AES256',
        ));
        $s3->deleteObject(array(
            'Bucket' => self::$bucket,
            'Key'    => $sourceThumbFilename
        ));
    }

    public static function imageUrl($userId)
    {
        return "https://s3-eu-west-1.amazonaws.com/".self::$bucket."/".\App::environment().'/user-photo/'.md5($userId).'.png';
    }

    public static function thumbnailUrl($userId)
    {
        return "https://s3-eu-west-1.amazonaws.com/".self::$bucket."/".\App::environment().'/user-photo/'.md5($userId).'-thumb.png';
    }

    public static function newThumbnailUrl($userId)
    {
        return "https://s3-eu-west-1.amazonaws.com/".self::$bucket."/".\App::environment().'/user-photo/'.md5($userId).'-thumb-new.png';
    }

    public static function gravatar($email)
    {
        return 'https://www.gravatar.com/avatar/'.md5($email).'?s=200&d=mm';
    }

    public static function anonymous()
    {
        return 'https://www.gravatar.com/avatar/foo?s=200&d=mm&f=y';
    }

    /**
     * Check the rotation of images and correct them if needed
     * @param $filePath
     */
    private function correctImageRotation($filePath)
    {
        $exif_data = @exif_read_data($filePath);

        //Auto image rotation
        if (array_key_exists('Orientation', $exif_data) && array_key_exists('MimeType', $exif_data)) {
            $orientation = $exif_data['Orientation'];
            if ($exif_data['MimeType'] == 'image/jpeg') {
                if ($orientation == '1') {
                    //Correct
                } elseif ($orientation == '3') {
                    //Upside down
                    //rotate 180
                    $source = imagecreatefromjpeg($filePath);
                    $rotate = imagerotate($source, 180, 0);
                    imagejpeg($rotate, $filePath, 98);
                } elseif ($orientation == '6') {
                    //rotate 90 cw
                    $source = imagecreatefromjpeg($filePath);
                    $rotate = imagerotate($source, 270, 0);
                    imagejpeg($rotate, $filePath, 98);
                } elseif ($orientation == '8') {
                    //rotate 90 ccw
                    $source = imagecreatefromjpeg($filePath);
                    $rotate = imagerotate($source, 90, 0);
                    imagejpeg($rotate, $filePath, 98);
                }
            }
        }
    }

} 