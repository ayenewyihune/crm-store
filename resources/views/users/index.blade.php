@extends('layouts.admin')

@section('content')
    <div class="card-body table-responsive px-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date registered</th>
                    <th>Roles</th>
                    <th>Admin</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $user)
                    <tr>
                        <th scope="row">{{ ($users->currentpage() - 1) * $users->perpage() + $key + 1 }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>{{ implode(', ',$user->roles()->pluck('name')->toArray()) }}</td>
                        <td><button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#modal-admin-{{ $user->id }}">
                                @if ($user->is_admin())
                                    Remove
                                @else
                                    Add
                                @endif
                            </button>
                        </td>
                    </tr>

                    <!-- Toggle admin modal -->
                    <div class="modal fade" id="modal-admin-{{ $user->id }}" tabindex="-1"
                        aria-labelledby="modalDeleteLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">
                                        @if ($user->is_admin())
                                            Remove admin status
                                        @else
                                            Make admin
                                        @endif
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('admin.users.toggle_admin', $user->id) }}" method="POST">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body">
                                        <div class="container-fluild form-group">
                                            @if ($user->is_admin())
                                                <p>Are you sure you want to remove admin privilege from this user?</p>
                                            @else
                                                <p>Are you sure you want to make this user an admin?</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger btn-icon">
                                            @if ($user->is_admin())
                                                Remove
                                            @else
                                                Make admin
                                            @endif
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $users->links() }}
@endsection
