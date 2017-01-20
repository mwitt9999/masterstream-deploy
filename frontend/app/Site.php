<?php

namespace App;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;


class Site extends \Eloquent
{
    protected $fillable = ['created_at', 'updated_at', 'name', 'github_account_name', 'github_repository_name'];

    public function __construct()
    {
    }

    public function deleteSite($siteId) {
        self::destroy($siteId);
    }

    public function editSite(Request $request) {
        $data = [
            'name' => $request->input('name'),
            'github_account_name' => $request->input('github_account_name'),
            'github_repository_name' => $request->input('github_repository_name')
        ];

        self::find($request->input('id'))->update($data);
    }

    public function saveSite(Request $request) {
        $site = new Site;
        $site->name = $request->input('name');
        $site->github_account_name = $request->input('github_account_name');
        $site->github_repository_name = $request->input('github_repository_name');
        $site->save();
    }

    public function getAllSites() {
        return self::all();
    }

    public function getSiteById($id) {
        return self::find($id);
    }

    public function getSiteDataTable() {
        $sites = self::all();
        return Datatables::of($sites)->make(true);
    }

}
