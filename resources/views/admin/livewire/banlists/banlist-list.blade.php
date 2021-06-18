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
                <livewire:sqms-servers.admin.banlists.banlist-entry :banlist="$banlist" :key="$banlist->id" />
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $servers->links() }}
    @else
    <p class="text-center">No Ban-Lists have been created yet.</p>
    @endif
</div>