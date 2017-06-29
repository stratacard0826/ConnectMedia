<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface HasStoreRelationship {

    /**
     * User belongs to many stores.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stores();


    /**
     * Get all stores as collection.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStores();



    /**
     * Check if the user is involved in at least one store
     *
     * @param int|string|array $store
     * @return bool
     */
    public function inStores($stores);


    /**
     * Attach store to a user.
     *
     * @param int|\App\Models\Store $role
     * @return null|bool
     */
    public function attachStore($role);


    /**
     * Detach store from a user.
     *
     * @param int|\App\Models\Store $role
     * @return int
     */
    public function detachStore($role);


    /**
     * Detach all stores from a user.
     *
     * @return int
     */
    public function detachAllStores();



    /**
     * Check if the user is associated to a Store
     *
     * @param int|string $role
     * @return bool
     */
    public function inStore($store);

}
