import { useNavigate } from 'react-router-dom';

export default function Landing() {
  const navigate = useNavigate();

  return (
    <div className="relative w-screen h-screen overflow-hidden text-white">
      <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
        <div className="w-[90vw] max-w-3xl backdrop-blur-md bg-white/10 border border-white/20 rounded-2xl px-10 py-16 text-center shadow-2xl">
          <div className="text-5xl lg:text-6xl font-extrabold drop-shadow-lg mb-10">
            FotoboxJO
          </div>
          <button
            className="bg-pink-400 hover:bg-pink-500 transition px-8 py-4 lg:px-10 lg:py-5 rounded-full text-lg lg:text-xl font-semibold shadow-md text-white"
            onClick={() => navigate('/selectpayment')}
          >
            Ketuk Untuk Mulai
          </button>
        </div>
      </div>
    </div>
  );
}
