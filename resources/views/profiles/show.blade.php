@extends('layouts.app')

@section('content')
<div class="container">
  <div class="page-header">
      <h1>
         {{ $profileUser->name }}
         <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
      </h1>
  </div>

  @foreach ($threads as $thread)
    <div class="card">
        <div class="card-header">
            <div class="level">
                <span>
                    <a href="#">{{ $thread->creator->name }} </a> posted:
                    {{ $thread->title }}
                </span>

                <span class="flex">
                    {{ $thread->created_at->diffForHumans() }}
                </span>
        </div>
    </div>
        <div class="card-body">
            {{ $thread->body }}
        </div>
</div>
  @endforeach

    {{ $threads->links() }}
</div>
@endsection