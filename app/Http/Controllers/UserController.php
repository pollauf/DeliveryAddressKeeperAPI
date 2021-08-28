<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
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

    public function list()
    {
        $tenantID = TenantController::getTenantID();

        $userList = User::where('id_tenant', '=', $tenantID)->get();

        return response()->json($userList);
    }

    public function register(Request $request)
    {
        try {
            $tenantID = TenantController::getTenantID();

            $nome = $request->input('nome');
            $login = $request->input('login');
            $senha = md5($request->input('senha'));

            $isDuplicated = User::where('id_tenant', '=', $tenantID)->where('login', '=', $login)->exists();
            if ($isDuplicated) {
                $response = array('ok' => false, 'msg' => 'O usuário já existe!');
            } else {
                $user = new User;
                $user->id_tenant = $tenantID;
                $user->nome = $nome;
                $user->login = $login;
                $user->senha = $senha;
                $user->status = 1;

                $user->save();

                $response = array('ok' => true, 'msg' => 'Usuário registrado com sucesso!');
            }
        } catch (Exception $e) {
            $response = array('ok' => false, 'msg' => 'Não foi possível registrar o usuário!');
        }

        return response()->json($response);
    }

    //
}
