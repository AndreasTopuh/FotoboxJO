// Camera.jsx
import { useEffect, useRef } from 'react';

export default function Camera() {
  const videoRef = useRef();

  useEffect(() => {
    navigator.mediaDevices.getUserMedia({ video: true })
      .then(stream => (videoRef.current.srcObject = stream))
      .catch(err => alert('Kamera tidak bisa diakses'));
  }, []);

  return (
    <div className="h-screen flex flex-col items-center justify-center gap-4 bg-black text-white">
      <video ref={videoRef} autoPlay className="rounded-lg border-4 border-white w-2/3 max-w-lg" />
      <button className="bg-green-500 px-6 py-2 rounded-full">Ambil Foto</button>
    </div>
  );
}
