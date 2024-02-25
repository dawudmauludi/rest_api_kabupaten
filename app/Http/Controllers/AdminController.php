<?php

namespace App\Http\Controllers;

use App\Models\absensi;
use App\Models\admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

    protected $admin, $user, $absen;
    public function __construct(admin $admin, User $user, absensi $absen)
    {
        $this->admin = $admin;
        $this->user = $user;
        $this->absen = $absen;
    }



    public function login(Request $request){
        $validation = Validator::make($request->all(), [
            'username' => 'required|min:4',
            'password' => 'required|min:5'
        ],["required" => ":attribute tidak boleh kosong", "min" => "Panjang min. :min karakter"]);

        if(!empty($validation->messages()->toArray())) return response()->json(['message'=> 'field login tidak valid', 'galat'=>array_map(fn($value) => $value[0], $validation->errors()->toArray())]);

        $admin = admin::where('username', $request->username)->where('password', $request->password)->first();
        $token = md5($request->username);
        $admin->update(['login_tokens' => $token]);

        if(!$admin) return response()->json(['invalid username or password']);
        return response()->json(['token' => $token]);


    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      
        
        $data = User::with('absen')->get();
        // return response()->json(['data', $data]);

        // if(!$data) return response()->json(['users not found']);

    return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $token = $request->query('login_tokens');
        $token = admin::where('login_tokens', $token);
        $validation = Validator::make($request->all(), [
            'username' => 'required|min:4',
            'password' => 'required|min:5'
        ]);
        if(empty($validation)) return response()->json(['nama dan password tidak boleh kosong']);

        $user = User::create([
            'username' => $request->input('username'),
            'password' => $request->input('password')
    ]);

    if(!$user) return response()->json(['users not found']);
        return response()->json($user);

    }

    /**
     * Display the specified resource.
     */
    public function show( $id,Request $request, User $user)
    {
        // $user = $request->findOrFail($id);
        // $user = $this->user->findOrFail($id)->get();
        // $user = User::findOrFail($id);
        // return response()->json(['berhasil', $user]);
            $user = User::with(['absen'])->findOrFail($id);
            return response()->json(['berhasil', $user]);
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $token = $request->query('login_tokens');
        $token = admin::where('login_tokens', $token);
     

     
        
        $user = User::findOrFail($id);
        $update = collect($request->only($this->user->getFillable()))->toArray();
       $user->update($update);
        if(!$user) return response()->json(['message'=>'user tidak ada']);

        return response()->json(['pengguna berhasil di perbarui'=>$user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id,admin $admin)
    {
        $destroy = $this->user->FindOrFail($id)->delete();
        return response()->json(['succefuly'=>'data berhasil di hapus']);
    }
}
