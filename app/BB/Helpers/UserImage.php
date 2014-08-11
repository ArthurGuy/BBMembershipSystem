<?php namespace BB\Helpers;

class UserImage {

    protected static $bucket = 'buildbrighton-bbms-dev';

    public function __construct()
    {

    }

    public function uploadPhoto($userId, $filePath)
    {
        $fileContent = file_get_contents($filePath);
        $fileInfo = getimagesize($filePath);
        $fileInfo['mime'];


        if ($fileInfo['mime'] == 'image/png')
        {
            $im = imagecreatefrompng($filePath);

            //The image needs to be true colour in order for it to render properly in the pdf
            if (!imageistruecolor($im))
            {
                //Convert the image from palette colour to standard RGBA and save back to the file system
                imagepalettetotruecolor($im);
                imagepng($im, $filePath);
            }
        }
        else
        {
            imagepng(imagecreatefromstring($fileContent), storage_path("tmp")."/".$userId.".png");
            $fileContent = file_get_contents(storage_path("tmp")."/".$userId.".png");
            /*
             * @TODO:delete this temp file
             */
        }

        /*
         * @TODO:Crop the image into the correct ratio
         */



        $s3 = \AWS::get('s3');
        try {
            $s3->putObject(array(
                'Bucket'        => self::$bucket,
                'Key'           => \App::environment().'/user-photo/'.md5($userId).'.png',
                'Body'          => $fileContent,
                'ACL'           => 'public-read',
                'ContentType'   => 'image/png'
            ));
        } catch(Exception $e) {
            \Log::exception($e);

            throw new UserImageFailedException();
        }
    }

    public static function thumbnailUrl($userId)
    {
        return "https://s3-eu-west-1.amazonaws.com/".self::$bucket."/".\App::environment().'/user-photo/'.md5($userId).'.png';
    }

} 