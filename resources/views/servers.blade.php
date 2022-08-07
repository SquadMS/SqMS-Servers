<x-sqms-foundation::templates.page :title="__('sqms-servers::pages/servers.heading')">
<div class="sqmss-flex sqmss-flex-wrap server-list">
    @foreach ($servers as $server)
        <livewire:sqms-servers::server-entry :server="$server" />
    @endforeach
</div>

@pushOnce('styles')
@livewireStyles
<link href="{{ mix('css/sqms-servers.css', 'vendor/sqms-servers') }}" rel="stylesheet">
@endPushOnce

@pushOnce('scripts')
@livewireScripts
@endPushOnce
</x-sqms-foundation::templates.page>