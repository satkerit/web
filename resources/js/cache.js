/**
 * Vanilla JS API Cache Helper
 * Caches API responses to reduce network requests and improve performance
 */
class APICache {
    constructor(options = {}) {
        this.prefix = options.prefix || "api_cache_";
        this.defaultTTL = options.defaultTTL || 5 * 60 * 1000; // 5 minutes default TTL
    }

    /**
     * Generate cache key from URL and parameters
     */
    generateKey(url, params = {}) {
        const sortedParams = Object.keys(params)
            .sort()
            .reduce((obj, key) => {
                obj[key] = params[key];
                return obj;
            }, {});

        return `${this.prefix}${url}${JSON.stringify(sortedParams)}`;
    }

    /**
     * Check if cache exists and is still valid
     */
    get(key) {
        try {
            const item = sessionStorage.getItem(key);
            if (!item) return null;

            const parsed = JSON.parse(item);
            if (Date.now() > parsed.expiry) {
                sessionStorage.removeItem(key);
                return null;
            }

            return parsed.data;
        } catch (e) {
            console.error("Cache read error:", e);
            return null;
        }
    }

    /**
     * Store data in cache
     */
    set(key, data, ttl = this.defaultTTL) {
        try {
            const item = {
                data,
                expiry: Date.now() + ttl,
            };
            sessionStorage.setItem(key, JSON.stringify(item));
            return true;
        } catch (e) {
            console.error("Cache write error:", e);
            return false;
        }
    }

    /**
     * Clear all cache entries
     */
    clearAll() {
        Object.keys(sessionStorage).forEach((key) => {
            if (key.startsWith(this.prefix)) {
                sessionStorage.removeItem(key);
            }
        });
    }

    /**
     * Wrapped fetch with cache
     */
    async fetch(url, options = {}, ttl = this.defaultTTL) {
        const cacheKey = this.generateKey(url, options.params || {});
        const cached = this.get(cacheKey);

        if (cached) {
            return cached;
        }

        try {
            const response = await fetch(url, options);
            if (!response.ok)
                throw new Error(`HTTP error! status: ${response.status}`);

            const data = await response.json();
            this.set(cacheKey, data, ttl);
            return data;
        } catch (e) {
            console.error("Fetch error:", e);
            throw e;
        }
    }
}

// Initialize and make available globally
window.apiCache = new APICache();

// Export for module usage
if (typeof module !== "undefined" && module.exports) {
    module.exports = APICache;
}
