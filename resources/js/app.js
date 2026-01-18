import './bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            const distanceMaxInput = document.getElementById('distance_max');

            // Fonction pour recalculer la distance et filtrer
            function updateDistances() {
                const maxDistance = parseFloat(distanceMaxInput.value) || Infinity;

                document.querySelectorAll('.card[data-lat][data-lng]').forEach(card => {
                    const raidLat = parseFloat(card.dataset.lat);
                    const raidLng = parseFloat(card.dataset.lng);

                    if (!isNaN(raidLat) && !isNaN(raidLng)) {
                        const distance = calculateDistance(userLat, userLng, raidLat, raidLng);
                        card.querySelector('.distance').textContent = `Distance : ${distance.toFixed(2)} km`;

                        // Filtre par distance max
                        if (distance > maxDistance) {
                            card.style.display = 'none';
                        } else {
                            card.style.display = 'block';
                        }
                    }
                });
            }

            // Initial
            updateDistances();

            // Recalculer à chaque changement de distance max
            distanceMaxInput.addEventListener('input', updateDistances);

        });
    }
});

function calculateDistance(lat1, lng1, lat2, lng2) {
    const R = 6371;
    const dLat = (lat2-lat1) * Math.PI / 180;
    const dLng = (lng2-lng1) * Math.PI / 180;
    const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) * Math.sin(dLng/2)**2;
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}



function filterResponsables(input) {
    const filter = input.value.toLowerCase();
    const select = input.nextElementSibling;
    const options = select.options;

    for (let i = 0; i < options.length; i++) {
        const text = options[i].text.toLowerCase();
        options[i].style.display = text.includes(filter) ? '' : 'none';
    }
}

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
