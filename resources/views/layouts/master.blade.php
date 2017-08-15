<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" ng-app="System">
<!--<![endif]-->
    <head>
        <title ng-bind=" title + '- {{ env('COMPANY') }}'">@yield('title') - {{ env('COMPANY') }}</title>
        <meta name="viewport" content="width=device-width" />
        <base href="/" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet" type="text/css" />
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.2/jquery.qtip.min.css" rel="stylesheet" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/ng-dialog/0.5.6/css/ngDialog-theme-default.min.css" rel="stylesheet" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/ng-dialog/0.5.6/css/ngDialog.min.css" rel="stylesheet" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.6.1/fullcalendar.min.css" rel="stylesheet" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
        <link href="//vjs.zencdn.net/5.7.1/video-js.css" rel="stylesheet" />
        <link href="{{ elixir('assets/css/app.css') }}" rel="stylesheet" />
    </head>
    <body id="@yield('id','@{{ get().id }}')" class="@yield('class','@{{ get().class }}')" ng-controller="PageController as page">

        @yield('content')
            
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.0-rc.2/angular.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.0-rc.2/angular-sanitize.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.0-rc.2/angular-mocks.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.2.17/angular-ui-router.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.0-rc.2/angular-messages.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-animate.min.js"></script>
        <script src="//cdn.gitcdn.link/cdn/angular/bower-material/v1.0.6/angular-material.js"></script>
        <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="//npmcdn.com/masonry-layout@4.1/dist/masonry.pkgd.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/3.10.1/lodash.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/1.1.2/ui-bootstrap-tpls.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0-beta.2/angular-resource.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.5.0/fullcalendar.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.2/basic/jquery.qtip.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/rangy/1.3.0/rangy-core.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/rangy/1.3.0/rangy-selectionsaverestore.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/textAngular/1.5.0/textAngular-sanitize.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/textAngular/1.5.0/textAngular.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/danialfarid-angular-file-upload/12.0.1/ng-file-upload-shim.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/danialfarid-angular-file-upload/12.0.1/ng-file-upload.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular-inview/1.5.6/angular-inview.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular-moment/1.0.0-beta.4/angular-moment.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.0.1/Chart.min.js"></script>
        <script src="//vjs.zencdn.net/5.7.1/video.js"></script>
        <script src="//maps.googleapis.com/maps/api/js?extension=.js&key=AIzaSyAkAg52jj7zCNS52QzWcWkGizUyFOpP54E"></script>
        <script src="/public/assets/js/lib/mousewheelStopPropagation.min.js"></script>
        <script src="/public/assets/js/lib/phoneFormat.min.js"></script>
        <script src="/public/assets/js/lib/angularjs-dirPagination.min.js"></script>
        <script src="/public/assets/js/lib/angularjs-dropdown-multiselect.min.js"></script>
        <script src="/public/assets/js/lib/jquery.confirm.min.js"></script>
        <script src="/public/assets/js/lib/jquery.inview.min.js"></script>
        <script src="/public/assets/js/lib/jquery.mousewheel.min.js"></script>
        <script src="/public/assets/js/lib/datetimepicker/datetimepicker.min.js"></script>
        <script src="/public/assets/js/lib/datetimepicker/datetimepicker.templates.min.js"></script>
        <script src="{{ elixir('assets/js/general.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/app.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/factories/loader.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/factories/config.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/factories/authorization.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/factories/user.js') }}" type="text/javascript"></script>
        @if(\Auth::check())
        <script src="{{ elixir('assets/js/app/factories/role.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/factories/page.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/factories/store.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/factories/hierarchy.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/directives/page.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/directives/forms.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/directives/calendar.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/directives/qtip.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/filters/text.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/filters/range.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/filters/users.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/interceptors/http.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/interceptors/loader.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/page.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/navigation.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/user-navigation.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/search-form.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/notification.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/dashboard.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/profile.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/search.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/user.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/role.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/store.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/news.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/news-dashboard.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/map.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/calendar.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/event.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/promo.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/document.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/folder.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/report.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/logout.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/buyerslogout.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/payroll.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/position.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/sickday.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/medical.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/doctor.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/tech.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/emailsetup.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/emailrequest.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/dashboard.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/profile.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/search.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/user.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/role.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/store.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/news.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/event.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/errors.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/promo.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/document.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/folder.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/report.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/logout.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/buyerslogout.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/payroll.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/position.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/sickday.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/medical.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/doctor.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/tech.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/emailsetup.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/views/emailrequest.js') }}" type="text/javascript"></script>
        @else
        <script src="{{ elixir('assets/js/app/factories/page.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/directives/forms.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/interceptors/http.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/page.js') }}" type="text/javascript"></script>
        <script src="{{ elixir('assets/js/app/controllers/authorization.js') }}" type="text/javascript"></script>
        @endif
    </body>
</html>