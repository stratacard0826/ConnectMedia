<!-- resources/views/emails/logout.blade.php -->
    
    @extends('layouts.email')
    @section('type', $data['type'])
    @section('subject', $data['subject'] )

    @section('header-background',  asset('public/assets/images/email/companynews.png'))
    @section('header-title',  'COMPANY NEWS')
    @section('header-title-background',  '#666666')
    @section('header-big-title',  $data['type'] )
    @section('header-small-title', $data['subject'] )

    @section('content')

        {{ $data['article'] }}

    @stop
