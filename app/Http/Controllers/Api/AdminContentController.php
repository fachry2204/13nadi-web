<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ContentItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
class AdminContentController extends Controller {
 private array $types=['slider','program','banner','about','release','artist','news','photo','video','info'];
 public function settings(){return response()->json(['data'=>Setting::orderBy('group')->orderBy('key')->get()]);}
 public function updateSettings(Request $r){$d=$r->validate(['items'=>['required','array'],'items.*'=>['nullable','string','max:2048']]);foreach($d['items'] as $key=>$value)Setting::updateOrCreate(['key'=>$key],['group'=>'identity','value'=>$value]);ActivityLog::create(['user_id'=>$r->user()->id,'action'=>'settings_updated','ip_address'=>$r->ip()]);return $this->settings();}
 public function activity(){return response()->json(ActivityLog::latest()->paginate(50));}
 public function uploadImage(Request $r){$d=$r->validate(['image'=>['required','image','mimes:jpeg,jpg,png,webp','max:8192']]);$file=$d['image'];$name=now()->format('YmdHis').'-'.Str::random(10).'.'.$file->extension();$directory=public_path('uploads');File::ensureDirectoryExists($directory,0755,true);$file->move($directory,$name);ActivityLog::create(['user_id'=>$r->user()->id,'action'=>'image_uploaded','properties'=>['file'=>$name],'ip_address'=>$r->ip()]);return response()->json(['data'=>['url'=>'/uploads/'.$name,'name'=>$file->getClientOriginalName()]],201);}
 public function index(Request $r,string $type){abort_unless(in_array($type,$this->types,true),404);$q=ContentItem::where('type',$type)->orderBy('sort_order');if($r->filled('search'))$q->where('title','like','%'.$r->string('search').'%');if($r->filled('active'))$q->where('is_active',$r->boolean('active'));return response()->json($q->paginate(min($r->integer('per_page',15),100)));}
 public function show(string $type,ContentItem $item){abort_unless(in_array($type,$this->types,true)&&$item->type===$type,404);return response()->json(['data'=>$item]);}
 public function store(Request $r,string $type){return $this->save($r,$type,new ContentItem,201);}
 public function update(Request $r,string $type,ContentItem $item){abort_unless($item->type===$type,404);return $this->save($r,$type,$item);}
 private function save(Request $r,string $type,ContentItem $item,int $status=200){abort_unless(in_array($type,$this->types,true),404);$d=$r->validate(['title'=>['required','string','max:160'],'slug'=>['nullable','string','max:180',Rule::unique('content_items')->where(fn($q)=>$q->where('type',$type))->ignore($item->id)],'subtitle'=>['nullable','string','max:255'],'description'=>['nullable','string'],'image_url'=>['nullable','string','max:2048',function(string $attribute,mixed $value,\Closure $fail){if($value!==null&&!Str::startsWith((string)$value,['https://','http://','/uploads/','/storage/']))$fail('Lokasi gambar tidak valid.');}],'external_url'=>['nullable','url','max:2048'],'metadata'=>['nullable','array'],'sort_order'=>['nullable','integer','min:0'],'is_active'=>['nullable','boolean']]);$item->fill($d+['type'=>$type])->save();ActivityLog::create(['user_id'=>$r->user()->id,'action'=>$item->wasRecentlyCreated?'created':'updated','subject_type'=>'content_item','subject_id'=>$item->id,'properties'=>['title'=>$item->title],'ip_address'=>$r->ip()]);return response()->json(['data'=>$item],$status);}
 public function destroy(Request $r,string $type,ContentItem $item){abort_unless($item->type===$type,404);$title=$item->title;$item->delete();ActivityLog::create(['user_id'=>$r->user()->id,'action'=>'deleted','properties'=>['title'=>$title],'ip_address'=>$r->ip()]);return response()->json([],204);}
 public function reorder(Request $r,string $type){abort_unless(in_array($type,$this->types,true),404);$d=$r->validate(['ids'=>['required','array'],'ids.*'=>['integer','exists:content_items,id']]);foreach($d['ids'] as $order=>$id)ContentItem::where('type',$type)->whereKey($id)->update(['sort_order'=>$order]);return response()->json(['message'=>'Urutan diperbarui.']);}
}
