import { useNavigate } from 'react-router-dom';

export default function Landing() {
  const navigate = useNavigate();

  return (
    <div className="relative w-screen h-screen overflow-hidden text-white bg-gray-900">
      <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
        <div className="backdrop-blur-md bg-white/10 border border-white/20 rounded-2xl p-10 text-center shadow-xl">
          <div className="text-4xl lg:text-5xl xl:text-6xl font-extrabold drop-shadow-lg mb-8">
            FotoboxJO
          </div>
          <button
            className="bg-pink-400 hover:bg-pink-500 transition px-6 py-3 lg:px-8 lg:py-4 xl:px-10 xl:py-5 rounded-full text-base lg:text-lg xl:text-xl font-semibold shadow-md text-white"
            onClick={() => navigate('/selectpayment')}
          >
            Ketuk Untuk Mulai
          </button>
        </div>
      </div>
    </div>
  );
}
