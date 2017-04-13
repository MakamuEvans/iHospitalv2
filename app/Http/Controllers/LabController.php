<?php

namespace App\Http\Controllers;

use App\LabData;
use Illuminate\Http\Request;
use App\Ticket;
use App\Progress;
use App\Clients;
use App\Test;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class LabController extends Controller
{
    /**
     * retirieve all data related to a given ticket, -->for lab view
     *
     * @param $ticket_id
     * @return mixed
     */
    public function openTicket($ticket_id){
        //dd('elm');
        $ticket = Ticket::findorFail($ticket_id);
        $ticket['progress'] = Progress::where('ticket_id', $ticket_id)->get();
        $ticket['client'] = Clients::findorFail($ticket->client_id);
        $ticket['lab_data'] = LabData::where('ticket_id', $ticket_id)->first();
        $ticket['tests'] = Test::where('lab_id', $ticket['lab_data']->id)->get();

        return Response::json($ticket);
    }

    public function updateTest(Request $request){
        $data = array();
        foreach ($request->all() as $item){
            /*unset($item['created_at']);
            unset($item['updated_at']);*/
            DB::table('tests')
                ->where('id', $item['id'])
                ->update(['result'=>$item['result']]);
            /*$test = new Test($item);
            $test->save();
            array_push($data, $test);*/
        }
        return Response::json($data);
    }
}
