<?php

namespace App;

use App\Repair;

// Управление на ремонтните дейности
class RepairManager extends Repair
{

    public $service_id;
    public $oper;
    public $part;
    public $pri;
    public $lab;
    public $comm;

    public
    function SetService($service_id)
    {
        $this->service_id = $service_id;
        return $this;
    }

    public
    function SetOper($oper)
    {
        $this->oper = $oper;
        return $this;
    }

    public
    function SetPart($part)
    {
        $this->part = $part;
        return $this;
    }

    public
    function SetPrice($pri)
    {
        $this->pri = $pri;
        return $this;
    }

    public
    function SetLab($lab)
    {
        $this->lab = $lab;
        return $this;
    }

    public
    function SetComm($comm)
    {
        $this->comm = $comm;
        return $this;
    }

    // Показва ремонтни действия
    public
    function getActions()
    {
        return self::where('serviceorder_id', $this->service_id)->get();
    }

    // Добавя позиции с ремотни дейности и стойности
    public
    function add()
    {
        for ($i = 0; $i < count($this->oper); $i++) {
            // Филтър за нулеви стойности от полетата
            if($this->oper[$i] && $this->part[$i] && $this->pri[$i] && $this->lab[$i] != 0 || null)
            {
                // Duplicate филтър
                self::firstOrCreate([
                    'serviceorder_id' => $this->service_id,
                    'operation' => $this->oper[$i],
                    'part_name' => $this->part[$i],
                    'price' => $this->pri[$i],
                    'labor' => $this->lab[$i],
                    'comment' => $this->comm[$i]
                ]);
            }

        }
    }

    // Актуализация на ремонтните действия
    public function upToDate()
    {
        self::where('id', $this->service_id)
            ->update([
                'operation' => $this->oper,
                'part_name' => $this->part,
                'price' => $this->pri,
                'labor' => $this->lab,
                'comment' => $this->comm
            ]);
    }

    // Суми и стойности по ремонтите
    public function Account()
    {
        return [
            'parts_total' => self::where('serviceorder_id', $this->service_id)->sum('price'),
            'labor_total' => self::where('serviceorder_id', $this->service_id)->sum('labor')
        ];
    }

    public function OrderByActionPositionId()
    {
        return self::where('id', $this->service_id)
            ->first()->value('serviceorder_id');
    }

    // Изтриване на ремонтни позиции
    public function del()
    {
       return self::where('id', $this->service_id)
            ->delete();
    }


}