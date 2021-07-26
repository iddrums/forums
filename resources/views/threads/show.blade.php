@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
    <script>
        window.thread = <?= json_encode($thread); ?>
    </script>
@endsection


@section('content')
<thread-view initial-replies-count="{{ $thread->replies_count }}" inline-template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="level">
                        <img src="https://images.unsplash.com/photo-1543466835-00a7907e9de1?ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8ZG9nfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="{{ $thread->creator->name }}" width="40" height="40" class="mr-3">
                        <span class="flex">
                            <a href="/profiles/{{ $thread->creator->name }}">{{ $thread->creator->name }} </a> posted:

                            {{ $thread->title }}
                        </span>

                        @can ('update', $thread)
                        <form action="{{ $thread->path() }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button type="submit" class="btn btn-link">Delete Thread</button>
                        </form>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                  {{ $thread->body }}
                </div>
            </div>

            <replies @added="repliesCount++" @removed="repliesCount--"></replies>

        </div>
        <div class="col-md-4">
               <div class="card">
                    <div class="card-body">
                        <p>This thread was published {{ $thread->created_at->diffForHumans() }} by
                            <a href="#">{{ $thread->creator->name }}</a>, and currently
                            has <span v-text="repliesCount"></span> {{ Str::plural('comment', $thread->replies_count) }}.
                        </p>

                        <p>
                            <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
                            {{-- <button class="btn btn-primary">
                                Subscribe
                            </button> --}}
                        </p>
                   </div>
               </div>
          </div>
       </div>
   </div>
</thread-view>
@endsection


