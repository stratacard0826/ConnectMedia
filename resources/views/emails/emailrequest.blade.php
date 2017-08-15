<!-- resources/views/emails/emailrequest.blade.php -->
    
    @extends('layouts.email')
    @section('subject', $subject )
    @section('content')

        <!-- Content -->
        <div class="notes" style="padding:10px 0 50px; font-size:15px;">
            <b>Email: </b>{{ $data['email'] }}<br>
            <b>Password: </b>{{ $data['password'] }}<br>
            <b>Content: </b>{{ $data['message'] }}<br>
            <br><br>
            {{$data["user"]->firstname}} {{$data["user"]->lastname}}
        </div>

    @stop
