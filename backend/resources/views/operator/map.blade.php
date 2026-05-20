@extends('layouts.operator')

@section('content')
<style>
    .map-wrapper {
        position: relative;
        margin: -40px -40px 0 -40px;
    }

    #mainMap {
        width: 100%;
        height: calc(100vh - 73px);
    }

    .map-filter-panel {
        position: absolute;
        top: 80px;
        left: 20px;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px);
        border-radius: 16px;
        padding: 24px;
        width: 280px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    }

    .map-filter-panel h3 {
        font-size: 1rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .filter-clear {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--maroon, #6B1D2A);
        cursor: pointer;
        text-decoration: none;
    }

    .filter-section-label {
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #9ca3af;
        margin: 16px 0 10px;
    }

    .filter-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 0;
        cursor: pointer;
    }

    .filter-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--maroon, #6B1D2A);
        cursor: pointer;
    }

    .filter-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .filter-checkbox span {
        font-size: 0.85rem;
        color: #4b5563;
    }

    .map-legend {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        border-radius: 12px;
        padding: 10px 24px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        display: flex;
        gap: 20px;
        align-items: center;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.68rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #4b5563;
    }

</style>

<div class="map-wrapper">
    {{-- Filter --}}
    <div class="map-filter-panel">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Filter</h3>
            <a href="#" class="filter-clear" onclick="resetFilters(); return false;">Bersihkan Semua</a>
        </div>

        <p class="filter-section-label">Status Kerusakan</p>

        <label class="filter-checkbox"><input type="checkbox" class="status-filter" value="Dilaporkan" checked><span class="filter-dot" style="background:#EF4444;"></span><span>Dilaporkan</span></label>
        <label class="filter-checkbox"><input type="checkbox" class="status-filter" value="Disurvey" checked><span class="filter-dot" style="background:#F59E0B;"></span><span>Disurvey</span></label>
        <label class="filter-checkbox"><input type="checkbox" class="status-filter" value="Tidak Valid"><span class="filter-dot" style="background:#374151;"></span><span>Tidak Valid</span></label>
        <label class="filter-checkbox"><input type="checkbox" class="status-filter" value="Diproses"><span class="filter-dot" style="background:#3B82F6;"></span><span>Diproses</span></label>
        <label class="filter-checkbox"><input type="checkbox" class="status-filter" value="Selesai"><span class="filter-dot" style="background:#22C55E;"></span><span>Selesai</span></label>
    </div>

    {{-- Map --}}
    <div id="mainMap"></div>

    {{-- Legend --}}
    <div class="map-legend">
        <div class="legend-item"><span class="filter-dot" style="background:#EF4444;"></span> Dilaporkan</div>
        <div class="legend-item"><span class="filter-dot" style="background:#F59E0B;"></span> Disurvey</div>
        <div class="legend-item"><span class="filter-dot" style="background:#374151;"></span> Tidak Valid</div>
        <div class="legend-item"><span class="filter-dot" style="background:#3B82F6;"></span> Diproses</div>
        <div class="legend-item"><span class="filter-dot" style="background:#22C55E;"></span> Selesai</div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisiasi Map
        const map = L.map('mainMap', {
            zoomControl: false
        }).setView([-6.2088, 106.8456], 12);

        // Warna map light
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://carto.com/">CARTO</a>',
            maxZoom: 19
        }).addTo(map);

        // Zoom control bottom-right
        L.control.zoom({
            position: 'bottomright'
        }).addTo(map);

        let markersLayer = L.layerGroup().addTo(map);

        // Load GeoJSON
        function loadMarkers() {
            const checked = [];
            document.querySelectorAll('.status-filter:checked').forEach(cb => checked.push(cb.value));

            if (checked.length === 0) {
                markersLayer.clearLayers();
                return;
            }

            const url = '{{ route("operator.map.geojson") }}?status=' + checked.join(',');

            fetch(url)
                .then(res => res.json())
                .then(geojson => {
                    markersLayer.clearLayers();

                    L.geoJSON(geojson, {
                        pointToLayer: function(feature, latlng) {
                            return L.circleMarker(latlng, {
                                radius: 8,
                                fillColor: feature.properties.color,
                                color: '#ffffff',
                                weight: 2.5,
                                fillOpacity: 0.9,
                            });
                        },
                        onEachFeature: function(feature, layer) {
                            const p = feature.properties;
                            const popup = `
                                <div style="font-family:Manrope,sans-serif;min-width:220px;">
                                    ${p.photo_url ? `<img src="${p.photo_url}" style="width:100%;height:120px;object-fit:cover;border-radius:8px;margin-bottom:10px;">` : ''}
                                    <h4 style="font-size:0.9rem;font-weight:700;color:#1a1a1a;margin:0 0 4px 0;">${p.description || 'Laporan Kerusakan Jalan'}</h4>
                                    <p style="font-size:0.75rem;color:#9ca3af;margin:0 0 8px 0;">${p.created_at}</p>
                                    <div style="display:flex;justify-content:space-between;align-items:center;">
                                        <div>
                                            <p style="font-size:0.6rem;font-weight:600;text-transform:uppercase;color:#9ca3af;margin:0;">STATUS</p>
                                            <p style="font-size:0.8rem;font-weight:600;color:${p.color};margin:2px 0 0 0;">${p.status}</p>
                                        </div>
                                        <a href="${p.detail_url}" style="display:inline-block;padding:6px 14px;background:var(--maroon,#6B1D2A);color:white;border-radius:8px;font-size:0.75rem;font-weight:600;text-decoration:none;">Lihat Detail</a>
                                    </div>
                                </div>
                            `;
                            layer.bindPopup(popup, {
                                maxWidth: 280
                            });
                        }
                    }).addTo(markersLayer);
                });
        }

        // Load
        loadMarkers();

        // Ganti filter
        document.querySelectorAll('.status-filter').forEach(cb => {
            cb.addEventListener('change', loadMarkers);
        });

        // Reset filter
        window.resetFilters = function() {
            document.querySelectorAll('.status-filter').forEach(cb => cb.checked = false);
            markersLayer.clearLayers();
        };
    });
</script>
@endsection