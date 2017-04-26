<?php

namespace BB\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CCTVController extends Controller
{

    public function storeSingle()
    {
        if (Request::hasFile('image')) {
            $file     = Request::file('image');
            $fileData = Image::make($file)->encode('jpg', 80);

            $date = Carbon::now();
            $folderName = $date->hour . ':' . $date->minute . ':' . $date->second;

            $newFilename = \App::environment() . '/cctv/' . $date->year . '/' . $date->month . '/' . $date->day . '/' . $folderName . '/' . str_random() . '.jpg';
            Storage::put($newFilename, (string) $fileData, 'public');

            \Slack::to("#cctv")->attach(['image_url'=>'https://s3-eu-west-1.amazonaws.com/buildbrighton-bbms/' . $newFilename, 'color'=>'warning'])->send('New image');
        } else {
            $data = Request::all();
            \Log::debug($data);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $s3 = AWS::get('s3');
        $s3Bucket = 'buildbrighton-bbms';

        if (Request::hasFile('image')) {
            $file = Request::file('image');
            $event = Request::get('textevent');
            $time = Request::get('time');

            $fileData = Image::make($file)->encode('jpg', 80);


            $date = Carbon::createFromFormat('YmdHis', $event);
            $folderName = $date->hour . ':' . $date->minute . ':' . $date->second;

            try {
                $newFilename = \App::environment() . '/cctv/' . $date->year . '/' . $date->month . '/' . $date->day . '/' . $folderName . '/' . $time . '.jpg';
                $s3->putObject(array(
                    'Bucket'        => $s3Bucket,
                    'Key'           => $newFilename,
                    //'Body'          => file_get_contents($file),
                    'Body'          => $fileData,
                    'ACL'           => 'public-read',
                    'ContentType'   => 'image/jpg',
                    'ServerSideEncryption' => 'AES256',
                ));
            } catch(\Exception $e) {
                \Log::exception($e);
            }
            //Log::debug('Image saved :https://s3-eu-west-1.amazonaws.com/buildbrighton-bbms/'.$newFilename);
        }
        if (Request::get('eventend') == 'true') {

            $event = Request::get('textevent');

            $date = Carbon::createFromFormat('YmdHis', $event);
            $folderName = $date->hour . ':' . $date->minute . ':' . $date->second;

            $iterator = $s3->getIterator(
                'ListObjects',
                array(
                    'Bucket' => $s3Bucket,
                    'Prefix' => \App::environment() . '/cctv/' . $date->year . '/' . $date->month . '/' . $date->day . '/' . $folderName,
                    //'Prefix' => 'production/camera-photos/20150410222028',
                )
            );

            $images         = [];
            $imageDurations = [];
            foreach ($iterator as $object) {
                $images[]         = 'https://s3-eu-west-1.amazonaws.com/buildbrighton-bbms/' . $object['Key'];
                $imageDurations[] = 35;
            }

            if (count($images) <= 2) {
                //only two images, probably two bad frames
                //delete them
                foreach ($iterator as $object) {
                    Log::debug("Deleting small event image " . $object['Key']);
                    $s3->deleteObject([
                        'Bucket'    => $s3Bucket,
                        'Key'       => $object['Key'],
                    ]);
                }
                return;
            }

            $gc = new GifCreator();
            $gc->create($images, $imageDurations, 0);
            $gifBinary = $gc->getGif();

            //Delete the individual frames now we have the gif
            foreach ($iterator as $object) {
                //Log::debug("Processed gif, deleting frame, ".$object['Key']);
                $s3->deleteObject([
                    'Bucket'    => $s3Bucket,
                    'Key'       => $object['Key'],
                ]);
            }

            //Save the gif
            $newFilename = \App::environment() . '/cctv/' . $date->year . '/' . $date->month . '/' . $date->day . '/' . $folderName . '.gif';
            $s3->putObject(
                array(
                    'Bucket'               => $s3Bucket,
                    'Key'                  => $newFilename,
                    'Body'                 => $gifBinary,
                    'ACL'                  => 'public-read',
                    'ContentType'          => 'image/gif',
                    'ServerSideEncryption' => 'AES256',
                )
            );




            //Log::debug('Event Gif generated :https://s3-eu-west-1.amazonaws.com/buildbrighton-bbms/'.$newFilename);

            \Slack::to("#cctv")->attach(['image_url'=>'https://s3-eu-west-1.amazonaws.com/buildbrighton-bbms/' . $newFilename, 'color'=>'warning'])->send('Movement detected');

        }
    }

}
