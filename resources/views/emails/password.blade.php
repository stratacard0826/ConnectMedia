<!-- resources/views/emails/password.blade.php -->
	
    @extends('layouts.email')
    @section('type', 'Password Reset')
    @section('subject', 'Click the Link Below to Reset Your Password' )
    @section('content')

        <a href="{{ url('password/reset/'.$token) }}" style="width:200px; margin:0 auto; padding:20px; background:#E02222; color:#FFF; border-radius:5px; -webkit-border-radius:5px; -moz-border-radius:5px; text-decoration:none; font-weight:light; font-size:18px; font-family:'Helvetica'; display:block; text-align:center;">Reset My Password</a>

        <hr style="background:#EFEFEF; border:none; height:1px; margin:20px 0;" />

        <p style="font-family:'Helvetica'; margin:20px 0;"><small style="font-size:10px;">** If you did not request a password reset please ignore this email or contact: <a href="{{ env('WEBMASTER_EMAIL') }}" style="color:#000; font-family:'Helvetica';">The Webmaster</a>

    @stop