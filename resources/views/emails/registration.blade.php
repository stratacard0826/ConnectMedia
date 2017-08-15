<!-- resources/views/emails/password.blade.php -->
	
    @extends('layouts.email')
    @section('type', 'Welcome')
    @section('subject', 'Welcome to the ' . env('COMPANY') . ' Online Portal' )
    @section('content')

        <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; width:100%; border:1px solid #DDD;">
            <thead>
                <tr>
                    <th style="border-collapse:collapse; border:1px solid #DDD; border-bottom-width:2px; padding:8px; font-family:'Helvetica';"></th>
                    <th style="border-collapse:collapse; border:1px solid #DDD; border-bottom-width:2px; padding:8px; font-family:'Helvetica';"></th>
                </tr>
            </thead>
            <tbody>
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">Your Login Page:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        <a href="{{ url('/') }}" style="color:#000;"><em>{{ url('/') }}</em></a>
                    </td>
                </tr>
                <tr>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">Your Login is:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        <em>{{ !empty($username) ? $username : $email }}</em>
                    </td>
                </tr>
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">Your Password is:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        <em>{{ $password }}</em>
                    </td>
                </tr>
            </tbody>
        </table>

	@stop