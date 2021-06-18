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