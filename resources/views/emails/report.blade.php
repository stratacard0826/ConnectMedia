<!-- resources/views/emails/report.blade.php -->
	
    @extends('layouts.email')
    @section('type', 'New Report')
    @section('subject', $name . ' Report is now available' )
    @section('content')

        <a href="{{ url( 'reports/view/' . $id ) }}" style="width:200px; margin:0 auto; padding:20px; background:#E02222; color:#FFF; border-radius:5px; -webkit-border-radius:5px; -moz-border-radius:5px; text-decoration:none; font-weight:light; font-size:18px; font-family:'Helvetica'; display:block; text-align:center;">View the Report</a>

	@stop