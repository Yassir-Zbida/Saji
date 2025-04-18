<div class="ticket-form">
    <div class="form-group">
        <label for="subject">Subject</label>
        <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject', $ticket->subject ?? '') }}" required>
        @error('subject')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    @if(!isset($ticket))
    <div class="form-group">
        <label for="message">Message</label>
        <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror" rows="6" required>{{ old('message') }}</textarea>
        @error('message')
            {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
        @enderror
    </div>
    @endif
    
    <div class="form-group">
        <label for="priority">Priority</label>
        <select name="priority" id="priority" class="form-control @error('priority') is-invalid @enderror" required>
            <option value="low" {{ old('priority', $ticket->priority ?? '') == 'low' ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ old('priority', $ticket->priority ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="high" {{ old('priority', $ticket->priority ?? '') == 'high' ? 'selected' : '' }}>High</option>
        </select>
        @error('priority')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    @if(isset($ticket) && (Auth::user()->isAdmin() || Auth::user()->isManager()))
    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
            <option value="open" {{ old('status', $ticket->status) == 'open' ? 'selected' : '' }}>Open</option>
            <option value="in_progress" {{ old('status', $ticket->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="resolved" {{ old('status', $ticket->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
            <option value="closed" {{ old('status', $ticket->status) == 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
        @error('status')
            {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
        @enderror
    </div>
    @endif
</div>