<!-- resources/views/emails/logout.blade.php -->
	
    @extends('layouts.email')
    @section('type', 'Upcoming Birthdays')
    @section('subject', 'Upcoming Birthdays - ' . env('COMPANY'))
    
    @section('header-background',  asset('public/assets/images/email/birthday.png'))
    @section('header-title', 'Upcoming Birthdays' )
    @section('header-title-background',  '#e1213d')
    
    @section('content')

        <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; width:100%; border:1px solid #DDD;">
            <tbody>
                @if(count(@$today) > 0)
                <tr>
                    <td colspan="2" style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        <p style="margin:5px 0;"><strong style="font-family:'Helvetica';">Birthday's Today:</strong>
                        <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; width:100%; border:1px solid #DDD;">
                            <thead>
                                <tr style="background-color:#F9F9F9;">
                                    <th align="left" style="border-collapse:collapse; border:1px solid #DDD; border-bottom-width:2px; padding:8px; font-family:'Helvetica';">
                                        <strong style="font-family:'Helvetica';">Name</strong>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(@$today)
                                    @foreach($today as $user)
                                    <tr>
                                        <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                                            {{ $user['firstname'] . ' ' . $user['lastname'] }}
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endif
                @if(count(@$tomorrow) > 0)
                <tr>
                    <td colspan="2" style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        <p style="margin:5px 0;"><strong style="font-family:'Helvetica';">Birthday's Tomorrow:</strong>
                        <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; width:100%; border:1px solid #DDD;">
                            <thead>
                                <tr style="background-color:#F9F9F9;">
                                    <th align="left" style="border-collapse:collapse; border:1px solid #DDD; border-bottom-width:2px; padding:8px; font-family:'Helvetica';">
                                        <strong style="font-family:'Helvetica';">Name</strong>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(@$tomorrow)
                                    @foreach($tomorrow as $user)
                                    <tr>
                                        <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                                            {{ $user['firstname'] . ' ' . $user['lastname'] }}
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endif                
                @if(count(@$week) > 0)
                <tr>
                    <td colspan="2" style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        <p style="margin:5px 0;"><strong style="font-family:'Helvetica';">Upcoming Birthdays:</strong>
                        <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; width:100%; border:1px solid #DDD;">
                            <thead>
                                <tr style="background-color:#F9F9F9;">
                                    <th align="left" style="border-collapse:collapse; border:1px solid #DDD; border-bottom-width:2px; padding:8px; font-family:'Helvetica';">
                                        <strong style="font-family:'Helvetica';">Name</strong>
                                    </th>
                                    <th style="border-collapse:collapse; border:1px solid #DDD; border-bottom-width:2px; padding:8px; font-family:'Helvetica';">
                                        <strong style="font-family:'Helvetica';">Date</strong>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(@$week)
                                    @foreach($week as $user)
                                    <tr>
                                        <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                                            {{ $user['firstname'] . ' ' . $user['lastname'] }}
                                        </td>
                                        <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                                            {{ $user['dob'] }}
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>

	@stop
