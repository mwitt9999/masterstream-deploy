<?php

namespace App\Http\Objects;

class Firebase {
    public static function getFirebase() {
        $firebase = new \Firebase();
        return $firebase->fromServiceAccount(base_path().'/google-service-account.json');
    }
}