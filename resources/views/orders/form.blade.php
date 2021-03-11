@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">@if(Route::currentRouteName() == 'order.edit') Редакция на заявка @else Нова заявка @endif</div>

                <div class="card-body">
                   <table width="100%" border="0">
                       <tr>
                           <td>Клиент: {{ $user->name }}</td>
                       </tr>
                       <tr>
                           <td>
                               <label for="car">Автомобил:&nbsp;</label>
                               <select name="car" id="car">
                                   @isset($cars)
                                    @foreach($cars as $car)
                                        <option value="{{$car['id']}}" @isset($oparams) @if($car['id'] == $oparams->car_id ) selected @endif @endisset>{{ $car['make']."/".$car['model']."/".$car['make_year'] }}</option>
                                    @endforeach
                                   @endisset
                               </select>
                           </td>
                       </tr>
                       <tr>
                           <td>
                               <label for="datepicker">Планирана дата:</label>
                               <input type="text" id="datepicker" value="@isset($oparams->schedule) {{  $oparams->schedule }} @endisset">
                           </td>
                       </tr>
                       <tr>
                           <td>
                            <label for="reactiontime">Очаквано време:</label>
                            <input type="text" name="reactiontime" id="reactiontime" value="@isset($oparams->note) {{  $oparams->note }} @endisset">
                           </td>
                       </tr>
                       <tr>
                           <td valign="top">
                               <textarea id="description" name="description" rows="4" cols="50" placeholder="описание на проблема" value="@isset($oparams->trouble) {{  $oparams->trouble }} @endisset">
                                   @isset($oparams->trouble) {{  $oparams->trouble }} @endisset
                               </textarea>
                           </td>
                       </tr>
                       <tr>
                           <td>
                               <label for="status">Статус:&nbsp;</label>
                               <select name="status" id="status">
                                   @isset($status)
                                       @foreach($status as $s)
                                           <option value="{{$s['id']}}"  @isset($oparams) @if($s['id'] == $oparams->status ) selected @endif @endisset>{{ $s['title'] }}</option>
                                       @endforeach
                                   @endisset
                               </select>
                           </td>
                       </tr>
                       <tr>
                           <td><button type="button" class="btn btn-danger" @if(Route::currentRouteName() == 'order.edit') onclick="update()" @else onclick="create()" @endif >запис</button></td>
                       </tr>
                   </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">

    $(function() {
        var dateToday = new Date();
        var disabledDates = @json($reserved); //["2021-03-09"];
        //var blqblq =
        $("#datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: dateToday,
            beforeShowDay: function(date){
                var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                return [ disabledDates.indexOf(string) == -1 ]
            }
        });
    });

        function setUserID(user_id) {
            $("#user").val(user_id);
        }
        
        function update() {
            axios.put('@isset($oparams) {{ route('order.update',$oparams->id) }}@endisset',{
                    car_id: $("#car").val(),
                    datepicker: $("#datepicker").val(),
                    reactiontime: $("#reactiontime").val(),
                    description: $("#description").val(),
                    status: $("#status").val()
            }).then(response => {
                alert(response.data['message']);
            });
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
