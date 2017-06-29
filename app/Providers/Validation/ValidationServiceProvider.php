<?php

namespace App\Providers\Validation;

use Validator;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

         $this->app->validator->resolver(function ($translator, $data, $rules, $messages) {

            // We create our own validation class here, we will create that after this
            return new ExtendedValidation($translator, $data, $rules, $messages);
        
        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
