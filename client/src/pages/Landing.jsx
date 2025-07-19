import { useNavigate } from 'react-router-dom';
import bgImg from '../assets/grid-bg.png';

export default function Landing() {
  const navigate = useNavigate();

  return (
    <div
      className="relative w-screen h-screen overflow-hidden text-white"
      style={{
        backgroundImage: `url(${bgImg})`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
      }}
    >
      {/* Dekorasi Star 💫 */}
      <div className="absolute top-10 left-10 text-yellow-300 text-xl animate-pulse">✨</div>
      <div className="absolute bottom-10 right-10 text-yellow-300 text-xl animate-pulse">✨</div>

      {/* Container Tengah */}
      <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
        {/* Ilustrasi dan Teks */}
        <div className="relative mb-10">
          <div className="absolute -top-4 -left-4 bg-white text-black px-2 py-1 text-xs rounded-full shadow-md animate-bounce">
            Selamat Datang 🎉
          </div>
          <div className="text-5xl font-extrabold drop-shadow-lg">Shoot Spot</div>
        </div>

        {/* Tombol */}
        <button
          className="bg-pink-400 hover:bg-pink-500 transition px-8 py-3 rounded-full text-lg font-semibold shadow-md text-white"
          onClick={() => navigate('/payment')}
        >
          Ketuk Untuk Mulai 📸
        </button>
      </div>
    </div>
  );
}
