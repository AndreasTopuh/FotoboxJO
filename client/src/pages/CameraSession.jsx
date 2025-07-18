import { useEffect, useRef, useState } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';

export default function CameraSession() {
  const videoRef = useRef(null);
  const canvasRef = useRef(null);
  const flashRef = useRef(null);
  const location = useLocation();
  const urlParams = new URLSearchParams(location.search);
  const photoCount = parseInt(urlParams.get('photos')) || 2;
  const [photos, setPhotos] = useState(Array(photoCount).fill(null));
  const [timer, setTimer] = useState(3);
  const [selectedTimer, setSelectedTimer] = useState(3);
  const [currentIdx, setCurrentIdx] = useState(0);
  const [step, setStep] = useState('idle');
  const [filter, setFilter] = useState('none');
  const [isMirrored, setIsMirrored] = useState(false);
  const navigate = useNavigate();

  useEffect(() => {
    navigator.mediaDevices.getUserMedia({ video: true })
      .then(stream => {
        videoRef.current.srcObject = stream;
      })
      .catch(() => alert('Kamera gagal diakses'));
    return () => {
      if (videoRef.current && videoRef.current.srcObject) {
        videoRef.current.srcObject.getTracks().forEach(track => track.stop());
      }
    };
  }, []);

  useEffect(() => {
    if (step === 'countdown' && timer > 0) {
      const interval = setInterval(() => setTimer(t => t - 1), 1000);
      return () => clearInterval(interval);
    } else if (step === 'countdown' && timer === 0) {
      capturePhoto(currentIdx);
    }
  }, [timer, step]);

  const capturePhoto = (index) => {
    const canvas = canvasRef.current;
    const video = videoRef.current;
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    if (isMirrored) {
      ctx.scale(-1, 1);
      ctx.translate(-canvas.width, 0);
    }
    ctx.filter = filter === 'none' ? 'none' : filter;
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    ctx.filter = 'none';
    if (isMirrored) {
      ctx.setTransform(1, 0, 0, 1, 0, 0);
    }
    const data = canvas.toDataURL('image/png');

    // Flash effect
    flashRef.current.style.opacity = '1';
    setTimeout(() => {
      flashRef.current.style.opacity = '0';
    }, 200);

    const updated = [...photos];
    updated[index] = data;
    setPhotos(updated);

    const nextIdx = updated.findIndex(p => p === null);
    if (nextIdx !== -1) {
      setCurrentIdx(nextIdx);
      setTimer(selectedTimer);
      setStep('countdown');
    } else {
      setStep('done');
    }
  };

  const startSession = () => {
    setPhotos(Array(photoCount).fill(null));
    setCurrentIdx(0);
    setTimer(selectedTimer);
    setStep('countdown');
  };

  const handleDone = () => {
    if (step === 'done') {
      navigate('/edit', { state: { photos } });
    }
  };

  const applyFilter = (filterType) => {
    setFilter(filterType);
    videoRef.current.style.filter = filterType === 'none' ? 'none' : filterType;
  };

  const toggleMirror = () => {
    setIsMirrored(!isMirrored);
    videoRef.current.style.transform = isMirrored ? 'scaleX(1)' : 'scaleX(-1)';
  };

  const handleKeyPress = (e) => {
    if (e.code === 'Space' && step === 'idle') {
      startSession();
    }
  };

  useEffect(() => {
    window.addEventListener('keydown', handleKeyPress);
    return () => window.removeEventListener('keydown', handleKeyPress);
  }, [step]);

  return (
    <main id="main-section" className="flex flex-col items-center p-4 min-h-screen bg-gradient-to-r from-[#FF8679] via-[#F2AAAE] to-[#F6D3AD]">
      <div className="canvas-centered max-w-6xl w-full">
        <p id="progressCounter" className="text-4xl font-bold text-gray-800 mb-4">
          {photos.filter(p => p !== null).length}/{photoCount}
        </p>
        <div id="add-ons-container" className="flex gap-4 mb-4">
          <button className="bg-gray-200 p-2 rounded flex items-center gap-2 hover:bg-gray-300">
            <img src="/assets/icons/upload-icon.png" className="w-6 h-6" alt="upload" />
            Upload Image
          </button>
          <select
            id="timerOptions"
            className="p-2 rounded bg-gray-200 text-gray-800"
            value={selectedTimer}
            onChange={(e) => setSelectedTimer(parseInt(e.target.value))}
          >
            <option value="3">3s</option>
            <option value="5">5s</option>
            <option value="10">10s</option>
          </select>
        </div>
        <section className="camera-container flex flex-col md:flex-row gap-5 justify-center">
          <div id="videoContainer" className="relative flex items-center justify-center bg-black rounded-3xl border border-black w-full max-w-[1000px]">
            <video
              ref={videoRef}
              autoPlay
              playsInline
              className="w-full rounded-3xl border-4 border-black"
            />
            <div
              ref={flashRef}
              id="flash"
              className="fixed inset-0 bg-white opacity-0 pointer-events-none transition-opacity duration-200"
            />
            <div
              id="fullscreenMessage"
              className={`absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-2xl font-bold bg-black bg-opacity-60 px-6 py-4 rounded-xl transition-opacity duration-500 ${step === 'idle' ? 'opacity-100' : 'opacity-0'}`}
            >
              Press SPACE to Start
            </div>
            <div
              id="countdownText"
              className={`absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-5xl font-bold bg-pink-400 bg-opacity-20 p-6 rounded-full ${step === 'countdown' ? 'block animate-bounceScale' : 'hidden'}`}
            >
              {timer}
            </div>
            <button className="absolute bottom-4 right-4">
              <img src="/assets/fullScreen3.png" className="w-8 h-8" alt="full screen" />
            </button>
          </div>
          <div id="photoContainer" className="flex flex-col md:flex-row gap-2 items-center justify-center w-full md:w-auto">
            {photos.map((photo, i) => (
              <img
                key={i}
                src={photo || '/assets/placeholder.png'}
                alt={`Photo ${i + 1}`}
                className="w-[100px] h-[110.9px] object-cover rounded-xl border-2 border-black"
              />
            ))}
          </div>
        </section>
        <div className="startBtn-container flex flex-col items-center mt-4">
          <div className="filter-container flex gap-2 mb-2">
            {[
              { id: 'vintageFilterId', filter: 'sepia(0.4)' },
              { id: 'grayFilterId', filter: 'grayscale(1)' },
              { id: 'smoothFilterId', filter: 'blur(1px)' },
              { id: 'bnwFilterId', filter: 'contrast(1.2) grayscale(1)' },
              { id: 'sepiaFilterId', filter: 'sepia(0.8)' },
              { id: 'normalFilterId', filter: 'none' },
            ].map(({ id, filter }) => (
              <button
                key={id}
                onClick={() => applyFilter(filter)}
                className="w-10 h-10 rounded-full bg-gray-300 hover:bg-gray-400"
                style={{ filter }}
              />
            ))}
            <button onClick={toggleMirror} className="w-10 h-10 rounded-full bg-gray-300 hover:bg-gray-400">
              <img src="/assets/mirror-icon.svg" alt="mirror" className="w-6 h-6 mx-auto" />
            </button>
          </div>
          <h3 className="options-label text-lg font-semibold mb-2">Choose a filter</h3>
          <div className="start-done-btn flex gap-5">
            <button
              onClick={startSession}
              className="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow-lg"
              disabled={step !== 'idle'}
            >
              START
            </button>
            <button
              onClick={handleDone}
              className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-lg"
              disabled={step !== 'done'}
            >
              DONE
            </button>
          </div>
        </div>
        <canvas ref={canvasRef} className="hidden" />
      </div>
    </main>
  );
}
