<reply :attributes="{{ $reply }}" inline-template v-cloak>
    {{-- <div class="col-md-8"> --}}
        <div id="reply-{{ $reply->id }}" class="card card-default">
            <div class="card-header">
                <div class="level">
                    <h5 class="flex">
                    <a href="/profiles/{{ $reply->owner->name }}">
                        {{ $reply->owner->name }}
                    </a> said {{ $reply->created_at->diffForHumans() }}...
                    </h5>

                 @if (Auth::check())
                    <div>
                        <favorite :reply="{{ $reply }}"></favorite>
                    </div>
                 @endif
               </div>
            </div>


            <div class="card-body">
                <div v-if="editing">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body"></textarea>
                    </div>

                        <button class="btn btn-xs btn-primary" @click="update">Update</button>
                        <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
                </div>

                <div v-else v-text="body"></div>
            </div>

            @can ('update', $reply)
              <div class="card-footer level">
                <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
              </div>
            @endcan
        </div>
    {{-- </div> --}}
</reply>
