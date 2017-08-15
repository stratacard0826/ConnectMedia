
        @extends('layouts.master')
        @section('title','404 Page Not Found')
        @section('id','perror')
        @section('content')

            <section class="error font2">
                <h1 class="colorfff">
                    @if($type == '404')
                        Uh Oh!
                    @endif
                </h1>
                <small class="colorfff">
                    @if($type == '404')
                        We can't seem to find the Page you're looking for!
                    @endif
                </small>
                @if(!$status)
                    <a href="{{ url('/auth/login') }}" class="colorfff hovercolorfff">Why not try and log in instead?</a>
                @else
                    <a href="{{ url('/') }}" class="colorfff hovercolorfff">Why not start at the dashboard</a>
                @endif
            </section>

        @stop