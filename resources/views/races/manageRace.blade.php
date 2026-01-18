@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Mes Courses</h1>

    @if(session('success'))
        <div class="alert alert-success">
            CSV inséré avec succès !
        </div>
    @endif

    @if($races->isEmpty())
        <p class="text-muted">Vous n'organisez actuellement aucune course.</p>
    @else
        @foreach($races as $race)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        {{ $race->COU_NOM ?? 'Nom non défini' }}
                    </h5>

                    <p class="card-text">
                        <strong>Date Debut :</strong> {{ $race->COU_DATE_DEBUT ?? 'Non définie' }}
                    </p>
                    <p class="card-text">
                        <strong>Date Fin :</strong> {{ $race->COU_DATE_FIN ?? 'Non définie' }}
                    </p>

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('view.participants', [
                            $race->CLU_ID,
                            $race->RAI_ID,
                            $race->COU_ID
                        ]) }}" class="btn btn-primary btn-sm">
                            Voir participants
                        </a>

                        <!-- Import CSV -->
                        <form action="{{ route('csv.import', [
                            'cluId' => $race->CLU_ID,
                            'raiId' => $race->RAI_ID,
                            'couId' => $race->COU_ID
                        ]) }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                            @csrf
                            <input type="file" name="csv_file" accept=".csv" required class="form-control form-control-sm">
                            <button type="submit" class="btn btn-success btn-sm">
                                Importer CSV
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
