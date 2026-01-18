@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 700px; margin: 20px auto; font-family: sans-serif;">
    <h1>Créer votre équipe</h1>

    <form action="{{ route('teams.store') }}" method="POST">
        @csrf
        <div style="background: #f4f4f4; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #ddd;">
            <p><strong>Raid:</strong> {{ $raid->RAI_NOM }}</p>
            <p><strong>Course:</strong> {{ $course->COU_NOM }}</p>
            <p><strong>Club:</strong> {{ $club->CLU_NOM }}</p>
            <p><strong>Limite:</strong> <span id="max_val">{{ $maxParticipants }}</span> participants max.</p>
        </div>

        <input type="hidden" name="clu_id" value="{{ $clu_id }}">
        <input type="hidden" name="rai_id" value="{{ $rai_id }}">
        <input type="hidden" name="cou_id" value="{{ $cou_id }}">

        <div style="margin-bottom: 20px;">
            <label><strong>Nom d'équipe:</strong></label><br>
            <input type="text" name="equ_nom" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" placeholder="ex: Les guépards">
            @error('equ_nom')
                <div style="color: red; font-size: 0.9em;">{{ $message }}</div>
            @enderror
        </div>

        <hr>

        <div style="margin-bottom: 20px; position: relative;">
            <label><strong>Rechercher un membre:</strong></label><br>
            <input type="text" id="searchInput" placeholder="Tapez le nom d'un coureur... (2 caractères minimum)" autocomplete="off" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            
            <div id="suggestionBox" style="position: absolute; width: 100%; background: white; border: 1px solid #ccc; border-top: none; display: none; z-index: 100; max-height: 200px; overflow-y: auto; box-shadow: 0 4px 8px rgba(0,0,0,0.1);"></div>
        </div>

        <div style="margin-bottom: 25px;">
            <label><strong>Membres de l'équipe (<span id="memberCount">0</span>/{{ $maxParticipants }}):</strong></label>
            <div id="listContainer" style="border: 1px solid #ddd; padding: 15px; background: #fff; min-height: 50px; border-radius: 4px; margin-top: 10px;">
                <p id="emptyMessage" style="color: gray; font-style: italic; margin: 0;">Pas encore de membres ajoutés.</p>
            </div>
            @error('members')
                <div style="color: red; font-size: 0.9em; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" style="background: #28a745; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 1.1em; font-weight: bold;">
            Inscrire mon équipe
        </button>

        <div style="text-align: center; margin-top: 2rem;">
            <button onclick="window.history.back();" class="btn btn-secondary" type="button">
                Revenir en arrière
            </button>
        </div>
    </form>
</div>

<script>
    const maxParticipants = parseInt("{{ $maxParticipants }}") || 5;
    let selectedMembers = [];

    const searchInput = document.getElementById('searchInput');
    const suggestionBox = document.getElementById('suggestionBox');
    const listContainer = document.getElementById('listContainer');
    const memberCountLabel = document.getElementById('memberCount');
    const emptyMessage = document.getElementById('emptyMessage');

    const ageMinCourse = parseInt("{{ $ageLimits->CAT_AGE_MIN ?? 0 }}");
    const ageMaxCourse = parseInt("{{ $ageLimits->CAT_AGE_MAX ?? 99 }}");

    const currentYear = 2026;

    function addMember(id, name, birthDate) {
        if (selectedMembers.length >= maxParticipants) { 
            alert(`La limite de l'équipe est atteinte, ${maxParticipants} participants maximum.`); 
            return; 
        }
        
        if (selectedMembers.some(member => member.id === id)) { 
            alert("Ce coureur est déjà inscrit dans votre équipe."); 
            return; 
        }

        if (birthDate) {
            const birthYear = new Date(birthDate).getFullYear();
            const age = currentYear - birthYear;

            if (age < ageMinCourse || age > ageMaxCourse) {
                alert(`📢 Note : ${name} a ${age} ans. Les catégories d'âge pour cette course vont de ${ageMinCourse} à ${ageMaxCourse} ans.`);
            }
        }

        selectedMembers.push({id: id, name: name});
        
        searchInput.value = '';
        suggestionBox.style.display = 'none';
        renderList();
    }

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value;

        if (searchTerm.length < 2) { 
            suggestionBox.style.display = 'none'; 
            return; 
        }

        fetch(`{{ route('runners.search') }}?term=${searchTerm}`)
            .then(response => response.json())
            .then(data => {
                suggestionBox.innerHTML = '';
                if (data.length > 0) {
                    suggestionBox.style.display = 'block';
                    data.forEach(runner => {
                        const id = runner.COM_ID || runner.com_id;
                        const lastName = (runner.COM_NOM || runner.com_nom).toUpperCase();
                        const firstName = runner.COM_PRENOM || runner.com_prenom;
                        const fullName = `${firstName} ${lastName}`;
                        const birthDate = runner.COM_DATE_NAISSANCE || runner.com_date_naissance;

                        const item = document.createElement('div');
                        item.innerHTML = fullName;
                        item.style = "padding: 10px; cursor: pointer; border-bottom: 1px solid #eee;";
                        
                        item.onmouseover = () => item.style.backgroundColor = '#f8f9fa';
                        item.onmouseout = () => item.style.backgroundColor = 'white';
                        
                        item.onclick = () => addMember(id, fullName, birthDate);
                        
                        suggestionBox.appendChild(item);
                    });
                } else {
                    suggestionBox.style.display = 'none';
                }
            });
    });

    function removeMember(id) {
        selectedMembers = selectedMembers.filter(member => member.id !== id);
        renderList();
    }

    function renderList() {
        emptyMessage.style.display = selectedMembers.length ? 'none' : 'block';
        memberCountLabel.innerText = selectedMembers.length;
        listContainer.querySelectorAll('.member-item').forEach(el => el.remove());
        
        selectedMembers.forEach((member) => {
            const row = document.createElement('div');
            row.className = 'member-item';
            row.style = "background: #eef2ff; margin-bottom: 10px; padding: 12px; border-radius: 4px; border: 1px solid #c7d2fe; display: flex; flex-direction: column; gap: 8px;";
            
            row.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span><strong>${member.name}</strong></span>
                    <button type="button" onclick="removeMember(${member.id})" style="background:#ef4444; color:white; border:none; border-radius:3px; padding:5px 10px; cursor:pointer;">Retirer</button>
                </div>
                <div style="display: flex; align-items: center; gap: 10px; background: white; padding: 5px; border-radius: 4px;">
                    <label style="font-size: 0.9em; min-width: 110px;">Numéro PPS :</label>
                    <input type="text" name="pps[${member.id}]" placeholder="Ex: FA1561" style="flex-grow: 1; border: 1px solid #ccc; border-radius: 3px; padding: 5px;">
                    <input type="hidden" name="members[]" value="${member.id}">
                </div>
            `;
            listContainer.appendChild(row);
        });
    }

    document.addEventListener('click', (event) => { 
        if(event.target !== searchInput) suggestionBox.style.display = 'none'; 
    });
</script>
@endsection