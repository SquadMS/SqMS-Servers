<tr>
    <td scope="row">{{ $server->name }}</td>
    <td class="text-end">
        <livewire:sqms-servers.admin.servers.edit-server :server="$server" />
        <livewire:sqms-servers.admin.servers.delete-server :server="$server" />
    </td>
</tr>