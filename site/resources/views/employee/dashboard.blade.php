@extends($includes->body)

@section('content')

<h1>Dashboard content</h1>
<h3>Welcome <code>{{ $user['first_name'] . ' ' . $user['last_name']}}</code> <small>{{$user['email']}}</small></h3>
@endsection