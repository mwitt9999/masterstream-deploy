<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SitesBuilds extends Model
{
    protected $fillable = ['build_id', 'site_id'];

    public function saveSitesBuilds($siteId, $buildId) {
        $sitesBuilds = new SitesBuilds;

        $sitesBuilds->build_id = $buildId;
        $sitesBuilds->site_id = $siteId;

        $sitesBuilds->save();
    }
}
