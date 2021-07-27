@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
    <script>
        window.thread = <?= json_encode($thread); ?>
    </script>
@endsection


@section('content')
<thread-view :thread="{{ $thread }}" inline-template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
         @include ('threads._question')

            <replies @added="repliesCount++" @removed="repliesCount--"></replies>

        </div>
        <div class="col-md-4">
               <div class="card">
                    <div class="card-body">
                        <p>This thread was published {{ $thread->created_at->diffForHumans() }} by
                            <a href="#">{{ $thread->creator->name }}</a>, and currently
                            has <span v-text="repliesCount"></span> {{ Str::plural('comment', $thread->replies_count) }}
                        </p>

                        <p>
                        //    <button type="button" class="btn btn-success" {{ json_encode($thread->isSubscribedTo) }} v-if="signedIn">Subscribe</button>

                            <subscribe-button type="button" :active="{{ json_encode($thread->isSubscribedTo) }}"  v-if="signedIn"></subscribe-button>

                            <button type="button"
                                    class="btn btn-primary"
                                    v-if="authorize('isAdmin')"
                                    @click="toggleLock"
                                    v-text="locked ? 'Unlock' : 'Lock'">Lock</button>
                        </p>
                   </div>
               </div>
          </div>
       </div>
   </div>
</thread-view>
@endsection


