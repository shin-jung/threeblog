@extends('layouts.app') 

@section('title', 'show article')

@section('content')
    
    @if (Auth::user() == NULL)
        @if(isset($articles))
            @foreach($articles as $article)
                <span>作者: </span></br></br>
                    &emsp;&emsp;{{ $article->author }}</br></br>
                <span>標題: </span></br></br>
                    &emsp;&emsp;{{ $article->title }}</br></br>
                <span>內文: </span></br></br>
                    &emsp;&emsp;{{ $article->content }}</br></br>
            @endforeach
	    @endif
    @else
        @if(isset($articles))
            @foreach($articles as $article)
                <span>作者: </span></br></br>
                    &emsp;&emsp;{{ $article->author }}</br></br>
                <span>標題: </span></br></br>
                    &emsp;&emsp;{{ $article->title }}</br></br>
                <span>內文: </span></br></br>
                    &emsp;&emsp;{{ $article->content }}</br></br>
            @endforeach
        @endif
        
        @if(auth()->user()->admin == 'admin' || auth()->user()->name == $article->author)
	        <form action="/article/edit/{{$article->id}}" method="get" >
            <button type="submit" class="btn btn-md btn-danger" >
            <span>編輯</span>
            </button>
            </form>
        @endif
    @endif

    <form action="/home" method="get">
    <button type="submit" class="btn btn-md btn-danger">
    <span class="pl-1">返回</span>
    </button>
    </form>
</div> 
@endsection