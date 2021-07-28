@extends ('layouts.app')

@section('content')
   <div class="container">
       <ais-instant-search>
            <ais-index
                app-id="{{ config('scout.algolia.id') }}"
                app-key="{{ config('scout.algolia.key') }}"
                index-name="threads"

                >

                <ais-search-box></ais-search-box>

                <ais-refinement-list attribute-name="channel.name"></ais-refinement-list>

                <ais-results>
                    <template slot-scope="{ result }">
                        <p>
                            <a :href="result.path">
                            <ais-highlight :result="result" attribute-name="name"></ais-highlight>
                            </a>
                            </p>
                    </template>
                </ais-results>
            </ais-index>
       </ais-instant-search>
    </div>
@endsection
