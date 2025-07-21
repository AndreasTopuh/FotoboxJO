import { useLocation, useNavigate, useEffect, useState } from 'react-router-dom';

export default function FrameTemplate() {
  const location = useLocation();
  const urlParams = new URLSearchParams(location.search);
  const layout = urlParams.get('layout'); // e.g., /frame/layout/finallayout1.png
  const photoCount = parseInt(urlParams.get('photos')) || 2;
  const navigate = useNavigate();
  const [selectedFrame, setSelectedFrame] = useState('');

  // Determine the selected frame based on photo count when the component mounts
  useEffect(() => {
    let frame;
    switch (photoCount) {
      case 2:
        frame = '/frame/layout/frameLayout1/frame1layout1.png';
        break;
      case 4:
        frame = '/frame/layout/frameLayout2/frame2layout1.png';
        break;
      case 5:
        frame = '/frame/layout/frameLayout5/frame5layout1.png';
        break;
      case 6:
        frame = '/frame/layout/frameLayout3/frame3layout1.png';
        break;
      case 8:
        frame = '/frame/layout/frameLayout4/frame4layout1.png';
        break;
      default:
        frame = '/frame/layout/frameLayout1/frame1layout1.png';
    }
    setSelectedFrame(frame);
  }, [photoCount]);

  const handleContinue = () => {
    console.log('Navigating to camera with layout:', selectedFrame);
    navigate(`/camera?layout=${encodeURIComponent(selectedFrame)}&photos=${photoCount}`);
  };

  return (
    <div className="p-6 text-center bg-blue-100 min-h-screen">
      <h2 className="text-3xl font-bold mb-6">Pilih Frame yang Berada di Layout</h2>
      <div className="flex flex-col md:flex-row justify-center items-center gap-6">
        <div className="w-full md:w-1/2">
          <p className="text-lg">Pilih template untuk layout Anda.</p>
        </div>
        <div className="w-full md:w-1/2">
          <img
            src={selectedFrame || layout}
            alt="Selected Template"
            className="w-full max-w-md mx-auto border-4 border-purple-500 rounded-lg"
          />
          <button
            onClick={handleContinue}
            className="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-lg"
          >
            LANJUT
          </button>
        </div>
      </div>
    </div>
  );
}