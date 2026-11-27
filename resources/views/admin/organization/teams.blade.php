@extends('admin.layouts.app')
@section('title', __('Teams & Leads'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div>
                <h4 class="mb-0">{{ __('Teams & Team Leads') }}</h4>
            </div>
            <div class="d-flex gap-2">
                @can('users.teams.assign')
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#assignMemberModal">
                        <i class="bx bx-user-check"></i> {{ __('Assign Member') }}
                    </button>
                @endcan
                @can('users.teams.create')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTeamModal">
                        <i class="bx bx-plus"></i> {{ __('Add Team') }}
                    </button>
                @endcan
            </div>
        </div>

        <div class="card radius-10">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle erp-datatable w-100">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Team Name') }}</th>
                                <th>{{ __('Department') }}</th>
                                <th>{{ __('Parent Team') }}</th>
                                <th>{{ __('Members') }}</th>
                                <th class="text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teams as $team)
                                <tr>
                                    <td><strong>{{ $team->name }}</strong> <small class="text-muted d-block"><code>{{ $team->slug }}</code></small></td>
                                    <td>{{ $team->department?->name ?? '-' }}</td>
                                    <td>{{ $team->parent?->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-light-primary text-primary px-3">{{ $team->members->count() }} Members</span>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        @can('users.teams.create')
                                            <button class="btn btn-sm btn-outline-primary btn-edit-team"
                                                data-id="{{ $team->id }}"
                                                data-name="{{ $team->name }}"
                                                data-slug="{{ $team->slug }}"
                                                data-dept="{{ $team->department_id }}"
                                                data-parent="{{ $team->parent_id }}"
                                                data-desc="{{ $team->description }}">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                            <form class="d-inline ajax-delete-form" method="POST" action="{{ route('admin.organization.teams.destroy', $team->id) }}" data-title="Delete team {{ $team->name }}?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i></button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Team Modal -->
<div class="modal fade" id="createTeamModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.organization.teams.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Create Team') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12"><label class="form-label">{{ __('Team Name *') }}</label><input type="text" name="name" class="form-control" required></div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Department') }}</label>
                    <select name="department_id" class="form-select">
                        <option value="">{{ __('-- Optional --') }}</option>
                        @foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->code }} - {{ $d->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Parent Team') }}</label>
                    <select name="parent_id" class="form-select">
                        <option value="">{{ __('-- Root Team --') }}</option>
                        @foreach($teams as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-12"><label class="form-label">{{ __('Description') }}</label><textarea name="description" class="form-control" rows="2"></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Create Team') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Team Modal -->
<div class="modal fade" id="editTeamModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editTeamForm" class="modal-content ajax-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Edit Team') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-7"><label class="form-label">{{ __('Team Name *') }}</label><input type="text" name="name" id="et_name" class="form-control" required></div>
                <div class="col-md-5"><label class="form-label">{{ __('Slug') }}</label><input type="text" name="slug" id="et_slug" class="form-control"></div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Department') }}</label>
                    <select name="department_id" id="et_dept" class="form-select">
                        <option value="">{{ __('-- Optional --') }}</option>
                        @foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->code }} - {{ $d->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Parent Team') }}</label>
                    <select name="parent_id" id="et_parent" class="form-select">
                        <option value="">{{ __('-- Root Team --') }}</option>
                        @foreach($teams as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-12"><label class="form-label">{{ __('Description') }}</label><textarea name="description" id="et_desc" class="form-control" rows="2"></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Update Team') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Assign Member Modal -->
<div class="modal fade" id="assignMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.organization.teams.members') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Assign Staff to Team') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">{{ __('Team *') }}</label>
                    <select name="team_id" class="form-select" required>
                        @foreach($teams as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('Staff Member *') }}</label>
                    <select name="user_id" class="form-select" required>
                        @foreach($users as $u)<option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>@endforeach
                    </select>
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_leader" value="1" id="am_leader">
                        <label class="form-check-label" for="am_leader">Designate as Team Leader</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Assign Member') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).on('click', '.btn-edit-team', function() {
    $('#editTeamForm').attr('action', "{{ url('/admin/organization/teams') }}/" + $(this).data('id'));
    $('#et_name').val($(this).data('name'));
    $('#et_slug').val($(this).data('slug'));
    $('#et_dept').val($(this).data('dept') || '');
    $('#et_parent').val($(this).data('parent') || '');
    $('#et_desc').val($(this).data('desc') || '');
    new bootstrap.Modal(document.getElementById('editTeamModal')).show();
});
</script>
@endpush
