<!-- resources/views/emails/default.blade.php -->
	
    @extends('layouts.email')
    @section('type', $type )
    @section('subject', @$subject )

    @section('header-background',  asset('public/assets/images/email/buyerslogout.png'))
    @section('header-title',  '<b>MAIL FROM<br>NEW BALANCE</b>')
    @section('header-title-background',  '')

    @section('content')

		{!! @$body !!}

	@stop