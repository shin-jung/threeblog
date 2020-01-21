@extends('layouts.app')

@section('title', 'home')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if (Auth::user() != NULL)
                    @if ( Auth::user()->admin == 'admin')
                        <span>管理員</span>
                    @else
                        <span>一般用戶</span>
                    @endif
                @else
                    <span>訪客</span>
                @endif

                <div class="card-body">
                    <form action="/article/create" method="get">
                        @csrf
                        <button type="submit">
                        <span>新增文章</span>
                        </button>
                    </form>
                    <HR SIZE=10>
                </div>
                @if (Auth::user() != NULL)
                @if( Auth::user()->admin == 'admin')
                    <form action="/user" method="get">
                    <button type="submit" class="btn btn-md btn-danger">
                    <span class="pl-1">會員列表</span>
                    </button>
                    </form>
                @endif
                @endif
                    @if(isset($articles))
                            @foreach($articles as $article)
                                <span>作者: </span>
                                &emsp;&emsp;{{ $article->author }}
                                <span>標題: </span>
                                <a href="/article/show/{{$article->id}}">
                                &emsp;&emsp;{{ $article->title }}
                                </a>
                                <span>內文: </span>
                                <div>
                                    &emsp;&emsp;{{ $article->content }}
                                </div>
                            @if (Auth::user() != NULL)
                                @if(auth()->user()->admin == 'admin' || auth()->user()->name == $article->author)
                                    <form action="/article/delete/{{ $article->id }}" method="get">
                                    <button type="submit" class="btn btn-md btn-danger">
                                    <span class="pl-1">刪除</span>
                                    </button>
                                    </form>
                                 @endif
                            @endif
                                <HR SIZE=10>
                            @endforeach
                    @endif
            </div>
        </div>
    </div>
</div>
@endsection
