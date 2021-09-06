<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function login(Request $request)
    {
        $tenantID = $request->input('tenantID');
        $login = $request->input('login');
        $senha = md5($request->input('senha'));

        $response = array('ok' => false, 'token' => null, 'msg' => '');

        try {
            $user = User::where([
                ['id_tenant', '=', $tenantID],
                ['login', '=', $login],
                ['senha', '=', $senha],
                ['status', '=', 1]
            ])->first();

            if ($user) {
                $response['ok'] = true;
                $response['token'] = $user->token;
                $response['msg'] = 'Login realizado com sucesso!';
            } else {
                $response['msg'] = 'Credenciais incorretas!';
            }
        } catch (Exception $e) {
            $response['msg'] = 'Não foi possível logar!';
        }

        return response()->json($response);
    }

    //
}
