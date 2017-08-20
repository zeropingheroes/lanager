<h2>API Key</h2>
<p>
    Please keep your API key secret, or others will be able to impersonate you on the system.
</p>
<a class="btn btn-success" data-toggle="collapse" href="#apikey" aria-expanded="false" aria-controls="apikey">
    Show API key
</a>
<div class="collapse" id="apikey">
    <div class="well well-sm">
        {{{ $user->api_key }}}
    </div>
</div>
<h2>Authentication</h2>
<ul>
    <li>Your API key will be required to authenticate you when interacting the API</li>
    <li>Depending on your assigned role(s), some resources will be read-only.</li>
</ul>
<p>
    You must set it in the HTTP Authorization header:
</p>
<p>
    <code>Authorization: Lanager abcdabcdabcdabcdabcdabcdabcdabcd</code>
</p>
<h2>Available Endpoints</h2>
<ul>
    <li>A list of Available endpoints is accessible at {{ link_to('/api/') }}</li>
    <li>In the below human-readable list, the notation {items} represents the numeric identifier of a resource</li>
    <li>See {{ link_to('http://www.restapitutorial.com/lessons/httpmethods.html') }} for background information on REST</li>
</ul>
<table class="table">
    <thead>
    <tr>
        <th>HTTP Verb</th>
        <th>URL</th>
    </tr>
    </thead>
    <tbody>
    @foreach( Route::getApiGroups()->getByVersion('v1') as $route )
        <tr>
            <td>{{ implode( $route->getMethods(), ', ' ) }}</td>
            <td>{{ link_to( $route->prefix(url())->uri() ) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>