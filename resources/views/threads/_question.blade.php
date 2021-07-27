<div class="card" v-if="editing">
    <div class="card-header">
        <div class="level">

            <input type="text" value="{{ $thread->title }}" class="form-control">


        </div>
    </div>
    <div class="card-body">

        <div class="form-group">
            <textarea class="form-control" rows="10">{{ $thread->body }}</textarea>
        </div>
    </div>

    <div class="card-footer">
        <div class="level">
            <button type="button" class="btn btn-primary level-item" @click="editing = true" v-show="! editing">Edit</button>
            <button type="button" class="btn btn-success level-item" @click="">Update</button>
            <button type="button" class="btn btn-danger level-item" @click="editing = false">Cancel</button>

                @can ('update', $thread)
                <form action="{{ $thread->path() }}" method="POST" class="ml-a">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <button type="submit" class="btn btn-link">Delete Thread</button>
                </form>
                @endcan

        </div>
    </div>
</div>



<div class="card" v-else>
    <div class="card-header">
        <div class="level">
            <img src="https://images.unsplash.com/photo-1543466835-00a7907e9de1?ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8ZG9nfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="{{ $thread->creator->name }}" width="40" height="40" class="mr-3">

            <span class="flex">
                <a href="/profiles/{{ $thread->creator->name }}">{{ $thread->creator->name }} </a> posted:{{ $thread->title }}
            </span>

        </div>
    </div>
    <div class="card-body">
      {{ $thread->body }}
    </div>

    <div class="card-footer">
      <button type="button" class="btn btn-primary" @click="editing = true">Edit</button>
    </div>
</div>
