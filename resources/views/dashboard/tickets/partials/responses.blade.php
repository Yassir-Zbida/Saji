<div class="ticket-responses">
    @forelse($responses as $response)
        <div class="ticket-response {{ $response->user->isStaff() ? 'staff' : '' }}">
            <div class="message-header">
                <div class="row">
                    <div class="col-md-6">
                        <strong>From:</strong> 
                        {{ $response->user->name }} 
                        @if($response->user->isStaff())
                            <span class="badge badge-primary">{{ ucfirst($response->user->role) }}</span>
                        @else
                            <span class="badge badge-secondary">Customer</span>
                        @endif
                    </div>
                    <div class="col-md-6 text-right">
                        <strong>Date:</strong> {{ $response->created_at->format('M d, Y H:i') }}
                    </div>
                </div>
            </div>
            <div class="message-content">
                {{ $response->message }}
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            No responses yet.
        </div>
    @endforelse
</div>