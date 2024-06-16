<?php

return [
    /**
     * Default is empty.
     *    prefix => ''
     *              <x-button />
     *              <x-card />
     *
     * Renaming all components:
     *    prefix => 'wire-comments-'
     *               <x-wire-comments-button />
     *               <x-wire-comments-card />
     *
     * Make sure to clear view cache after renaming
     *    php artisan view:clear
     */
    'prefix' => '',
];
