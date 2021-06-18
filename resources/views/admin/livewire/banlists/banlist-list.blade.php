<div>
    @if ($servers->count())
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">List</th>
                    <th scope="col">Global</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($banlists as $banlist)
                <tr>
                    <td scope="row">{{ $banlist->name }}</td>
                    <td>
                        @if ($banlist->global)
                            <i class="bi bi-check-lg"></i>
                        @else
                            <i class="bi bi-x-lg"></i>
                        @endif
                    </td>
                    <td class="text-end">
                        <livewire:sqms-servers.admin.servers.edit-server :server="$server" />
                        <livewire:sqms-servers.admin.servers.delete-server :server="$server" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $servers->links() }}
    @else
    <p class="text-center">No Ban-Lists have been created yet.</p>
    @endif
</div>