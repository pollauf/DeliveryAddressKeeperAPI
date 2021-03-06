<?php

namespace App\Http\Controllers;

use App\Models\DeliveryCustomer;
use Exception;
use Illuminate\Http\Request;

class DeliveryCustomerController extends Controller
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

        $customerList = DeliveryCustomer::where('id_tenant', '=', $tenantID)
            ->where('status', '=', 1)
            ->get();

        return response()->json($customerList);
    }

    public function getById($id)
    {
        $tenantID = TenantController::getTenantID();

        $customer =
            DeliveryCustomer::where('id_tenant', '=', $tenantID)
            ->where('id', '=', $id)
            ->get();

        if (count($customer) > 0)
            $customer = $customer[0];

        return response()->json($user);
    }

    public function getByPhone(Request $request)
    {
        $tenantID = TenantController::getTenantID();

        $phone = $request->input('phone');

        $user =
            DeliveryCustomer::where('id_tenant', '=', $tenantID)
            ->where('celular', '=', $phone)
            ->where('status', '=', 1)
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

            if ($request->input('celular') == null) {
                $response = array('ok' => false, 'msg' => 'O celular é obrigatório!');
            } else {
                DeliveryCustomer::where('id_tenant', '=', $tenantID)
                    ->where('celular', '=', $request->input('celular'))
                    ->update(['status' => 0]);

                if ($editMode) {
                    $customer = DeliveryCustomer::where('id', '=', $id)->first();
                } else {
                    $customer = new DeliveryCustomer;
                }

                $customer->id_tenant = $tenantID;
                $customer->nome = $request->input('nome');
                $customer->celular = $request->input('celular');
                $customer->endereco = $request->input('endereco');
                $customer->numero = $request->input('numero');
                $customer->complemento = $request->input('complemento');
                $customer->bairro = $request->input('bairro');
                $customer->cidade = $request->input('cidade');
                $customer->estado = $request->input('estado');
                $customer->origem = $request->input('origem');
                $customer->status = $request->input('status');

                $customer->save();

                if ($editMode) {
                    $response = array('ok' => true, 'msg' => 'Cliente alterado com sucesso!');
                } else {
                    $notifCustomerController = new NotifCustomerController();
                    $notifCustomerController->register($customer->id);

                    $response = array('ok' => true, 'msg' => 'Cliente registrado com sucesso!');
                }
            }
        } catch (Exception $e) {
            $response = array('ok' => false, 'msg' => 'Não foi possível registrar o cliente!');
        }

        return response()->json($response);
    }

    public function setStatus($id, $status)
    {
        try {
            $tenantID = TenantController::getTenantID();

            $sucesso = DeliveryCustomer::where('id_tenant', '=', $tenantID)
                ->where('id', '=', $id)
                ->update(['status' => $status]);

            $response = array('ok' => $sucesso, 'msg' => 'Status alterado com sucesso!');
        } catch (Exception $e) {
            $response = array('ok' => false, 'msg' => 'Não foi possível alterar o status!');
        }

        return response()->json($response);
    }

    //
}
