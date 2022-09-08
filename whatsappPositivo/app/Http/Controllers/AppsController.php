<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Akaunting\Module\Facade as Module;
use ZipArchive;

class AppsController extends Controller
{
    public function index(){
        //1. Get all available apps
        $appsLink="https://raw.githubusercontent.com/mobidonia/foodtigerapps/main/apps24.json";
        $response = (new \GuzzleHttp\Client())->get($appsLink);

        $rawApps=[];
        if ($response->getStatusCode() == 200) {
            $rawApps = json_decode($response->getBody());
        }

        //2. Merge info
        foreach ($rawApps as $key => &$app) {
            $app->installed=Module::has($app->alias);
            if($app->installed){
                $app->version=Module::get($app->alias)->get('version');
                if($app->version==""){
                    $app->version="1.0";
                }

                //Check if app needs update
                if($app->latestVersion){
                    $app->updateAvailable=$app->latestVersion!=$app->version."";
                }else{
                    $app->updateAvailable=false;
                }
                
            }
        }

        //Filter apps by type
        $apps=[];
        foreach ($rawApps as $key => $app) {
            if(isset($app->rule)&&$app->rule){
                if(config('app.'.$app->rule)){
                    array_push($apps,$app);
                }
            }else{
               array_push($apps,$app);
            }
        }
        

        //3. Return view
        return view('apps.index',compact('apps'));

    }

    public function store(Request $request){
       
        $path=$request->appupload->storeAs('appupload', $request->appupload->getClientOriginalName());
        
        $fullPath = storage_path('app/'.$path);
        $zip = new ZipArchive;

        if ($zip->open($fullPath)) {

            //Modules folder
            $destination=public_path('../modules');
            // Extract file
            $zip->extractTo($destination);
            
            // Close ZipArchive     
            $zip->close();
            return redirect()->route('apps.index')->withStatus(__('App is installed'));
        }else{
            return redirect(route('apps.index'))->withError(__('There was an error on app install. Please try manual install'));
        }
    }
}
