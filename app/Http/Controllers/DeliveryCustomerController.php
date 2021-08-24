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
        $customerList = DeliveryCustomer::all();
        return response()->json($customerList);
    }

    public function register(Request $request)
    {
        try {
            $tenantID = TenantController::getTenantID();

            if ($request->input('celular') == null) {
                $response = array('ok' => false, 'msg' => 'O celular é obrigatório!');
            } else {
                DeliveryCustomer::where('id_tenant', '=', $tenantID)
                    ->where('celular', '=', $request->input('celular'))
                    ->update(['status' => 0]);

                $customer = new DeliveryCustomer;
                $customer->id_tenant = $tenantID;
                $customer->nome = $request->input('nome');
                $customer->celular = $request->input('celular');
                $customer->endereco = $request->input('endereco');
                $customer->numero = $request->input('numero');
                $customer->complemento = $request->input('complemento');
                $customer->cidade = $request->input('cidade');
                $customer->estado = $request->input('estado');
                $customer->origem = $request->input('origem');
                $customer->status = 1;

                $customer->save();

                $notifCustomerController = new NotifCustomerController();
                $notifCustomerController->register($customer->id);

                $response = array('ok' => true, 'msg' => 'Cliente registrado com sucesso!');
            }
        } catch (Exception $e) {
            $response = array('ok' => false, 'msg' => 'Não foi possível registrar o cliente!');
        }

        return response()->json($response);
    }

    //
}
