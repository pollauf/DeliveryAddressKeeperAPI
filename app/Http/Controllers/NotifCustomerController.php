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

    public function list($status = 2)
    {
        $tenantID = TenantController::getTenantID();

        $notificationList =
            NotificationCustomer::where('notif_cad_cli_delivery.id_tenant', '=', $tenantID)
            ->select(
                'notif_cad_cli_delivery.*',
                'clientes_delivery.nome',
                'clientes_delivery.celular',
            )
            ->join('clientes_delivery', 'clientes_delivery.id', '=', 'notif_cad_cli_delivery.id_cliente_delivery');

        if ($status != 2) {
            $notificationList = $notificationList->where('notif_cad_cli_delivery.status', '=', $status);
        }

        $notificationList = $notificationList->orderBy('id', 'desc')->get();

        return response()->json($notificationList);
    }

    public function setAsViewed($notificationID)
    {
        $tenantID = TenantController::getTenantID();

        try {
            $sucesso = NotificationCustomer::where('id', '=', $notificationID)
                ->where('id_tenant', '=', $tenantID)
                ->update(['status' => 1]);

            $response = array('ok' => $sucesso, 'msg' => 'Notificação visualizada com sucesso!');
        } catch (Exception $e) {
            $response = array('ok' => false, 'msg' => 'Não foi possível visualizar a notificação!');
        }

        return response()->json($response);
    }

    public function clearAll()
    {
        $tenantID = TenantController::getTenantID();

        try {
            $sucesso = NotificationCustomer::where('id_tenant', '=', $tenantID)
                ->update(['status' => 1]);

            $response = array('ok' => $sucesso, 'msg' => 'Notificações limpas com sucesso!');
        } catch (Exception $e) {
            $response = array('ok' => false, 'msg' => 'Não foi possível limpar as notificações!');
        }

        return response()->json($response);
    }

    public function register($customerID)
    {
        try {
            $tenantID = TenantController::getTenantID();

            $customer = new NotificationCustomer;
            $customer->id_tenant = $tenantID;
            $customer->id_cliente_delivery = $customerID;
            $customer->status = 0;

            $customer->save();

            $response = array('ok' => true, 'msg' => 'Notificação registrada com sucesso!');
        } catch (Exception $e) {
            $response = array('ok' => false, 'msg' => 'Não foi possível registrar a notificação!');
        }

        return response()->json($response);
    }

    //
}
