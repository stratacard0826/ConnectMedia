<!-- resources/views/emails/logout.blade.php -->
    
    @extends('layouts.email')
    @section('type', $data['type'] )
    @section('subject', @$data['name'] . ' - ' . $data['period'] )

    @section('header-background',  asset('public/assets/images/email/event.png'))
    @section('header-title',  $data['type'] )
    @section('header-title-background',  '#00d2f1')
    @section('header-big-title',  $data['name'])
    @section('header-small-title', $data['period'] ) )

    @section('content')

        {{ $data['body'] }}
        
    @stop
