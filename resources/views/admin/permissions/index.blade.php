@extends('admin.layouts.app')
@section('title', 'Permissions')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <h4 class="mb-3">System Permissions</h4>
            <div class="row">
                @foreach($permissions as $module => $items)
                    <div class="col-md-6 mb-3">
                        <div class="card radius-10 h-100">
                            <div class="card-header bg-light">
                                <strong class="text-uppercase">{{ $module }} Module</strong>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    @foreach($items as $permission)
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <span>{{ $permission->name }}</span>
                                            <code>{{ $permission->slug }}</code>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
