<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Monolog\Logger;
use App\Models\Mime;
use Storage;
use DB;
use Auth;
use FFMpeg;
use URL;
use App;
use Image;
use Unoconv\Unoconv;
use Log;

class Attachment extends Model {

    /**
    *
    *   Requires FFMPEG
    *       -
    *
    *
    *   MAC Installation (Requires Homebrew):
    *       brew install ffmpeg --with-vpx --with-vorbis --with-libvorbis --with-vpx --with-vorbis --with-theora --with-libogg --with-libvorbis --with-gpl --with-version3 --with-nonfree --with-postproc --with-libaacplus --with-libcelt --with-libfaac --with-libfdk-aac --with-libfreetype --with-libmp3lame --with-libopencore-amrnb --with-libopencore-amrwb --with-libopenjpeg --with-openssl --with-libopus --with-libschroedinger --with-libspeex --with-libtheora --with-libvo-aacenc --with-libvorbis --with-libvpx --with-libx264 --with-libxvid --with-faac
    *
    *
    *
    *   Requires LibreOffice, Unoconv
    *       Libre Office: https://downloadarchive.documentfoundation.org/libreoffice/old/4.3.7.2/mac/
    *       brew install unoconv --HEAD
    *
    **/



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attachments';



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id' , 'filename' , 'slug' , 'disk' , 'bytes' , 'mime_id' , 'mime_subtype', 'progress'];




    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];



    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['mime'];
    



    /**
    *
    *   $supported
    *       - A list of the supported file formats by inoconv
    *
    **/
    static protected $supported = ['bib', 'doc', 'doc6', 'docx', 'doc95', 'docbook', 'html', 'odt', 'ott', 'ooxml', 'pdb', 'pdf', 'psw', 'rtf', 'latex', 'sdw', 'sdw4', 'sdw3', 'stw', 'sxw', 'text', 'txt', 'vor', 'vor4', 'vor3', 'xhtml', 'bib', 'doc', 'doc6', 'doc95', 'docbook', 'html', 'odt', 'ott', 'ooxml', 'pdb', 'pdf', 'psw', 'rtf', 'latex', 'sdw', 'sdw4', 'sdw3', 'stw', 'sxw', 'text', 'txt', 'vor', 'vor4', 'vor3', 'xhtml', 'bmp', 'emf', 'eps', 'gif', 'html', 'jpg', 'met', 'odd', 'odg', 'odp', 'pbm', 'pct', 'pdf', 'pgm', 'png', 'pot', 'ppm', 'ppt', 'pwp', 'ras', 'sda', 'sdd', 'sdd3', 'sdd4', 'sti', 'stp', 'svg', 'svm', 'swf', 'sxi', 'tiff', 'vor', 'vor3', 'vor4', 'vor5', 'wmf', 'xhtml', 'xpm', 'csv', 'dbf', 'dif', 'html', 'ods', 'ooxml', 'pdf', 'pts', 'pxl', 'sdc', 'sdc4', 'sdc3', 'slk', 'stc', 'sxc', 'vor3', 'vor4', 'vor', 'xhtml', 'xls', 'xls5', 'xlsx', 'xls95', 'xlt', 'xlt5', 'xlt95'];

    



    /**
    *
    *   $supported
    *       - A list of the supported file formats by inoconv
    *
    **/
    static public $images = ['gif', 'jpg', 'jpeg', 'png'];


    














    /**
    *
    *   files
    *       - Loads the Belongs to Many Relationship Attachments
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Promotion Attachment
    *
    **/
    public function mime(){

        return $this->belongsTo('App\Models\Mime','mime_id');

    }









    /**
    *
    *   store
    *       - Adds an Attachment
    *
    *   Params:
    *       - file: 			(FILE) The File to Save
    *       - disk:                 (String) The Disk to Lookup the Attachments On (Options: 'local' , 'public') 
    *
    *   Returns (INT):
    *       1. The Attachment ID
    *
    **/
	static public function store($file,$disk = 'local'){

        $extension   = $file->getClientOriginalExtension();
        $filename    = pathinfo( $file->getClientOriginalName() , PATHINFO_FILENAME);
        $size 		 = $file->getClientSize();
        $addon       = 0;

        //Get a Unique Name
        while( ++$addon ){
            $name = $filename . ( $addon > 1 ? '(' . ( $addon - 1 ) . ')' : '' ) . '.' . $extension;
            if( !Storage::disk( $disk )->exists( $name ) && !self::where( 'slug' , Str::slug( $name ) )->exists() ){
                $filename = $name;
                break;
            }
        }

        //Save the File to Disk
        Storage::disk( $disk )->put( $filename , File::get( $file ) );

        if( !( $mime = Mime::where( 'mime' , $file->getClientMimeType() )->first() ) ){

            //Check if the File Extension Exists
            $existing = Mime::where( 'extension' , '.' . $extension )->first();

            //Prepare the Mime Data
            $mime = explode( '/' , $file->getClientMimeType() );

            //Create a new Mime
            $mime = Mime::create([
                'name'      => ( $existing ? $existing->name : strtoupper( $extension ) . ' File' ),
                'mime'      => implode( '/' , $mime ),
                'type'      => $mime[0],
                'subtype'   => ( isset( $mime[1] ) ? $mime[1] : '' ),
                'extension' => '.' . $extension
            ]);
        }

        //Store the Attachment
        $attachment = self::create([
            'user_id'       => Auth::user()->id,
            'filename'      => $filename,
            'slug'          => Str::slug( $filename ),
            'disk'          => $disk,
            'bytes'         => $size,
            'mime_id'       => $mime->id,
            'progress'      => '100'
        ]);

        //Convert the Video
        if( $mime->type == 'video' ){
            if( function_exists('pcntl_fork') ){ 
        
                $pid = pcntl_fork();

                if( $pid && $pid > -1 ){

                    self::encode( $attachment , $disk );

                }

            }else{

                self::encode( $attachment , $disk );

            }
        }else
        if( !empty( env('Unoconv') ) && ( ( $mime->type !== 'image' || !in_array( $mime->subtype , self::$images )) && ( in_array( $extension , self::$supported ) || in_array( $mime->type , self::$supported ) ) ) ){
            if( function_exists('pcntl_fork') ){

                $pid = pcntl_fork();

                if( $pid && $pid > -1 ){

                    self::convert( $attachment , $disk );

                }

            }else{

                self::convert( $attachment , $disk );

            }
        }


        //Return the Attachment
        return $attachment;

	}











    /**
    *
    *   add
    *       - Gets the Path to a File
    *
    *   Params:
    *       - filename:             (String) The Filename
    *       - disk:                 (String) The Disk to Lookup the Attachments On (Options: 'local' , 'public') 
    *
    *   Returns (INT):
    *       1. The Attachment ID
    *
    **/
    static public function path($filename, $disk='local'){
        if( $disk == 'public' ){
        
            return public_path( 'assets/files/' . $filename );

        }
        
        return storage_path( 'app/' . $filename );
    }











    /**
    *
    *   URL
    *       - Gets the URL Path to a File
    *
    *   Params:
    *       - filename:             (String) The Filename to Load
    *
    *   Returns (INT):
    *       1. The Attachment ID
    *
    **/
    static public function URL( $filename ){

        return URL::to( '/assets/files/' . $filename );

    }
    




    
    










    






    /**
    *
    *   thumbnail
    *       - Loads an Existing Video Thumbnail
    *
    *   Params:
    *       - filename:     (String) The Filename
    *       - disk:         (String) The Disk to Lookup the Attachments On (Options: 'local' , 'public') 
    *
    *
    *   Returns (INT):
    *       1. The Attachment ID
    *
    **/
    static function thumbnail($filename , $disk='local'){
        return pathinfo( $filename , PATHINFO_FILENAME ) . '.jpg';

    }
    




	
    










    






    /**
    *
    *   grab
    *       - Loads an Existing Attachment
    *
    *   Params:
    *       - ids: 			(INT | Array) The Attachment ID or an Array of Attachment IDs
    *       - disk:         (String) The Disk to Lookup the Attachments On (Options: 'local' , 'public') 
    *
    *
    *   Returns (INT):
    *       1. The Attachment ID
    *
    **/
	static function grab($ids , $disk='local'){

		$attachments = self::find($ids);

		foreach( $attachments as $key => $data ){

            $data->file          = self::path( $data->filename , $disk );

			$attachments[ $key ] = $data;

		}

		return $attachments;

	}











    /**
    *
    *   encode
    *       - Encodes the Video and Forks the Process
    *
    *   Params:
    *       - filename:             (FILE) The Filename
    *       - disk:                 (String) The Disk to Lookup the Attachments On (Options: 'local' , 'public') 
    *
    *   Returns:
    *       n/a
    *
    **/
    private static function encode($attachment, $disk='local'){

        try {

            //Get the Filename
            $filename   = pathinfo( $attachment->filename , PATHINFO_FILENAME );

            //Get the Extension
            $extension  = pathinfo( $attachment->filename , PATHINFO_EXTENSION );

            //Get FFMPeg
            $FFMpeg     = FFMpeg\FFMpeg::create([
                'ffmpeg.binaries'       => env('FFMpeg'),
                'ffprobe.binaries'      => env('FFProbe')
            ]);
            
            //Get a Thumbnail
            $video  = $FFMpeg->open( self::path( $attachment->filename , $disk ) );
            $video->frame( FFMpeg\Coordinate\TimeCode::fromSeconds(1) )->save( self::path( $filename . '.jpg' , $disk ) );

            if( !in_array( $extension , [ 'mp4' ] ) ){

                //Save the new Name 
                $attachment->progress        = 0;
                $attachment->filename        = $filename . '.mp4' ;
                $attachment->mime->subtype   = 'mp4' ;
                $attachment->save();

                //Format the Video
                $format = new FFMpeg\Format\Video\X264('libmp3lame', 'libx264');

                //Start the Encoding
                $video->save($format, self::path( $filename . '.mp4' , $disk ) );

                //Set to 480p
                $format->setKiloBitrate(1000);

                //Save
                $video->save($format, self::path( $filename . '-480.mp4' , $disk ) );

                //Set to 480p
                $format->setKiloBitrate(500);

                //Update the Progress in the DB
                $format->on('progress', function($video, $format, $percentage) use ($attachment){
                    $attachment->progress = ( $percentage >= 99 ? 100 : $percentage );
                    $attachment->save();
                });

                //Save
                $video->save($format, self::path( $filename . '-320.mp4' , $disk ) );

            }else{

                //Format the Video
                $format = new FFMpeg\Format\Video\X264('libmp3lame', 'libx264');

                //Set to 480p
                $format->setKiloBitrate(1000);

                //Save
                $video->save($format, self::path( $filename . '-480.' . $extension , $disk ) );

                //Set to 480p
                $format->setKiloBitrate(500);

                //Update the Progress in the DB
                $format->on('progress', function($video, $format, $percentage) use ($attachment){
                    $attachment->progress = ( $percentage >= 99 ? 100 : $percentage );
                    $attachment->save();
                });

                //Save
                $video->save($format, self::path( $filename . '-320.' . $extension , $disk ) );

            }

        }catch(\RuntimeException $e){

            //Do Nothing

        }

    }











    /**
    *
    *   convert
    *       - Converts the document into a viewable image
    *
    *   Params:
    *       - filename:             (FILE) The Filename
    *       - disk:                 (String) The Disk to Lookup the Attachments On (Options: 'local' , 'public') 
    *
    *   Returns:
    *       n/a
    *
    **/
    private static function convert($attachment, $disk='local'){

        try {

            //Prepare the Filename
            $filename   = pathinfo( $attachment->filename , PATHINFO_FILENAME );

            //Prepare the Unoconv
            $unoconv    = Unoconv::create(array(
                'unoconv.binaries'      => env('Unoconv'),
                'timeout'               => '42'
            ),null);

            //Convert the current document to a PDF
            $unoconv->transcode( self::path( $attachment->filename , $disk ) , 'pdf' , self::path( $filename . '.pdf' , $disk ) , '1-1' );

            //Convert the PDF to an Image
            $unoconv->transcode( self::path( $filename . '.pdf' , $disk ) , 'jpg' , self::path( $filename . '.jpg' , $disk ) );

            //Remove the PDF
            Storage::disk( $disk )->delete( $filename . '.pdf' );

        }catch(\RuntimeException $e){

            //Do Nothing

        }

    }
    




    
    










    






    /**
    *
    *   resize
    *       - Resize an Attachment
    *
    *   Params:
    *       - filename:     (String) The Filename
    *       - width:        (INT) The Width to Resize to
    *       - height:       (INT) THe Height to Resize to
    *       - disk:         (String) The Disk to Lookup the Attachments On (Options: 'local' , 'public') 
    *
    *
    *   Returns (INT):
    *       1. The Attachment ID
    *
    **/
    static function resize($filename , $width, $height, $disk='local' ){

        $pathinfo = pathinfo( $filename );
        $resized  = $pathinfo['filename'] . '-' . $width . 'x' . $height . '.' . $pathinfo['extension'];

        if( !Storage::disk( $disk )->exists( $resized ) ){

            Image::make( self::path( $filename , $disk ) )->resize( $width , $height , function($constraint){

                $constraint->aspectRatio();

            })->save( self::path( $resized , $disk ) );

        }

        return $resized;

    }





}
