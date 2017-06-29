<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasStoreRelationship {
    /**
     * Property for caching stores.
     *
     * @var \Illuminate\Database\Eloquent\Collection|null
     */
    protected $stores;

    /**
     * User belongs to many stores.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stores(){

        return $this->belongsToMany('App\Models\Store')->withTimestamps();
    
    }

    /**
     * Get all stores as collection.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStores(){
        return (!$this->stores) ? $this->stores = $this->stores()->get() : $this->stores;
    
    }



    /**
     * Check if the user is involved in at least one store
     *
     * @param int|string|array $store
     * @return bool
     */
    public function inStores($stores){
        foreach ($stores as $store) {
            if ($this->inStore($store)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Attach store to a user.
     *
     * @param int|\App\Models\Store $role
     * @return null|bool
     */
    public function attachStore($role){

        return (!$this->getStores()->contains($role)) ? $this->stores()->attach($role) : true;
    
    }

    /**
     * Detach store from a user.
     *
     * @param int|\App\Models\Store $role
     * @return int
     */
    public function detachStore($role)
    {
        $this->stores = null;

        return $this->stores()->detach($role);
    }

    /**
     * Detach all stores from a user.
     *
     * @return int
     */
    public function detachAllStores()
    {
        $this->stores = null;

        return $this->stores()->detach();
    }


    /**
     * Check if the user is associated to a store.
     *
     * @param int|string $store
     * @return bool
     */
    public function inStore($store){
        return $this->getStores()->contains(function ($key, $value) use ($store) {
            return $store == $value->id || Str::is($store, $value->slug);
        });
    }

}
