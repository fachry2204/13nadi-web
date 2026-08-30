<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\ContentItem;
use App\Models\Setting;
class PublicController extends Controller {
 public function index(string $type){$allowed=['sliders','programs','releases','artists','news','photos','videos','infos'];abort_unless(in_array($type,$allowed,true),404);$contentType=$type==='news'?'news':rtrim($type,'s');$items=ContentItem::where('type',$contentType)->where('is_active',true)->orderBy('sort_order')->get();return response()->json(['data'=>$items]);}
 public function show(string $type,string $slug){abort_unless(in_array($type,['releases','artists'],true),404);$item=ContentItem::where('type',rtrim($type,'s'))->where('slug',$slug)->where('is_active',true)->firstOrFail();return response()->json(['data'=>$item]);}
 public function settings(){return response()->json(['data'=>Setting::pluck('value','key')]);}
 public function home(){return response()->json(['data'=>ContentItem::where('is_active',true)->orderBy('sort_order')->get()->groupBy('type')]);}
}
