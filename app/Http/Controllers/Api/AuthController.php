<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller {
 private function safeUser(User $user):array{return ['id'=>$user->id,'username'=>$user->username,'name'=>$user->name];}
 public function login(Request $request){$d=$request->validate(['username'=>['required','string','max:50'],'password'=>['required','string','max:255']]);$u=User::where('username',$d['username'])->first();if(!$u||!Hash::check($d['password'],$u->password))return response()->json(['message'=>'Username atau password tidak valid.'],422);$u->tokens()->where('created_at','<',now()->subHours(12))->delete();$token=$u->createToken('nadiku-admin')->plainTextToken;ActivityLog::create(['user_id'=>$u->id,'action'=>'login','ip_address'=>$request->ip()]);return response()->json(['data'=>['user'=>$this->safeUser($u),'token'=>$token]]);}
 public function me(Request $r){return response()->json(['data'=>$this->safeUser($r->user())]);}
 public function logout(Request $r){$r->user()->currentAccessToken()?->delete();return response()->json(['message'=>'Berhasil keluar.']);}
}
