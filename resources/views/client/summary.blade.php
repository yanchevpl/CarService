@extends('layouts.app')

@section('content')
@php($repairs = new \App\RepairManager())
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Моят профил') }}</div>

                <div class="card-body">
                  {{-- Основна таблица --}}
                    @isset($cars)
                        @foreach($cars as $car)
                              {{--- Таблица информация за автомобил --}}
                                 <table width="100%" border="0">
                                     <tr>
                                         <td bgcolor="#32cd32" colspan="2" align="center">Сервизна дейност {{$car->schedule}}</td>
                                     </tr>
                                     <tr>
                                         <td>Автомобил:</td>
                                         <td>{{ $car['make']."/".$car['model']."/".$car['make_year'] }}</td>
                                     </tr>
                                     <tr>
                                         <td>Статус:</td>
                                         <td>{{$car['title']}}</td>
                                     </tr>
                                     <tr>
                                         <td>Коментар:</td>
                                         <td>{{$car['note']}}</td>
                                     </tr>
                                     <tr>
                                         <td colspan="2">
                                            <table border="0" width="100%">
                                                <tr>
                                                    <td bgcolor="#adff2f" align="center">Дейност</td>
                                                    <td bgcolor="#adff2f" align="center">Част/Консуматив</td>
                                                    <td bgcolor="#adff2f" align="center">Част/Цена</td>
                                                    <td bgcolor="#adff2f" align="center">Труд Цена</td>
                                                </tr>
                                                @php($service = $repairs->SetService($car['orderid'])->getActions())
                                                @php($subTotalParts = 0)
                                                @php($subTotalLabor = 0)
                                                @php($seq = 1)
                                                @isset($service)
                                                    @foreach($service as $serv)
                                                        <tr>
                                                            <td align="center">{{$seq}}.&nbsp;{{ $serv['operation']}}</td>
                                                            <td align="center">{{$serv['part_name']}}</td>
                                                            <td align="center">{{$serv['price']}}</td>
                                                            <td align="center">{{$serv['labor']}}</td>
                                                        </tr>
                                                        @php($seq = $seq + 1)
                                                    @endforeach
                                                @endisset
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td bgcolor="#adff2f" align="center">{{ $subTotalParts = $subTotalParts + $repairs->Account()['parts_total'] }}</td>
                                                    <td bgcolor="#adff2f" align="center">{{ $subTotalLabor = $subTotalLabor + $repairs->Account()['labor_total'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" align="right"><b>Всичко:{{ array_sum($repairs->Account()) }}</b></td>
                                                </tr>
                                            </table>
                                         </td>
                                     </tr>
                                 </table>
                            @endforeach
                        @endisset

                </div>
            </div>
        </div>
    </div>
</div>
<script type="application/javascript ">

        function setUserID(user_id) {
            $("#user").val(user_id);
        }

        function create() {
            axios.get('{{ route('car.create') }}',{
                params: {
                    user: $("#user").val(),
                    make: $("#make").val(),
                    model: $("#model").val(),
                    make_year: $("#year").val(),
                    comment: $("#comment").val()
                }
            }).then(response => {
                alert(response.data['message']);
        });
        }
</script>
@endsection
