@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                @if($errors->any())
                    <h4>{{$errors->first()}}</h4>
                @endif

                <div class="card-header">{{ __('Ремонт') }} @isset($order) {{ $order->id  }} @endisset</div>
                <input type="hidden" name="order" id="order" value="@isset($order) {{$order->id}} @endisset">

                <div class="card-body">
                   <table width="100%" border="0" id="ct_blah">
                       <tr>
                           <td align="center">Дейност</td>
                           <td align="center">Консуматив/Част</td>
                           <td align="center">Част Цена</td>
                           <td align="center">Труд Цена</td>
                           <td align="center">Коментар</td>
                           <td align="center">Действие</td>
                       </tr>
                       @isset($repairs)
                           @foreach($repairs as $repair)
                               <tr id="row{{$repair['id']}}">
                                   <td><input type="text" id="operation{{$repair['id']}}" class="form-control" value="{{ $repair['operation'] }}"></td>
                                   <td><input type="text" id="part{{$repair['id']}}" class="form-control" value="{{ $repair['part_name'] }}"></td>
                                   <td><input type="text" id="partprice{{$repair['id']}}" class="form-control" value="{{ $repair['price'] }}"></td>
                                   <td><input type="text" id="labor{{$repair['id']}}" class="form-control" value="{{ $repair['labor'] }}"></td>
                                   <td><input type="text" id="comment{{$repair['id']}}" class="form-control" value="{{ $repair['comment'] }}"></td>
                                   <td align="center"><i class="fa fa-trash" aria-hidden="true" onclick="dbDelete({{$repair['id']}})"></i>&nbsp;<i onclick="dbUpdate({{$repair['id']}})" class="fa fa-floppy-o" aria-hidden="true"></i></td>
                               </tr>
                           @endforeach
                       @endisset
                       <tr id="begin">
                           <td><input type="text" class="form-control" name="operation" id="operation"></td>
                           <td><input type="text" class="form-control" name="part" id="part"></td>
                           <td><input type="text" class="form-control" name="partprice" id="partprice"></td>
                           <td><input type="text" class="form-control" name="labor" id="labor"></td>
                           <td><input type="text" class="form-control" name="comment" id="comment"></td>
                           <td align="center"><i class="fa fa-trash" aria-hidden="true" onclick="delpos()"></td>
                       </tr>
                   </table>
                    <table id="final">
                        <tr>
                            <td><button type="button" class="btn btn-primary" onclick="addpos()"><i class="fa fa-tags" aria-hidden="true"></i>Добави позиция</button></td>
                        </tr>
                        <tr><td>
                                  <textarea id="client_comment" name="client_comment" rows="4" cols="50">
                                      @isset($order) {{$order->note}} @endisset
                                   </textarea>
                            </td></tr>
                        <tr>
                            <table>
                                <tr>
                                    <td>Статус:</td>
                                    <td>
                                        <select name="status" id="status">
                                            @isset($status)
                                             @foreach($status as $s)
                                                <option value="{{$s['id']}}" @if( $s['id'] == $order->status) selected @endif>{{$s['title']}}</option>
                                              @endforeach
                                            @endisset
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left">Части:</td>
                                    <td><input type="number" id="parts_total" value="{{ $totals['parts_total'] }}" disabled></td>
                                </tr>
                                <tr>
                                    <td align="left">Труд:</td>
                                    <td><input type="number" id="labor_total" value="{{ $totals['labor_total'] }}" disabled></td>
                                </tr>
                                <tr>
                                    <td align="left">Общо:</td>
                                    <td><input type="number" id="sum_total"  value="{{ array_sum($totals) }}"  disabled></td>
                                </tr>
                            </table>
                        </tr>
                        <tr><td>
                                <button type="button" class="btn btn-danger" onclick="create()"  @if($order->status == 3) disabled @endif>Запис</button></td>
                            </td></tr>
                    </table>


                </div>
            </div>
        </div>
    </div>
</div>
<script type="application/javascript ">

    var seq = 1;
    function addpos() {
        var $lastRow = $("[id$=blah] tr:not('.ui-widget-header'):last"); //grab row before the last row
        var $newRow = $lastRow.clone(); //clone it
        $newRow.find(":text").val(""); //clear out textbox values
        $lastRow.after($newRow); //add in the new row at the end
        seq = seq + 1;
    }

    function delpos() {
       if(seq > 1)
       {
           $('#ct_blah tr:last').remove();
           seq = seq - 1;
       }
    }

    var oper;
    var part;
    var partprice;
    var labor;
    var comment;

     function populate() {
          oper = [];
          part = [];
          partprice = [];
          labor = [];
          comment = [];

        $("input[name='operation']").each(function () {
          oper.push(this.value || 0);
        });

         $("input[name='part']").each(function () {
             part.push(this.value || 0);
         });

         $("input[name='partprice']").each(function () {
             partprice.push(this.value || 0);
         });

         $("input[name='labor']").each(function () {
             labor.push(this.value || 0);
         });

         $("input[name='comment']").each(function () {
             comment.push(this.value || '');
         });

    }

        function setUserID(user_id) {
            $("#user").val(user_id);
        }

        function create() {
            populate();
            axios.get('{{ route('repair.create') }}',{
                params: {
                    order: $("#order").val(),
                    operation: oper,
                    part: part,
                    partprice: partprice,
                    labor: labor,
                    comment: comment,
                    client_comment: $("#client_comment").val(),
                    status: $("#status").val()
                }
            }).then(response => {
                alert(response.data['message']);
                $("#parts_total").val(response.data[0]['parts_total']);
                $("#labor_total").val(response.data[0]['labor_total']);
                $("#sum_total").val(response.data[0]['parts_total'] + response.data[0]['labor_total']);
        });
        }

    function dbUpdate(id) {
        axios.put('{{ route('repair.update',0) }}',{
                id: id,
                operation: $("#operation"+id).val(),
                part: $("#part"+id).val(),
                partprice: $("#partprice"+id).val(),
                labor: $("#labor"+id).val(),
                comment: $("#comment"+id).val(),
                client_comment: $("#client_comment").val(),
        }).then(response => {
            $("#parts_total").val(response.data['parts_total']);
            $("#labor_total").val(response.data['labor_total']);
            $("#sum_total").val(response.data['parts_total'] + response.data['labor_total']);

    });
    }

        function dbDelete(id) {
            axios.delete('{{ route('repair.destroy',0) }}',{
            params: {
                id: id,
            }
            }).then(response => {
            $("#parts_total").val(response.data['parts_total']);
            $("#labor_total").val(response.data['labor_total']);
            $("#sum_total").val(response.data['parts_total'] + response.data['labor_total']);
            $("#row"+id).remove();
            //preventDefault();
        });


        }
</script>
@endsection
