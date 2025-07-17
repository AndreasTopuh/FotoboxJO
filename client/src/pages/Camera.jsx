import { useEffect, useRef } from 'react';

export default function Camera() {
  const videoRef = useRef();

  useEffect(() => {
    navigator.mediaDevices.getUserMedia({ video: true })
      .then(stream => (videoRef.current.srcObject = stream))
      .catch(() => alert('Kamera gagal diakses'));
  }, []);

  return (
    <div className="flex flex-col items-center justify-center h-screen bg-black text-white">
      <video ref={videoRef} autoPlay className="rounded-lg w-2/3 max-w-lg border-4" />
      <button className="mt-4 bg-green-500 px-6 py-2 rounded-full">Ambil Foto</button>
    </div>
  );
}
