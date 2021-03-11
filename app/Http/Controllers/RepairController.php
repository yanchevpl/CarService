<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Validator;
use App\RepairManager;
use App\Servicestatus;
use App\OrderManager;

class RepairController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order' => ['required', 'exists:serviceorder,id'],
        ], [
            'order.required' => 'Не сте посочили ID на заявката !',
            'order.exists' => 'Несъществуващо ID на заявката !',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors([$validator->errors()->first()]);
        }

        $Repairs = new RepairManager();
        $Order = new OrderManager();
        return view('repair.form', [
            'order' => $Order->SetOid($request->order)->info(),
            'status' => Servicestatus::all(),
            'repairs' => $Repairs->SetService($request->order)->getActions(),
            'totals' => $Repairs->Account()
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order' => ['required', 'exists:serviceorder,id'],
            'operation.*' => ['required', 'string'],
            'part.*' => ['string'],
            'partprice.*' => ['numeric'],
            'labor.*' => ['numeric'],
            'comment' => ['max:1000'],
            'client_comment' => ['max:1000'],
            'status' => 'required|exists:servicestatus,id'

        ], [
            'order.required' => 'Не сте посочили ID на заявката',
            'order.exists' => 'Несъществуваща заявка !',
            'operation.required' => 'Не сте посочили дейност',
            'operation.string' => 'Дейност трябва да е стринг !',
            'part.string' => 'Част трябва да е стринг !',
            'partprice.numeric' => 'Стойността на частта трябва да е в цифри !',
            'labor.numeric' => 'Стойността на труда трябва да е в цифри !',
            'comment.max' => 'Забележката трябва да под 1000 символа !',
            'client_comment.max' => 'Забележката към клиент трябва да под 1000 символа !',
            'status.required' => 'Не сте посочили статус !',
            'status.exists' => 'Невалиден статус !'
        ]);

        if ($validator->fails()) {
            return ['message' => $validator->errors()->first()];
        }

        $Repair = new RepairManager();
        $Order = new OrderManager();

        try {

            DB::beginTransaction();

            $Repair->SetService($request->order)
                ->SetOper($request->operation)
                ->SetPart($request->part)
                ->SetPrice($request->partprice)
                ->SetLab($request->labor)
                ->SetComm($request->comment)
                ->add();

            $Order->setOid($request->order)
                ->SetClientComment($request->client_comment)
                ->ChangeStatus($request->status);

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            return [ 'message' => $e ];
        }

        return ['message' => 'Записано !', $Repair->Account()];
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'exists:repair,id'],
            'operation' => ['required', 'string'],
            'part' => ['string'],
            'partprice' => ['numeric'],
            'labor' => ['numeric'],
            'comment' => ['max:1000'],

        ], [
            'id.required' => 'Не сте посочили ID на заявката',
            'id.exists' => 'Несъществуваща заявка !',
            'operation.required' => 'Не сте посочили дейност',
            'operation.string' => 'Дейност трябва да е стринг !',
            'part.string' => 'Част трябва да е стринг !',
            'partprice.numeric' => 'Стойността на частта трябва да е в цифри !',
            'labor.numeric' => 'Стойността на труда трябва да е в цифри !',
            'comment.max' => 'Забележката трябва да под 1000 символа !'
        ]);

        if ($validator->fails()) {
            return ['message' => $validator->errors()->first()];
        }

        $RepairUpdate = new RepairManager();
        $RepairUpdate
            ->SetService($request->id)
            ->SetOper($request->operation)
            ->SetPart($request->part)
            ->SetPrice($request->partprice)
            ->SetLab($request->labor)
            ->SetComm($request->comment)
            ->upToDate();

        return $RepairUpdate
            ->SetService($RepairUpdate->OrderByActionPositionId())
            ->Account();
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'exists:repair,id'],
        ], [
            'id.required' => 'Не сте посочили ID на заявката',
            'id.exists' => 'Несъществуваща заявка !',
        ]);

        if ($validator->fails()) {
            return ['message' => $validator->errors()->first()];
        }

        $RepairDelete = new RepairManager();
        $RepairDelete->SetService($request->id);
        $order = $RepairDelete->OrderByActionPositionId();
        $RepairDelete->del();
        return $RepairDelete->SetService($order)->Account();

    }
}
