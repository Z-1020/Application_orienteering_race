@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="text-center">Courses en attente de validation</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Raid</th>
                <th>Club</th>
                <th>Organisateur</th>
                <th>E-mail</th>
                <th>Date de demande</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($courses as $course)
            <tr>
                <td>{{ $course->COU_NOM }}</td>
                <td>{{ $course->raid_nom ?? '-' }}</td>
                <td>{{ $course->club_nom ?? '-' }}</td>
                <td>{{ $course->organisateur_nom }} {{ $course->organisateur_prenom }}</td>
                <td>{{ $course->organisateur_mail }}</td>
                <td>{{ $course->COU_DATE_DEMANDE }}</td>
                <td class="d-flex gap-2">

                    <form action="{{ route('admin.courses.valider', $course->COU_ID) }}" method="POST">
                    @csrf
                        <button class="btn btn-success btn-sm">
                            Valider
                        </button>
                    </form>

                    <form action="{{ route('admin.courses.refuser', $course->COU_ID) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment refuser cette course ?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">
                            Refuser
                        </button>
                    </form>

                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">
                    Aucune course en attente
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>
@endsection
