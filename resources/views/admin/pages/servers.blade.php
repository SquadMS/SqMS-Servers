@extends('sqms-foundation::admin.structure.layout')

@section('content')
<section>
    <div class="container-fluid">
        <div class="row pb-4">
            <div class="col">
                <h1 class="d-flex aliign-items-center">
                    <span class="flex-grow-1">Servers</span>
                    <livewire:sqms-servers.admin.servers.create-server></livewire:sqms-servers.admin.servers.create-server/>
                </h1>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col text-center">
                <livewire:sqms-servers.admin.servers.server-list></livewire:sqms-servers.admin.servers.server-list/>
            </div>
        </div>
    </div>
</section>
@endsection