<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Bican\Roles\Models\Permission;
use Bican\Roles\Models\Role;
use App\Models\User;
use App\Models\Reminder;
use App\Models\Event;
use App\Models\Attachment;
use App\Services\CommonService;
use Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();


        
        $schedule->call(function(){

            //Get the Recipients
            $Recipients = User::where(function($query){

                //Get the recipient roles
                $query->where(function($query){

                    //Get all the Roles with Recipient ALL status
                    $roles = Role::whereHas( 'permissions' , function($query){

                        $query->where( 'slug' , 'users.recipient' );

                    })->get()->lists('id');

                    if(!empty( $roles )){

                        //Has the Roles
                        $query->whereHas( 'roles' , function($query) use ($roles){

                            $query->whereIn( 'role_id' , $roles );

                        });

                    }

                });



                //CGet the "All" Role User Permissions
                $query->orWhereHas( 'userPermissions' , function($query){

                    //Has Permission with All Recipients
                    $query->where( 'slug' , 'users.recipient' );

                });


            })->get();

            //Within a Week
            $week      = User::where('dob' , 'LIKE' , '%-' . date('m-d',strtotime('1 week')))->get();

            //Tomorrow
            $tomorrow  = User::where('dob' , 'LIKE' , '%-' . date('m-d',strtotime('tomorrow')))->get();

            //Today
            $today     = User::where('dob' , 'LIKE' , '%-' . date('m-d'))->get();

            //If we have birthdays
            if( count(array_merge( $week->toArray() , $tomorrow->toArray() , $today->toArray() )) > 0 ){
                /**
                * Get all recipients including administrators
                */
                $Recipients = CommonService::getUsersForSendingMails($Recipients);

                //Send the Email
                Mail::send([ 'html' => 'emails.birthday'] , [ 'week' => $week , 'tomorrow' => $tomorrow , 'today' => $today ] , function($message) use ( $Recipients ){

                    //Setup Recipients
                    foreach( $Recipients as $user ){
                        if( $user->email ){

                            $user->fullname = ( !empty( $user->firstname ) && !empty( $user->lastname ) ? $user->firstname . ' ' . $user->lastname : null );

                            $message->to( $user->email , $user->fullname );

                        }
                    }

                    //Set Subject
                    $message->subject( 'Upcoming Birthdays - ' . env('COMPANY') );

                });

            }

        })->dailyAt('1:00');

        $schedule->call(function(){
            /**
            * Get all reminders that will be not done.
            */
            $reminders = Reminder::with(['event'])->where('is_sent', 0)->where('module_name', 'event')->get();
            foreach($reminders as $reminder){
                $event          = $reminder->event;
                
                $time           = strtotime($event->start);
                $day            = intval(date('d', $time));
                $month          = intval(date('m', $time));
                $year           = intval(date('Y', $time));
                $hour           = intval(date('H', $time));
                $minute         = intval(date('i', $time));
                $second         = intval(date('s', $time));
                $sendingDate    = "";
                 
                /**
                * Get sending date from period and start time of this event
                */
                switch($reminder->period){
                    case "day":
                        $sendingDate = date('Y-m-d H:i:s', mktime($hour, $minute, $second, $month, $day-$reminder->timecount, $year));
                    break;
                    case "week":
                        $sendingDate = date('Y-m-d H:i:s', mktime($hour, $minute, $second, $month, $day-($reminder->timecount * 7), $year));
                    break;
                    case "month":
                        $sendingDate = date('Y-m-d H:i:s', mktime($hour, $minute, $second, $month - $reminder->timecount, $day, $year));
                    break;
                }
                
                if($sendingDate && strtotime($sendingDate) - time() < 30*60){
                    /**
                    * If it is around 30 mins at the sending time, send emails to users who has roles and stores of this event
                    */
                    $event = Event::with(['roles', 'stores', 'attachments'])->find($event->id);
                    $data = array(
                        'id'            => $event['id'],
                        'user_id'       => $event['user_id'],
                        'name'          => $event['name'],
                        'details'       => $event['details'],
                        'start'         => $event['start'],
                        'end'           => $event['end'],
                        'roles'         => $event['roles']->toArray(),
                        'stores'        => $event['stores']->toArray(),
                        'attachments'   => $event['attachments']->toArray(),
                    );
                    $attachments = [];
                    foreach( $data['attachments'] as $key => $file ){
                        $attachments[] = $file['id'];
                    }

                    $attachments = Attachment::grab($attachments);

                    $Recipients = array();
                    
                    if( $reminder->type == "role" ){
                        /**
                        * if a type of this reminder is role, get users in role.
                        */
                        $Recipients = User::where(function($query) use ($reminder) {
                            $query->whereHas( 'roles' , function($query) use ($reminder) {
                                $query->where( 'role_id' , $reminder->role_id );
                            });
                        })->get();

                    }elseif( $reminder->type == "me" ){
                        /**
                        * if a type of this reminder is me, get author user
                        */
                        $Recipients = User::where('id', $event->user_id)->get();
                        
                    }elseif( $reminder->type == "all" ){
                        /**
                        * if a type of this reminder is all, get users in role and stores
                        */
                    
                        if( !empty( $data['stores'] ) || !empty( $data['roles'] ) ){
                            $Recipients = User::where(function($query) use ($data) {
                                if( !empty( $data['stores'] ) ){
                                    $query->whereHas( 'stores' , function($query) use ($data) {
                                        $stores = [];
                                        array_walk( $data['stores'] , function($item) use (&$stores) {
                                            if( !empty($item['id']) ){
                                                $stores[] = $item['id'];
                                            }
                                        });
                                        $query->whereIn( 'store_id' , $stores );
                                    });
                                }
                                if( !empty( $data['roles'] ) ){
                                    $query->whereHas( 'roles' , function($query) use ($data) {
                                        $roles = [];
                                        array_walk( $data['roles'] , function(&$item) use (&$roles) {
                                            if( !empty($item['id']) ){
                                                $roles[] = $item['id'];
                                            }
                                        });
                                        $query->whereIn( 'role_id' , $roles );
                                    });
                                }
                            })->get();
                        }else{
                            $Recipients = User::all();
                        }
                    
                    }
                    
                    //Send the Email
                    Mail::send([ 'html' => 'emails.default'] , [ 'body' => $data['details'] , 'type' => 'Reminder for an event', 'subject' => '<p style="margin-top:0;">' . $data['name'] . '</p> <p>' . date('M d, Y' , strtotime( $data['start'] ) ) . ' to ' . date('M d, Y' , strtotime( $data['end'] ) ) . '</p>' ] , function($message) use ( $data , $Recipients , $attachments ){
                        //Setup Recipients
                        foreach( $Recipients as $user ){
                            if( $user->email ){
                                $user->fullname = ( !empty( $user->firstname ) && !empty( $user->lastname ) ? $user->firstname . ' ' . $user->lastname : null );
                                $message->to( $user->email , $user->fullname );
                            }
                        }
                        //Set Subject
                        $message->subject( 'Reminder: ' . $data['name'] . ' - ' . env('COMPANY') );
                        //Attach the Attachments
                        if( count( $attachments ) > 0 ){
                            foreach( $attachments as $attachment ){
                                $message->attach( $attachment->file );
                            }
                        }
                    });
                    
                    /**
                    * Mark is_sent = 1 for the reminders to be done.
                    */
                    if( count($Recipients) > 0 ){
                        $reminder->is_sent = 1;
                        $reminder->save();
                    }
                }
            }
        
        })->everyThirtyMinutes();
    }
}
