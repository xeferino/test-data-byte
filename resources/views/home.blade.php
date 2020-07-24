@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{  ($user->role=="admin") ? 'List Users' : 'Completed Information Profile' }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(session()->has('message'))
                        <div class="alert alert-dismissible fade show alert-{{ (session()->get('label')=='danger') ? session()->get('label') : session()->get('label') }}" role="alert">
                            @if(session()->get('label')=="success")
                                {{ session()->get('message') }}
                            @else
                                {{ session()->get('message') }}
                            @endif
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                  @endif

                    @if ($user->role=="admin")
                    <div class="table-responsive-xl">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Surname</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Description</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    @else
                        <form method="POST" action="{{ route('profile', ['user' => $user->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <img title="Image Profile" src="{{ asset('upload/profile/'.$user->img) }}" class="image-reponsive img-thumbnail rounded-circle rounded mx-auto d-block mb-2" width="100px" height="100" alt="customer image">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @if($errors->first('name')) is-invalid @endif" name="name" value="{{ $user->name }}" required>
                                    @if($errors->first('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('Surname') }}</label>
                                <div class="col-md-6">
                                    <input id="surname" type="text" class="form-control @if($errors->first('surname')) is-invalid @endif" name="surname" value="{{ $user->surname }}" required>
                                    @if($errors->first('surname'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('surname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control  @if($errors->first('email')) is-invalid @endif" name="email" value="{{ $user->email }}" required>
                                    @if($errors->first('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>

                                <div class="col-md-6">
                                    <input id="phone" text="phone" class="form-control @if($errors->first('phone')) is-invalid @endif" name="phone" value="{{ $user->phone }}">
                                    @if($errors->first('phone'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>
                                <div class="col-md-6">
                                    <textarea name="description" id="description" class="form-control @if($errors->first('description')) is-invalid @endif" name="description" cols="30" rows="10">{{ $user->description }}</textarea>
                                    @if($errors->first('description'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="input-group mb-3">

                                </div>
                            <div class="form-group row">
                                <label for="img" class="col-md-4 col-form-label text-md-right">{{ __('Image') }}</label>

                                <div class="col-md-6">
                                    <input type="file" name="file" class="form-control-file @if($errors->first('file')) is-invalid @endif">
                                    @if($errors->first('file'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('file') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-secondary">
                                        {{ __('Completed Profile') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script-content')
<script type="text/javascript">
    $(function () {
      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('users.index') }}",
          columns: [
              {data: 'id', name: 'id'},
              {data: 'name', name: 'name'},
              {data: 'surname', name: 'surname'},
              {data: 'email', name: 'email'},
              {data: 'phone', name: 'phone'},
              {data: 'description', name: 'description'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });


      $('body').on('click', '.deleteUser', function () {
            var user_id = $(this).data("id");

            if(confirm("Are You sure want to delete !"))
            {
                $.ajax({
                    type: "GET",
                    url: "{{ url('user/delete') }}"+'/'+user_id,
                    success: function (data) {
                    var oTable = $('.data-table').dataTable();
                    oTable.fnDraw(false);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    });
  </script>
@endsection
