// Debug camera access
console.log('Current URL:', window.location.href);
console.log('Is HTTPS:', window.location.protocol === 'https:');
console.log('MediaDevices available:', !!navigator.mediaDevices);
console.log('getUserMedia available:', !!navigator.mediaDevices?.getUserMedia);

// Test camera access
if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            console.log('Camera access successful!');
            stream.getTracks().forEach(track => track.stop());
        })
        .catch(err => {
            console.error('Camera access failed:', err);
        });
} else {
    console.error('Camera API not available');
}
