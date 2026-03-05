import L from "leaflet";
import "leaflet/dist/leaflet.css";

// Fix for default icon paths in Vite
import iconUrl from "leaflet/dist/images/marker-icon.png";
import iconRetinaUrl from "leaflet/dist/images/marker-icon-2x.png";
import shadowUrl from "leaflet/dist/images/marker-shadow.png";

L.Icon.Default.mergeOptions({
    iconUrl,
    iconRetinaUrl,
    shadowUrl,
});

if (typeof window !== "undefined") {
    window.L = L;
}

function normalizePoint(p) {
    const la = parseFloat(String(p.lat ?? p.la ?? "").replace(",", "."));
    const lo = parseFloat(String(p.lng ?? p.lo ?? "").replace(",", "."));
    if (!isFinite(la) || !isFinite(lo)) {
        console.warn("BPRSMaps: Invalid point rejected", {
            lat_in: p.lat ?? p.la,
            lng_in: p.lng ?? p.lo,
            point: p,
        });
        return null;
    }
    return {
        id: p.id ?? p.i ?? null,
        lat: la,
        lng: lo,
        name: p.name ?? p.n ?? "",
        address: p.address ?? p.a ?? "",
        type: p.type ?? p.t ?? "",
        url: p.url ?? p.u ?? "",
    };
}

function cleanupContainer(el) {
    if (!el) return;
    try {
        if (el._leaflet_id) el._leaflet_id = null;
        el.innerHTML = "";
    } catch (e) {}
}

function createMap(containerId, points, opts = {}) {
    const el =
        typeof containerId === "string"
            ? document.getElementById(containerId)
            : containerId;
    if (!el) return null;

    // Clean previous instance if any
    cleanupContainer(el);

    const m = L.map(el, { scrollWheelZoom: !!opts.scrollWheelZoom }).setView(
        [0, 0],
        2,
    );
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: "&copy; OpenStreetMap",
    }).addTo(m);

    const markers = [];
    const markersById = {};
    const norm = (points || []).map(normalizePoint).filter(Boolean);
    norm.forEach((x) => {
        const mk = L.marker([x.lat, x.lng]).addTo(m);
        const html =
            `<div class="min-w-[180px]">` +
            (x.type
                ? `<div class="text-xs uppercase tracking-wide text-emerald-600 font-bold mb-1">${x.type}</div>`
                : "") +
            (x.url
                ? `<a href="${x.url}" class="font-bold text-gray-900 hover:text-emerald-600">${x.name || "Lokasi"}</a>`
                : `<div class="font-bold text-gray-900">${x.name || "Lokasi"}</div>`) +
            (x.address
                ? `<div class="text-sm text-gray-600 mt-1">${x.address}</div>`
                : "") +
            `<a href="https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(x.lat + "," + x.lng)}" target="_blank" class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-700 text-sm font-medium mt-2">Arah</a>` +
            `</div>`;
        mk.bindPopup(html);
        markers.push(mk);
        if (x.id != null) {
            markersById[String(x.id)] = mk;
        }
    });

    if (markers.length > 0) {
        const group = L.featureGroup(markers);
        m.fitBounds(group.getBounds(), { padding: [30, 30] });
        setTimeout(() => {
            try {
                m.invalidateSize();
            } catch (e) {}
        }, 300);
    }

    return { map: m, markers, markersById };
}

if (typeof window !== "undefined") {
    window.BPRSMaps = {
        initSimpleMap: createMap,
        normalizePoint,
    };
}

export { createMap, normalizePoint };
