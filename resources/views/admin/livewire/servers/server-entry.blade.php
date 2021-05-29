<tr>
    <th scope="row">{{ $server->name }}</th>
    <td class="text-end">
        <livewire:sqms-servers.admin.servers.edit-server :server="$server" />
        <livewire:sqms-servers.admin.servers.delete-server :server="$server" />
    </td>
</tr>