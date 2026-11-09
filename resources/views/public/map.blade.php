@extends('layouts.public')

@section('title', 'Peta Wisata Digital (GIS) - Pantai Jumiang')

@section('content')
<div class="container-fluid px-3 py-3">
    <div class="card card-custom overflow-hidden">
        <!-- Map Header & Filter Toolbar -->
        <div class="card-header bg-white py-3 px-4 border-bottom">
            <div class="row align-items-center g-3">
                <div class="col-md-5">
                    <h4 class="fw-bold mb-0 text-primary brand-font">
                        <i class="bi bi-geo-alt-fill text-danger me-2"></i>PETA DIGITAL PANTAI JUMIANG
                    </h4>
                    <small class="text-muted">Web-GIS Berbasis Leaflet.js & OpenStreetMap (WGS84)</small>
                </div>
                <div class="col-md-7">
                    <!-- Layer Filter Buttons -->
                    <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                        <a href="{{ route('map', ['category' => 'ALL']) }}" class="btn btn-sm {{ $category === 'ALL' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3">
                            Semua Titik ({{ count($markers) }})
                        </a>
                        <a href="{{ route('map', ['category' => 'WISATA']) }}" class="btn btn-sm {{ $category === 'WISATA' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3">
                            <i class="bi bi-camera me-1"></i> Spot Wisata
                        </a>
                        <a href="{{ route('map', ['category' => 'UMKM']) }}" class="btn btn-sm {{ $category === 'UMKM' ? 'btn-success text-white' : 'btn-outline-success' }} rounded-pill px-3">
                            <i class="bi bi-shop me-1"></i> Gerai UMKM
                        </a>
                        <a href="{{ route('map', ['category' => 'FASILITAS']) }}" class="btn btn-sm {{ $category === 'FASILITAS' ? 'btn-info text-white' : 'btn-outline-info' }} rounded-pill px-3">
                            <i class="bi bi-life-preserver me-1"></i> Fasilitas
                        </a>
                        <a href="{{ route('map', ['category' => 'HOMESTAY']) }}" class="btn btn-sm {{ $category === 'HOMESTAY' ? 'btn-warning text-dark' : 'btn-outline-warning' }} rounded-pill px-3">
                            <i class="bi bi-house me-1"></i> Homestay
                        </a>
                        <a href="{{ route('map', ['category' => 'PARKIR']) }}" class="btn btn-sm {{ $category === 'PARKIR' ? 'btn-dark' : 'btn-outline-dark' }} rounded-pill px-3">
                            <i class="bi bi-p-square me-1"></i> Parkir
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Container -->
        <div id="jumiangMap" style="height: 620px; width: 100%; z-index: 1;"></div>

        <!-- Map Legend & Coordinate Info -->
        <div class="card-footer bg-light py-2 px-4 d-flex flex-wrap justify-content-between align-items-center small text-secondary">
            <div>
                <span class="me-3"><i class="bi bi-geo text-danger"></i> Datum: WGS84</span>
                <span class="me-3"><i class="bi bi-bullseye text-primary"></i> Koordinat Pusat: -7.228912, 113.538120</span>
                <span><i class="bi bi-pin-map text-success"></i> Desa Tanjung, Kec. Pademawu, Pamekasan</span>
            </div>
            <div>
                <span class="badge bg-secondary">Total: {{ count($markers) }} Marker Aktif</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Center coords: Pantai Jumiang (-7.228912, 113.538120)
        const centerLat = -7.228912;
        const centerLng = 113.538120;

        const map = L.map('jumiangMap').setView([centerLat, centerLng], 15);

        // Tile Layer from OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Raw Marker Data from Server
        const markersData = @json($markers);

        // Category Color Mapping
        const getMarkerColor = (cat) => {
            switch(cat) {
                case 'WISATA': return '#0284c7'; // Blue
                case 'UMKM': return '#16a34a'; // Green
                case 'FASILITAS': return '#06b6d4'; // Cyan
                case 'HOMESTAY': return '#ea580c'; // Orange
                case 'PARKIR': return '#475569'; // Slate
                default: return '#dc2626';
            }
        };

        // Render Leaflet Circle Markers with Popups
        markersData.forEach(item => {
            const circle = L.circleMarker([item.latitude, item.longitude], {
                radius: 10,
                fillColor: getMarkerColor(item.category),
                color: "#ffffff",
                weight: 2,
                opacity: 1,
                fillOpacity: 0.9
            }).addTo(map);

            const popupContent = `
                <div style="min-width: 180px;">
                    <span style="background:${getMarkerColor(item.category)}; color:white; font-size:10px; font-weight:bold; padding:2px 6px; border-radius:4px; text-transform:uppercase;">
                        ${item.category}
                    </span>
                    <h6 style="margin: 6px 0 3px; font-weight: bold; color:#0f172a;">${item.title}</h6>
                    <p style="font-size:12px; color:#64748b; margin:0 0 6px;">${item.description || 'Lokasi strategis di kawasan Pantai Jumiang.'}</p>
                    <small style="color:#94a3b8; font-size:10px;">Koordinat: ${item.latitude.toFixed(6)}, ${item.longitude.toFixed(6)}</small>
                </div>
            `;
            circle.bindPopup(popupContent);
        });
    });
</script>
@endpush
