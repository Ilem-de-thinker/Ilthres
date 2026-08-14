/**
 * Geolocation helper for AI-powered search.
 * Retrieves the user's current position via the Browser Geolocation API.
 *
 * @returns {Promise<{lat: number, lng: number}>}
 */
export function getCurrentPosition() {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            reject(new Error('Geolocation is not supported by this browser.'));
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                resolve({
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                });
            },
            (error) => {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        reject(new Error('Location permission denied.'));
                        break;
                    case error.POSITION_UNAVAILABLE:
                        reject(new Error('Location information unavailable.'));
                        break;
                    case error.TIMEOUT:
                        reject(new Error('Location request timed out.'));
                        break;
                    default:
                        reject(new Error('An unknown error occurred.'));
                        break;
                }
            },
            {
                enableHighAccuracy: false,
                timeout: 10000,
                maximumAge: 300000, // cache for 5 minutes
            }
        );
    });
}
