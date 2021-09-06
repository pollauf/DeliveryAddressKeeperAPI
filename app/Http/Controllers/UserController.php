<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

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

    public function getById($id)
    {
        $tenantID = TenantController::getTenantID();

        $user =
            User::where('id_tenant', '=', $tenantID)
            ->where('id', '=', $id)
            ->get();

        if (count($user) > 0)
            $user = $user[0];

        return response()->json($user);
    }

    public function register(Request $request)
    {
        try {
            $tenantID = TenantController::getTenantID();

            $id = $request->input('id');
            $editMode = $id != null;

            $nome = $request->input('nome');
            $login = $request->input('login');
            $senha = md5($request->input('senha'));

            $token = $tenantID . '_' . $login . '_' . time();
            $token = Crypt::encrypt($token);

            $isDuplicated =
                User::where('id_tenant', '=', $tenantID)->where('login', '=', $login);

            if ($editMode)
                $isDuplicated = $isDuplicated->where('id', '!=', $id);

            $isDuplicated = $isDuplicated->exists();

            if ($isDuplicated) {
                $response = array('ok' => false, 'msg' => 'O login informado já existe!');
            } else {
                if ($editMode) {
                    $user = User::where('id', '=', $id)->first();
                } else {
                    $user = new User;
                    $user->senha = $senha;
                    $user->token = $token;
                    $user->status = 1;
                }

                $user->id_tenant = $tenantID;
                $user->nome = $nome;
                $user->login = $login;

                $user->save();

                $response = array(
                    'ok' => true,
                    'msg' => !$editMode ?
                        'Usuário registrado com sucesso!' : 'Usuário alterado com sucesso!'
                );
            }
        } catch (Exception $e) {
            $response = array('ok' => false, 'msg' => 'Não foi possível registrar o usuário!');
        }

        return response()->json($response);
    }

    public function changePassword(Request $request)
    {
        $userID = Auth::user()->id;
        $newPassword = md5($request->input('newPassword'));

        try {
            $sucesso = User::where('id', '=', $userID)
                ->update(['senha' => $newPassword]);

            $response = array('ok' => $sucesso, 'msg' => 'Senha alterada com sucesso!');
        } catch (Exception $e) {
            $response = array('ok' => false, 'msg' => 'Não foi possível alterar a senha!');
        }

        return response()->json($response);
    }

    public function setStatus($id, $status)
    {
        $tenantID = TenantController::getTenantID();

        try {
            $sucesso = User::where('id', '=', $id)
                ->where('id_tenant', '=', $tenantID)
                ->update(['status' => $status]);

            $response = array('ok' => $sucesso, 'msg' => 'Status alterado com sucesso!');
        } catch (Exception $e) {
            $response = array('ok' => false, 'msg' => 'Não foi possível alterar o status!');
        }

        return response()->json($response);
    }

    //
}
