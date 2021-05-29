<div>
    @if ($servers->count())
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Server</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($servers as $server)
                <livewire:sqms-servers.admin.servers.server-entry :server="$server"></livewire:sqms-servers.admin.servers.server-entry />
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $servers->links() }}
    @else
    <p class="text-center">No servers have been created yet.</p>
    @endif
</div>