@if(!$ticket->isClosed())
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Reply to Ticket</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('support-tickets.add-response', $ticket->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror" placeholder="Type your response here..." required></textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="notifyCustomer" name="notify_customer" value="1" checked>
                        <label class="custom-control-label" for="notifyCustomer">Notify customer by email</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Send Response
                </button>
            </form>
        </div>
    </div>
@else
    <div class="alert alert-warning mt-4">
        <i class="fas fa-exclamation-circle"></i> This ticket is closed. Reopen it to add more responses.
    </div>
@endif