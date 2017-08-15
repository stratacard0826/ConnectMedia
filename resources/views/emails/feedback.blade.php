<!-- resources/views/emails/feedback.blade.php -->
	
    @extends('layouts.email')
    @section('type', $data['type'] . ' Feedback')
    @section('subject', 'New ' . $data['type'] . ' Feedback for ' . $data['name'] )
    @section('content')

		<p style="font-family:'Helvetica';">{!! nl2br($data['message']) !!}</p>

	@stop