@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Клиенти') }}</div>

                <div class="card-body">
                   <table width="100%" border="0">
                       <tr>
                           <td>Имена</td>
                           <td>E-мейл</td>
                           <td>Бр.Автомобили</td>
                           <td align="center">Действие</td>
                       </tr>

                       @isset($users)
                           @foreach($users as $user)
                               <tr>
                                   <td>{{$user['name']}}</td>
                                   <td>{{$user['email']}}</td>
                                   <td> {{ \App\CarsManager::CarCount($user['id']) }}</td>
                                     <td align="center">
                                        <a href="#" style="text-decoration: none" data-toggle="modal" data-target="#myModal" onclick="setUserID({{$user['id']}})"><i class="fa fa-car" aria-hidden="true"></i>&nbsp;Нов</a>&nbsp;
                                         &nbsp;&nbsp;<a href="{{ route('order.show',$user['id']) }}" style="text-decoration: none"><i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;Заявка</a>
                                     </td>
                               </tr>
                           @endforeach
                       @endisset
                   </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Създаване на нов автомобил</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="make">Марка</label>
                    <input type="text" name="make" id="make" placeholder="Марка">
                </div>
                <div class="form-group">
                    <label for="model">Модел</label>
                    <input type="text" name="model" id="model" placeholder="Модел">
                </div>
                <div class="form-group">
                    <label for="year">Година</label>
                    <input type="text" name="year" id="year" placeholder="Година">
                </div>
                <div class="form-group">
                    <label for="comment"></label>
                    <textarea id="comment" name="comment" rows="4" cols="50" placeholder="коментар"></textarea>
                </div>
                <div class="form-group">
                    <input type="hidden" name="user" id="user">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Затвори</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="create()">Запиши</button>
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
