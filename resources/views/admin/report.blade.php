@extends('layoutAdmin')

@section('content')
<div class="container mt-5">
    <h2>Reported Users</h2>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>Reported User</th>
                <th>Reporter</th>
                <th>Reason</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            @php
                                $reportedUserProfilePicture = $report->reportedUser->profilePicture;
                            @endphp
                            @if($reportedUserProfilePicture && $reportedUserProfilePicture->file_path)
                                <img src="{{ Storage::url($reportedUserProfilePicture->file_path) }}" alt="profile-img" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <img src="{{ asset('/images/template/user/Noprofile.jpg') }}" alt="profile-img" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            @endif
                            <div class="ms-3">
                                <strong>{{ $report->reportedUser->name }}</strong><br>
                                <small>{{ $report->reportedUser->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            @php
                                $reporterProfilePicture = $report->reporter->profilePicture;
                            @endphp
                            @if($reporterProfilePicture && $reporterProfilePicture->file_path)
                                <img src="{{ Storage::url($reporterProfilePicture->file_path) }}" alt="profile-img" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <img src="{{ asset('/images/template/user/Noprofile.jpg') }}" alt="profile-img" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            @endif
                            <div class="ms-3">
                                <strong>{{ $report->reporter->name }}</strong><br>
                                <small>{{ $report->reporter->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $report->reason }}</td>
                    <td>
                        <!-- View Profile Button -->
                        <a href="{{ route('admin.reports.user.profile', ['id' => $report->reportedUser->id]) }}" class="btn btn-primary btn-sm">View Profile</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
