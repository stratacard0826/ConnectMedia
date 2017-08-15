<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>@yield('subject')</title>
    </head>
    <body style="width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; background:#EFEFEF; font-style:'Helvetica'">
        <table cellpadding="0" cellspacing="0" border="0" style="width:100%; height:100%; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; background:#EFEFEF; margin:60px 0;">
            <tr>
                <td style="border-collapse:collapse;">
                    <table cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; width:510px; margin:0 auto; background:#FFF; box-shadow:0 1px 4px rgba(0,0,0,.1); -webkit-box-shadow:0 1px 4px rgba(0,0,0,.1); -moz-box-shadow:0 1px 4px rgba(0,0,0,.2);">
                        <tr>
                            <td style="border-collapse:collapse;">
                                <!--<table cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; width:100%; margin:0 auto; background:#222;">
                                    <tr>
                                        <td style="border-collapse:collapse; padding:20px 0;" align="center">
                                            <h1 style="color:#FFF; font-weight:normal; font-family:'Helvetica';">
                                                <img src="{{ URL::to('/') }}/public/assets/images/logo-no-bg.png" alt="{{ env('COMPANY') }} Logo" />
                                            </h1>
                                        </td>
                                    </tr>
                                </table>-->
                                <table cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; width:100%; margin:0 auto; text-transform: uppercase;">
                                    <tr>
                                        <td style="font-family:'Helvetica'; border-collapse:collapse; background: url(@yield('header-background'));" align="center">
                                            <div style="height: 280px;">
                                                <img style="margin-top: 48px;" src="{{asset('public/assets/images/email/logo.png')}}" alt="">
                                                <br><br>
                                                <h1 style="color:#FFF; font-weight:normal; font-family:'Helvetica';">
                                                    <b style="background-color: @yield('header-title-background'); padding: 0px 5px; text-transform:uppercase;">@yield('header-title')</b>
                                                </h1>
                                                <h4 style="color: #fff;">
                                                    @yield('header-big-title')<br>
                                                    <small>@yield('header-small-title')</small>
                                                </h4>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                
                                <div style="padding:20px">

                                    <!--<h2 style="font-family:'Helvetica'; text-align:center; font-size:30px;">
                                        @yield('type')
                                        <small style="font-size:15px; display:block; padding-top:10px;">
                                            @yield('subject')
                                        </small>
                                    </h2>

                                    <hr style="background:#EFEFEF; border:none; height:1px; margin:20px 0;" />-->

                                    @yield('content')

                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>