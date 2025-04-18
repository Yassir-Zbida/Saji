<div class="ticket-responses">
    <h3>Responses</h3>
    
    @if($responses->isEmpty())
        <p>No responses yet.</p>
    @else
        <div class="response-list">
            @foreach($responses as $response)
                <div class="response {{ $response->user_id == auth()->id() ? 'response-mine' : 'response-other' }}">
                    <div class="response-header">
                        <span class="response-author">{{ $response->user->name }}</span>
                        <span class="response-date">{{ $response->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="response-content">
                        {{ $response->message }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>