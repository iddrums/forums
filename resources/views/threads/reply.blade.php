<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div class="col-md-8">
        <div id="reply-{{ $reply->id }}" class="card">
            <div class="card-header">
                <div class="level">
                    <h5 class="flex">
                    <a href="/profiles/{{ $reply->owner->name }}">
                        {{ $reply->owner->name }}
                    </a> said {{ $reply->created_at->diffForHumans() }}...
                    </h5>
                <div>
                    {{ $reply->favorites()->count() }}
                    <form method="POST" action="/replies/{{ $reply->id }}/favorites">
                        {{ csrf_field() }}
                    <button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                        {{ $reply->favorites_count }} {{ Str::plural('Favorite', $reply->favorites_count) }}
                    </button>
                    </form>
                </div>
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

                <form method="POST" action="/replies/{{ $reply->id }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                </form>
            </div>
            @endcan
        </div>
    </div>
</reply>
