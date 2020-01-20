@extends('layouts.app')

@section('title', 'edit article')

@section('content')


	@if ($errors->any())
		@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
		@endforeach
	@endif

	<form action="/article/update/{{ $articles->id }}",  method="post" class="container">
	@csrf
		<span>Title: </span></br></br>

		<input type="text" name="title" value = "{{ $articles->title }}" autocomplete="off"></br></br>

		<span>content: </span></br></br>

		<input type="text" name="content" value = "{{ $articles->content }}" autocomplete="off"></br></br>

		<button type="submit" class="btn btn-md btn-danger">
		<span class="pl-1">送出</span>
		</button>
	</form>
@endsection