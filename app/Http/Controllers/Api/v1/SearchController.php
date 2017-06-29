<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Search;
use DB;
use Auth;

class SearchController extends Controller {






    /**
    *
    *   getRole
    *       - Loads All of the Roles
    *
    *   URL Params:
    *       - limit:     (INT) The Page Limit (Default: 15)
    *       - page:      (INT) Pages to Load (Default: 1)
    *       - search:    (String) The Search Query
    *
    *
    *   Returns (JSON):
    *       1. The Role Data
    *
    **/
    public function getSearch( $limit = 15, $page = 1, $search = null ){

        if( $search ){

            return Search::where(function($query){

                //Search within Roles
                $query->where(function($query){

                    $roles = Auth::user()->roles()->get()->lists('id');

                    $query->whereDoesntHave('roles');

                    if( count( $roles ) > 0 ){

                        $query->orWhereHas('roles',function($query) use ($roles) {

                            $query->whereIn('role_id', $roles );

                        });

                    }


                });


                //Search within Stores
                $query->where(function($query){

                    $stores = Auth::user()->stores()->get()->lists('id');

                    $query->whereDoesntHave('stores');

                    if( count( $stores ) > 0 ){

                        $query->orWhereHas('stores',function($query) use ($stores) {

                            $query->whereIn('store_id' , $stores );

                        });

                    }

                });


                //Search within Permissions
                $query->where(function($query){

                    $permissions = Auth::user()->getPermissions()->lists('id');

                    $query->whereDoesntHave('permissions');

                    if( count( $permissions ) > 0 ){

                        $query->orWhereHas('permissions',function($query) use ($permissions) {

                            $query->whereIn('permission_id' , $permissions );

                        });

                    }

                });


            })->where(function($query) use ($search){

                //Lookup Title
                $query->where('title' , 'like' , '%' . $search . '%' );

                //Lookup Query
                $query->orWhere('query' , 'like' , '%' . $search . '%' );

            })->get()->each(function($item, $key){
                
                //Get the Base Link Type
                $type          = explode( '\\' , $item['link_type'] );
                $item['type']  = $type[ count( $type ) - 1 ];

                //Unset the Link Type
                unset( $item['link_type'] );

            });

        }else{

            return Search::all()->each(function($item, $key){
                
                $type          = explode( '\\' , $item['link_type'] );
                $item['type']  = $type[ count( $type ) - 1 ];

                //Unset the Link Type
                unset( $item['link_type'] );

            });

        }

    }   



    
}
