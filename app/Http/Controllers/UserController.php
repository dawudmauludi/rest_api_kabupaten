<?php

namespace App\Http\Controllers;

use App\Models\absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }



    public function login(Request $request){
        $validation = Validator::make($request->all(), [
            'username' => 'required|min:4',
            'password' => 'required|max:5'
        ],["required" => ":attribute tidak boleh kosong", "min" => "Panjang min. :min karakter"]);

        if(!empty($validation->messages()->toArray())) return response()->json(['message'=> 'field login tidak valid', 'galat'=>array_map(fn($value) => $value[0], $validation->errors()->toArray())]);

        $user = User::where('username', $request->username);
        if(!$user && Hash::check('password', $user->password, $request->password));

        $token = md5($request->username);
        $user->update(['login_tokens'=> $token]);

        if(!$user) return response()->json(["invalid username or password"]);
        return response()->json(['token' =>$token],200);
    }

    public function logout(Request $request){
        $token = $request->query('login_tokens');
        $token = User::where('login_tokens', $token);

        $token->update(['login_tokens' => null]);

        return response()->json(['message' => 'logout berhasil']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store($userId,Request $request)
    {
        $validation = Validator::make($request->all(), [
            'tanggal' => 'required',
            'jam' => [
            'required',
            function ($attribute, $value, $fail) {
                $startTime = strtotime('06:00');
                $endTime = strtotime('07:15');
                $userTime = strtotime($value);

                if (!($userTime >= $startTime && $userTime <= $endTime)) {
                    $fail('Waktu absensi harus antara 06:00 dan 07:15.');
                }
            },
        ],
        ]);

        if(!$validation) return response()->json(['validasi gagal']);
        $user = User::findOrFail($userId);
        $absensi = absensi::create([
            'tanggal' => $request->input('tanggal'),
            'jam' => $request->input('jam'),
            'users_id' => $user->id
        ]);
        if ($validation->fails()) {
            // Handle error, misalnya kirim pesan ke pengguna atau kembali ke form dengan pesan kesalahan
            return response()->json(['error' => $validation->errors()->first()], 400);
        }    
        

        return response()->json(["absensi berhasil pada tanggal"=>$request->tanggal,"jam" => $request->jam]);

        
        
        
    }



    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $token = $request->query('login_tokens');
        $token = User::where('login_tokens', $token);
        $user = User::findOrFail($id);

        $absen = absensi::all();

        return response()->json(['absensi' => $absen]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
