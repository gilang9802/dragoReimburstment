@extends('layouts.app')

@section('content')
<section class="content">
<div class="container-fluid">
    <form action="{{ route('reimburstment.store') }}" method="post" enctype="multipart/form-data">
    <div class="row justify-content-center">

	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
                <h5 class="card-title">{{ $pageTitle }}</h5>
                <div class="header-elements float-right">
                    <a href="{{ route($urlIndex) }}" class=" btn-link" ><i class="fas fa-arrow-left"></i>&nbsp;Kembali</a>
                </div>
			</div>

			<div class="card-body">
				@if (session('status'))
				<div class="alert alert-success" role="alert">
					{{ session('status') }}
				</div>
				@endif

					@csrf

                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Nama</label>
                                <div class="input-group mb-3">
                                    <select name="user_id" class="custom-select" id="user">
                                        <option selected>Pilih...</option>
                                        @foreach( $data as $key => $value )
                                        <option value="{{ $value->id }}" @if(Auth::user()->id == $value->id) selected @endif>{{ $value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($errors->has('user_id'))
                                <span class="text-danger">{{ $errors->first('user_id') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Tipe Pengembalian</label>
                                <div class="input-group mb-3">
                                    <select name="tipe_pengembalian" class="custom-select" id="return_type">
                                        <option value="">Choose...</option>
                                        @foreach( $return_type as $key => $value )
                                        <option value="{{$key}}" >{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($errors->has('tipe_pengembalian'))
                                <span class="text-danger">{{ $errors->first('tipe_pengembalian') }}</span>
                                @endif
                            </div>

                            <div class="form-group" id="no_rek">
                            </div>

                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" class="form-control" name="tanggal" class="form-control">
                                @if($errors->has('tanggal'))
                                <span class="text-danger">{{ $errors->first('tanggal') }}</span>
                                @endif
                            </div>

                            <div class="form-group" id="bank">
                            </div>

                            <div class="form-group">
                              <label>Total</label>
                              <input type="number" name="total" class="form-control" id="total" readonly>
                            </div>
                        </div>
                    </div>

			</div>
        </div>
	</div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                Detail Reimburstment
                <div class="input-group input-group-sm float-right" style="width: 150px;">
                    <div class="input-group-btn pul">
                      <button type="submit" class="btn btn-default float-right" id="add_detail"><i class="fa fa-plus"></i>&nbsp;Tambah Detail</button>
                    </div>
                  </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Prihal</th>
                            <Th>Total</Th>
                            <th>Bukti</th>
                            <th width="500px">Deskripsi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="append_detail">
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <span class="help-block" style="color: red;"> {!! $errors->first('Detail.*.prihal') !!} </span>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <span class="help-block" style="color: red;"> {!! $errors->first('Detail.*.digunakan') !!} </span>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <span class="help-block" style="color: red;"> {!! $errors->first('Detail.*.foto') !!} </span>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <span class="help-block" style="color: red;"> {!! $errors->first('Detail.*.deskripsi') !!} </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="number" class="form-control" name="sum" id="sum" readonly></td>
                        </tr>
                    </tfoot>
                </table>
                <div class="text-right">
                    <input class="btn btn-dark" type="reset" name="reset" value="reset">
                    <input class="btn btn-primary" type="submit" name="submit" value="Simpan">
                </div>
            </div>

        </div>

    </div>
</form>

</div>
</div>
</section>


<script type="text/javascript">

      $(document).ready(function() {
        var i = 0;

        $('#user').change(function(){
            type = $('#return_type').children("option:selected").val();
            if (type == 'transfer') {
                $('#no_rek').children().remove();
                $('#bank').children().remove();
                getUser();
            }
        });

        $('#return_type').change(function(){
            val = $(this).children("option:selected").val();

            select(val);
        });


        $('#add_detail').click(function(e){
          e.preventDefault();

          $('#append_detail').append(
            '<tr class="row_detail">\
              <td><input type="text" class="form-control text-right title" name="Detail['+i+'][prihal]"></td>\
              <td><input type="number" class="form-control used" name="Detail['+i+'][digunakan]"></td>\
              <td><input type="file" class="form-control image" name="Detail['+i+'][foto]"></td>\
              <td><textarea class="form-control description" name="Detail['+i+'][deskripsi]" rows="1"></textarea></td>\
              <td width="25px"><button class="btn btn-xs btn-danger btn-remove btn-flat"><i class="fa fa-trash" data-toggle="tooltip" title="Delete"></i></button></td>\
            </tr>'
          );
            i++;

            $('#append_detail').on('click', '.btn-remove', function(e){
                e.preventDefault();
                $(this).parent().parent().remove();
                count();
            });

            $( "#append_detail" ).on('change','.description', function() {
                row = $(this);
            });

            $('#append_detail').on('keyup', '.used',function() {
                count();

            });

        });
      });

function count(){
    sum = 0;
    $('.used').each(function(){
        sum = sum+Number($(this).val());
    });

    $('#sum').val(sum);
    $('#total').val(sum);
}

function select(val){
    $('#no_rek').children().remove();
    $('#bank').children().remove();
    // console.log($('#awal').children());

    if(val == 'transfer'){
        getUser();
    }

}

function getUser(){
    user = $('#user').val();
    url = '{{route('reimburstment.get.user')}}';
    dataSend = {
        'id_user' : user
    };

    $.ajax({
        url : url,
        headers : {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method : 'POST',
        dataType : 'JSON',
        data : dataSend,
        success : function(data){
            if(data.no_rek == null){
                $('#no_rek').append('\
                <div class="form-group origin">\
                <label>No rekening</label>\
                <input type="number" class="form-control" name="no_rek" pleaceholder="Masukan no rekening">\
                </div>\
                ');
            }else{
                $('#no_rek').children().remove();
            }

            if(data.bank == null){
                $('#bank').append('\
                <div class="form-group origin">\
                <label>Bank</label>\
                <input type="text" class="form-control" name="bank" pleaceholder="Masukan nama bank">\
                </div>\
                ');
            }else{
                $('#bank').children().remove();
            }

        }
    });
}
</script>
@endsection
