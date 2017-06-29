var elixir = require('laravel-elixir');

//Require Sass Compass Addon
require('laravel-elixir-sass-compass');

//Easy Versioning without the Subfolder
require('elixir-busting');



//Build
elixir(function(mix) {

	//The Assets Directory
	var Assets = 'resources/assets/',

	//The SASS Directory
	SASS = 'resources/assets/sass/';

    //Render
    mix.compass('app.scss', 'public/assets/css', {
    	require: 	['sass-globbing'],
	    sass: 		SASS
    });

    //Copy Fonts
    mix.copy( SASS + 'fonts/', 'public/assets/fonts/' );

    //Copy Images
    mix.copy( Assets + 'images/', 'public/assets/images/' );

    //Copy Javascript
    mix.copy( Assets + 'js/', 'public/assets/js/' );

    //Version Files
    mix.busting([ 
        'public/assets/css/app.css' , 
        'public/assets/js/general.js' ,
        'public/assets/js/app/app.js' ,
        'public/assets/js/app/controllers/page.js',
        'public/assets/js/app/controllers/navigation.js' ,
        'public/assets/js/app/controllers/authorization.js' ,
        'public/assets/js/app/controllers/user-navigation.js' ,
        'public/assets/js/app/controllers/notification.js' ,
        'public/assets/js/app/controllers/search-form.js' ,
        'public/assets/js/app/controllers/dashboard.js' ,
        'public/assets/js/app/controllers/profile.js' ,
        'public/assets/js/app/controllers/search.js' ,
        'public/assets/js/app/controllers/user.js' ,
        'public/assets/js/app/controllers/role.js' ,
        'public/assets/js/app/controllers/store.js' ,
        'public/assets/js/app/controllers/news.js' ,
        'public/assets/js/app/controllers/news-dashboard.js' ,
        'public/assets/js/app/controllers/map.js' ,
        'public/assets/js/app/controllers/calendar.js' ,
        'public/assets/js/app/controllers/event.js' ,
        'public/assets/js/app/controllers/menu.js' ,
        'public/assets/js/app/controllers/bar.js' ,
        'public/assets/js/app/controllers/promo.js' ,
        'public/assets/js/app/controllers/document.js' ,
        'public/assets/js/app/controllers/folder.js' ,
        'public/assets/js/app/controllers/report.js' ,
        'public/assets/js/app/controllers/logout.js' ,
        'public/assets/js/app/controllers/promotion.js' ,
        'public/assets/js/app/controllers/payroll.js' ,
        'public/assets/js/app/controllers/position.js' ,
        'public/assets/js/app/controllers/buyerslogout.js' ,
        'public/assets/js/app/controllers/sickday.js' ,
        'public/assets/js/app/controllers/doctor.js' ,
        'public/assets/js/app/controllers/medical.js' ,
        'public/assets/js/app/controllers/tech.js' ,
        'public/assets/js/app/controllers/emailsetup.js' ,
        'public/assets/js/app/controllers/emailrequest.js' ,
        'public/assets/js/app/views/dashboard.js' ,
        'public/assets/js/app/views/profile.js' ,
        'public/assets/js/app/views/search.js' ,
        'public/assets/js/app/views/user.js' ,
        'public/assets/js/app/views/role.js' ,
        'public/assets/js/app/views/store.js' ,
        'public/assets/js/app/views/news.js' ,
        'public/assets/js/app/views/event.js' ,
        'public/assets/js/app/views/errors.js' ,
        'public/assets/js/app/views/menu.js' ,
        'public/assets/js/app/views/bar.js' ,
        'public/assets/js/app/views/promo.js' ,
        'public/assets/js/app/views/document.js' ,
        'public/assets/js/app/views/folder.js' ,
        'public/assets/js/app/views/report.js' ,
        'public/assets/js/app/views/logout.js' ,
        'public/assets/js/app/views/promotion.js' ,
        'public/assets/js/app/views/payroll.js' ,
        'public/assets/js/app/views/position.js' ,
        'public/assets/js/app/views/buyerslogout.js' ,
        'public/assets/js/app/views/sickday.js' ,
        'public/assets/js/app/views/doctor.js' ,
        'public/assets/js/app/views/medical.js' ,
        'public/assets/js/app/views/tech.js' ,
        'public/assets/js/app/views/emailsetup.js' ,
        'public/assets/js/app/views/emailrequest.js' ,
        'public/assets/js/app/factories/config.js' ,
        'public/assets/js/app/factories/authorization.js' ,
        'public/assets/js/app/factories/user.js' ,
        'public/assets/js/app/factories/loader.js' ,
        'public/assets/js/app/factories/page.js' ,
        'public/assets/js/app/factories/role.js' ,
        'public/assets/js/app/factories/store.js' ,
        'public/assets/js/app/factories/hierarchy.js' ,
        'public/assets/js/app/directives/page.js' ,
        'public/assets/js/app/directives/forms.js' ,
        'public/assets/js/app/directives/calendar.js' ,
        'public/assets/js/app/directives/qtip.js' ,
        'public/assets/js/app/filters/text.js' ,
        'public/assets/js/app/filters/range.js' ,
        'public/assets/js/app/filters/users.js' ,
        'public/assets/js/app/interceptors/http.js' ,
        'public/assets/js/app/interceptors/loader.js'
    ]);

});
