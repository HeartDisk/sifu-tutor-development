
<?php


use App\Models\Setting;

use Illuminate\Support\Str;
use App\Helper\HelperComponent;





function sideBarMenu(){
    return HelperComponent::sideBar();
}


function setSingleLink($title,$icon,$v_can=null,$v_route=null, $prefix="/portal"){
    return [
        "title"=>$title,
        'type'=> 'single_link',
        "icon"=>$icon,
        "can"=>$v_can,
        // "route"=>$prefix.$v_route,
        "route" => $v_route
    ];
}
function singleImgUpload($request, $path)
{
    if ($request->hasfile('image')) {
        $name=Str::random(20);
        // dd($name);
        $name = $name . "-" . time() . '.' . $request->image->extension();
        $request->image->move(public_path($path), $name);
    } else
        $name = "";
    return $name;
}

function getLayoutColors(){
   $setting= Setting::where(['user_id'=>auth()->user()->id,'active'=>1])->first();
   if(!empty($setting)){

    return $setting;
   }
   else {
    $setting= Setting::where('type','default')->first();
    return $setting;
   }
}

function setSubMenu($title,$icon,$v_can=null,$v_route=null,$prefix="/portal"){
    return [
        "title"=>$title,
        "icon"=>$icon,
        "can"=>$v_can,
        "route"=>$v_route,
    ];
 }




