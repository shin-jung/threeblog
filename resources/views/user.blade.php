@extends('layouts.app') 

@section('content')
<div class="container">
        @if(isset($user))
            @foreach($user as $user)
                @if( $user->admin == 'user')
                	<span>一般會員: </span></br></br>
                    	&emsp;&emsp;{{ $user->name }}</br></br>
                	<span>email: </span></br></br>
                    	&emsp;&emsp;{{ $user->email }}</br></br>
                @else
                	<span>管理員: </span></br></br>
                    	&emsp;&emsp;{{ $user->name }}</br></br>
                	<span>email: </span></br></br>
                    	&emsp;&emsp;{{ $user->email }}</br></br>
				@endif
            @endforeach
	    @endif
</div>
@endsection