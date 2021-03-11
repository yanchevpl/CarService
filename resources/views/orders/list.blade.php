@extends('layouts.app')

@section('content')
@php($total = new \App\RepairManager())
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Списък заявки') }}</div>

                <div class="card-body">
                   <table width="100%" border="0">
                       <tr>
                           <td>ID</td>
                           <td>Клиент</td>
                           <td>Автомобил</td>
                           <td>Дата ремонт</td>
                           <td>Стойност</td>
                           <td>Статус</td>
                           <td align="center">Действие</td>
                       </tr>
                       @isset($orders)
                           @foreach($orders as $order)
                               <tr>
                                   <td>{{$order['orderid']}}</td>
                                   <td>{{$order['name']}}</td>
                                   <td>{{$order['make']."/".$order['model']."/".$order['make_year']}}</td>
                                   <td>{{$order['schedule']}}</td>
                                   <td>{{ array_sum($total->setService($order['orderid'])->Account()) }}</td>
                                   <td>{{$order['title']}}</td>
                                   <td align="center"><a href="{{route('order.edit', $order['orderid']) }}"><i class="fa fa-calendar-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
                                       <a href="{{route('repair.index')."?order=".$order['orderid'] }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
                               </tr>
                           @endforeach
                       @endisset
                   </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">

    $(function() {
        var disabledDates = ["2021-03-09"];
        $("#datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            beforeShowDay: function(date){
                var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                return [ disabledDates.indexOf(string) == -1 ]
            }
        });
    });

        function setUserID(user_id) {
            $("#user").val(user_id);
        }

        function create() {
            axios.get('{{ route('order.create') }}',{
                params: {
                    car_id: $("#car").val(),
                    datepicker: $("#datepicker").val(),
                    reactiontime: $("#reactiontime").val(),
                    description: $("#description").val(),
                    status: $("#status").val()
                }
            }).then(response => {
                alert(response.data['message']);
        });
        }
</script>
@endsection
