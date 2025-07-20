import { useLocation, useNavigate } from 'react-router-dom';

export default function FrameTemplate() {
  const location = useLocation();
  const urlParams = new URLSearchParams(location.search);
  const layout = urlParams.get('layout');
  const photoCount = parseInt(urlParams.get('photos')) || 2;
  const navigate = useNavigate();

  const handleContinue = () => {
    navigate(`/camera?layout=${layout}&photos=${photoCount}`);
  };

  return (
    <div className="p-6 text-center bg-blue-100 min-h-screen">
      <h2 className="text-3xl font-bold mb-6">Pilih Frame yang Berada di Layout</h2>
      <div className="flex flex-col md:flex-row justify-center items-center gap-6">
        <div className="w-full md:w-1/2">
          {/* Placeholder for template selection (currently only one option) */}
          <p className="text-lg">Pilih template untuk layout Anda.</p>
        </div>
        <div className="w-full md:w-1/2">
          <img
            src="/frame/layout/frameLayout1/frame1layout1.png"
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