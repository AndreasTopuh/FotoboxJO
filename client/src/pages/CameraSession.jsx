// import { useEffect, useRef, useState } from 'react';
// import RetakeIcon from '../assets/retake.svg';


// export default function CameraSession() {
//   const videoRef = useRef(null);
//   const canvasRef = useRef(null);
//   const [photos, setPhotos] = useState([null, null, null, null]);
//   const [timer, setTimer] = useState(3);
//   const [globalTimer, setGlobalTimer] = useState(420);
//   const [currentIdx, setCurrentIdx] = useState(0);
//   const [step, setStep] = useState('idle');
//   const [expired, setExpired] = useState(false);

//   useEffect(() => {
//     navigator.mediaDevices.getUserMedia({ video: true }).then(stream => {
//       videoRef.current.srcObject = stream;
//     }).catch(() => alert('Kamera gagal diakses'));
//   }, []);

//   useEffect(() => {
//     if (step === 'countdown' && timer > 0) {
//       const interval = setInterval(() => setTimer(t => t - 1), 1000);
//       return () => clearInterval(interval);
//     } else if (step === 'countdown' && timer === 0) {
//       capturePhoto(currentIdx);
//     }
//   }, [timer, step]);

//   useEffect(() => {
//     if (step !== 'idle' && globalTimer > 0) {
//       const interval = setInterval(() => setGlobalTimer(t => t - 1), 1000);
//       return () => clearInterval(interval);
//     } else if (globalTimer === 0) {
//       setExpired(true);
//       setStep('idle');
//     }
//   }, [globalTimer, step]);

//   const startSession = () => {
//     setPhotos([null, null, null, null]);
//     setCurrentIdx(0);
//     setTimer(3);
//     setGlobalTimer(420);
//     setExpired(false);
//     setStep('countdown');
//   };

//   const capturePhoto = (index) => {
//     const canvas = canvasRef.current;
//     const video = videoRef.current;
//     canvas.width = video.videoWidth;
//     canvas.height = video.videoHeight;
//     const ctx = canvas.getContext('2d');
//     ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
//     const data = canvas.toDataURL('image/png');

//     const updated = [...photos];
//     updated[index] = data;
//     setPhotos(updated);

//     const nextIdx = updated.findIndex(p => p === null);
//     if (nextIdx !== -1) {
//       setCurrentIdx(nextIdx);
//       setTimer(3);
//       setStep('countdown');
//     } else {
//       setStep('done');
//     }
//   };

//   const retake = (index) => {
//     setCurrentIdx(index);
//     setTimer(3);
//     setStep('countdown');
//   };

//   return (
//     <>
//       {/* Kamera & tombol */}
//       <div className="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center justify-center">
//         <video ref={videoRef} autoPlay className="rounded-lg w-96 max-w-full shadow-lg border-4 border-white" />

//         {step === 'countdown' && (
//           <div className="absolute text-white text-7xl font-bold bg-black bg-opacity-60 px-8 py-3 rounded-full mt-[-100px]">
//             {timer}
//           </div>
//         )}

//         {expired && (
//           <div className="absolute top-10 bg-red-600 text-white px-6 py-3 rounded-xl shadow-lg text-xl animate-pulse">
//             ⏳ Waktumu sudah habis!
//           </div>
//         )}

//         <div className="mt-4">
//           {step === 'idle' || step === 'done' ? (
//             <button
//               onClick={step === 'done' ? () => window.location.href = "/edit" : startSession}
//               className="bg-green-600 hover:bg-green-700 text-white px-6 py-3 text-lg rounded-full shadow-lg"
//               disabled={expired}
//             >
//               {step === 'done' ? 'Lanjut ke Edit' : 'Mulai'}
//             </button>
//           ) : null}
//         </div>

//         <div className="mt-2 text-gray-700 font-semibold">
//           Sisa waktu: {Math.floor(globalTimer / 60)}:{String(globalTimer % 60).padStart(2, '0')}
//         </div>

//         <canvas ref={canvasRef} className="hidden" />
//       </div>

//       {/* Preview */}
//       <div className="absolute right-6 top-1/2 -translate-y-1/2 w-72 p-4 bg-transparent">
//         <h2 className="text-xl font-bold mb-3 text-center text-white drop-shadow">Preview</h2>
//         <div className="grid grid-cols-2 gap-3">
//           {photos.map((photo, i) => (
//             <div key={i} className="relative">
//               {photo ? (
//                 <>
//                   <img src={photo} alt={`Foto ${i + 1}`} className="rounded-lg border-2 border-white shadow" />
//                   <button
//                     onClick={() => retake(i)}
//                     className="absolute top-1 right-1 bg-white rounded-full shadow p-1"
//                     title="Ulangi Foto"
//                   >
//                     <img src={RetakeIcon} alt="Retake" className="w-5 h-5" />
//                   </button>

//                 </>
//               ) : (
//                 <div className="aspect-square w-full bg-blue-400 opacity-40 rounded-lg border-4 border-white" />
//               )}
//             </div>
//           ))}
//         </div>
//       </div>

//       {/* ICON STYLE */}
//       <style>
//         {`
//           .material-symbols-outlined {
//             font-variation-settings:
//               'FILL' 0,
//               'wght' 400,
//               'GRAD' 0,
//               'opsz' 24;
//           }
//         `}
//       </style>
//     </>
//   );
// }
import { useEffect, useRef, useState } from 'react';
import RetakeIcon from '../assets/retake.svg';

export default function CameraSession() {
  const videoRef = useRef(null);
  const canvasRef = useRef(null);
  const urlParams = new URLSearchParams(window.location.search);
  const photoCount = parseInt(urlParams.get('photos')) || 4;
  const [photos, setPhotos] = useState(Array(photoCount).fill(null));
  const [timer, setTimer] = useState(3);
  const [globalTimer, setGlobalTimer] = useState(420);
  const [currentIdx, setCurrentIdx] = useState(0);
  const [step, setStep] = useState('idle');
  const [expired, setExpired] = useState(false);

  useEffect(() => {
    navigator.mediaDevices.getUserMedia({ video: true }).then(stream => {
      videoRef.current.srcObject = stream;
    }).catch(() => alert('Kamera gagal diakses'));
  }, []);

  useEffect(() => {
    if (step === 'countdown' && timer > 0) {
      const interval = setInterval(() => setTimer(t => t - 1), 1000);
      return () => clearInterval(interval);
    } else if (step === 'countdown' && timer === 0) {
      capturePhoto(currentIdx);
    }
  }, [timer, step]);

  useEffect(() => {
    if (step !== 'idle' && globalTimer > 0) {
      const interval = setInterval(() => setGlobalTimer(t => t - 1), 1000);
      return () => clearInterval(interval);
    } else if (globalTimer === 0) {
      setExpired(true);
      setStep('idle');
    }
  }, [globalTimer, step]);

  const startSession = () => {
    setPhotos(Array(photoCount).fill(null));
    setCurrentIdx(0);
    setTimer(3);
    setGlobalTimer(420);
    setExpired(false);
    setStep('countdown');
  };

  const capturePhoto = (index) => {
    const canvas = canvasRef.current;
    const video = videoRef.current;
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    const data = canvas.toDataURL('image/png');

    const updated = [...photos];
    updated[index] = data;
    setPhotos(updated);

    const nextIdx = updated.findIndex(p => p === null);
    if (nextIdx !== -1) {
      setCurrentIdx(nextIdx);
      setTimer(3);
      setStep('countdown');
    } else {
      setStep('done');
    }
  };

  const retake = (index) => {
    setCurrentIdx(index);
    setTimer(3);
    setStep('countdown');
  };

  const gridLayout = photoCount === 8 ? 'grid-cols-2 grid-rows-4' : photoCount === 6 ? 'grid-cols-2 grid-rows-3' : 'grid-cols-2 grid-rows-2';

  return (
    <>
      {/* Kamera & tombol */}
      <div className="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center justify-center">
        <video ref={videoRef} autoPlay className="rounded-lg w-96 max-w-full shadow-lg border-4 border-white" />

        {step === 'countdown' && (
          <div className="absolute text-white text-7xl font-bold bg-black bg-opacity-60 px-8 py-3 rounded-full mt-[-100px]">
            {timer}
          </div>
        )}

        {expired && (
          <div className="absolute top-10 bg-red-600 text-white px-6 py-3 rounded-xl shadow-lg text-xl animate-pulse">
            ⏳ Waktumu sudah habis!
          </div>
        )}

        <div className="mt-4">
          {step === 'idle' || step === 'done' ? (
            <button
              onClick={step === 'done' ? () => window.location.href = "/edit" : startSession}
              className="bg-green-600 hover:bg-green-700 text-white px-6 py-3 text-lg rounded-full shadow-lg"
              disabled={expired}
            >
              {step === 'done' ? 'Lanjut ke Edit' : 'Mulai'}
            </button>
          ) : null}
        </div>

        <div className="mt-2 text-gray-700 font-semibold">
          Sisa waktu: {Math.floor(globalTimer / 60)}:{String(globalTimer % 60).padStart(2, '0')}
        </div>

        <canvas ref={canvasRef} className="hidden" />
      </div>

      {/* Preview */}
      <div className="absolute right-6 top-1/2 -translate-y-1/2 w-72 p-4 bg-transparent">
        <h2 className="text-xl font-bold mb-3 text-center text-white drop-shadow">Preview</h2>
        <div className={`grid ${gridLayout} gap-2`}>
          {photos.map((photo, i) => (
            <div key={i} className="relative">
              {photo ? (
                <>
                  <img src={photo} alt={`Foto ${i + 1}`} className="rounded-lg border-2 border-white shadow w-full h-full object-cover" />
                  <button
                    onClick={() => retake(i)}
                    className="absolute top-1 right-1 bg-white rounded-full shadow p-1"
                    title="Ulangi Foto"
                  >
                    <img src={RetakeIcon} alt="Retake" className="w-5 h-5" />
                  </button>
                </>
              ) : (
                <div className="aspect-square w-full bg-blue-400 opacity-40 rounded-lg border-4 border-white" />
              )}
            </div>
          ))}
        </div>
      </div>

      {/* ICON STYLE */}
      <style>
        {`
          .material-symbols-outlined {
            font-variation-settings:
              'FILL' 0,
              'wght' 400,
              'GRAD' 0,
              'opsz' 24;
          }
        `}
      </style>
    </>
  );
}