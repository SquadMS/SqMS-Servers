@extends('sqms-foundation::admin.structure.layout')

@section('content')
<section>
    <div class="container-fluid">
        <div class="row pb-4">
            <div class="col">
                <h1 class="d-flex aliign-items-center">Server</h1>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col text-center">
                <livewire:sqms-servers.admin.server.chat :server="$server" />
            </div>
        </div>
    </div>
</section>
@endsection