<div class="card">
    <div class="card-body">
        <h5 class="card-title">Server Chat</h5>

        <hr>

        <div id="chatMessagesContainer" class="chat-messages-container overflow-auto bg-dark text-white mb-3">
            <div id="chatLoadingBefore" class="align-items-center justify-content-center p-3 d-none">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading more Chat-Messages...</span>
                </div>
            </div>
            <div id="chatMessages" class="chat-messages">
                @foreach ($messages as $message)
                    @include('admin.servers.includes.message', ['message' => $message])
                @endforeach
            </div>
            <div id="chatLoading" class="align-items-center justify-content-center p-3 d-none">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading more Chat-Messages...</span>
                </div>
            </div>
        </div>

        <form id="form-broadcast" action="{{ route('admin.servers.send-broadcast', ['server' => $server]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="inputMessage">Message</label>
                <input type="text"
                    class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}"
                    id="inputMessage" name="message" value="{{ old('message') }}"
                    required>
                @if ($errors->has('host'))
                    <div class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('message') }}</strong>
                    </div>
                @endif
            </div>
            
            <button type="submit" class="btn btn-block btn-primary">Send</button>
        </form>
    </div>
</div>