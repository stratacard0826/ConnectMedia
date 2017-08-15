@if ($breadcrumbs)
    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!$breadcrumb->last)
                <li>
                	<i class="{{{ $breadcrumb->icon }}}"></i>
                	<a href="{{{ $breadcrumb->url }}}">{{{ $breadcrumb->title }}}</a>
                </li>
            @else
                <li class="active">
                	<i class="{{{ $breadcrumb->icon }}}"></i>
                	{{{ $breadcrumb->title }}}
                </li>
            @endif
        @endforeach
    </ol>
@endif