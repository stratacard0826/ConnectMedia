<!-- resources/views/emails/logout.blade.php -->
    
    @extends('layouts.email')
    @section('type', @$type . ' Tech Talk Product')
    @section('subject', @$data['name'] )

    @section('header-background',  asset('public/assets/images/email/welcome.png'))
    @section('header-title',  'WELCOME')
    @section('header-title-background',  '#00d305')
    @section('header-big-title',  'WELCOME TO THE NEW BALANCE<br>VANCOUVER ONLINE PORTAL')
    @section('header-small-title', '' )

    @section('content')

        
    @stop
