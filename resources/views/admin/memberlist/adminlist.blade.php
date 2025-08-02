@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h2>User List</h2>

        @if(session('success'))
            <div style="color: green;">{{ session('success') }}</div>
        @endif

        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>OTP</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->otp }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <form action="{{ route('admin.adminlist.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color:red;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
