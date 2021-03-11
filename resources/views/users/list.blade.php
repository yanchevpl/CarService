@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Списък на регистрираните потребители') }}</div>

                <div class="card-body">
                   <table width="100%" border="0">
                       <tr>
                           <td>Имена</td>
                           <td>E-мейл</td>
                           <td align="center">Достъп</td>
                       </tr>

                       @isset($users)
                           @foreach($users as $user)
                               <tr>
                                   <td>{{$user['name']}}</td>
                                   <td>{{$user['email']}}</td>
                                   @isset($user['get_role'])
                                    @foreach($user['get_role'] as $role)
                                     <td align="center">
                                         <select name="role{{$user['id']}}" id="role{{$user['id']}}" onchange="update({{$user['id']}})">
                                             @isset($roles)
                                                 @foreach($roles as $r)
                                                  <option value="{{$r['id']}}" @if($r['id'] == $role['id']) selected @endif @if($role['id'] == 1) disabled @endif>{{$r['description']}}</option>
                                                 @endforeach
                                             @endisset
                                         </select>
                                     </td>
                                    @endforeach
                                   @endisset
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

    function update(id) {
        var role = $("#role"+id).val();
        axios.put('/users/'+id,{
            id: id,
          role: role
        }).then(response => {
            alert(response.data['message']);
    });
    }

</script>
@endsection
