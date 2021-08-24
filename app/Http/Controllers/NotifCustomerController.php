<?php

namespace App\Http\Controllers;

use App\Models\NotificationCustomer;
use Exception;
use Illuminate\Http\Request;

class NotifCustomerController extends Controller
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
        $notificationList = NotificationCustomer::all();
        return response()->json($notificationList);
    }

    public function register($customerID)
    {
        try {
            $tenantID = TenantController::getTenantID();

            $customer = new NotificationCustomer;
            $customer->id_tenant = $tenantID;
            $customer->id_cliente_delivery = $customerID;
            $customer->status = 1;

            $customer->save();

            $response = array('ok' => true, 'msg' => 'Notificação registrada com sucesso!');
        } catch (Exception $e) {
            $response = array('ok' => false, 'msg' => 'Não foi possível registrar a notificação!');
        }

        return response()->json($response);
    }

    //
}
