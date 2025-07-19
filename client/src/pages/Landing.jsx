import { useNavigate } from 'react-router-dom';
import bgImg from '../assets/grid-bg.png';

export default function Landing() {
  const navigate = useNavigate();

  return (
    <div
      className="h-screen flex flex-col items-center justify-center text-white relative"
      style={{
        backgroundImage: `url(${bgImg})`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
      }}
    >
      {/* Dekorasi Star 💫 */}
      <div className="absolute top-10 left-10 text-yellow-300 text-xl animate-pulse">✨</div>
      <div className="absolute bottom-10 right-10 text-yellow-300 text-xl animate-pulse">✨</div>

      {/* Logo/Ilustrasi Tengah */}
      <div className="flex flex-col items-center mb-10">
        <div className="relative">
          <div className="absolute -top-4 -left-4 bg-white text-black px-2 py-1 text-xs rounded-full shadow-md animate-bounce">
            Selamat Datang 🎉
          </div>
        </div>
      </div>

      {/* Tombol */}
      <button
        className="bg-blue-500 hover:bg-blue-600 transition px-8 py-3 rounded-full text-lg font-semibold shadow-lg"
        onClick={() => navigate('/payment')}
      >
        Ketuk Untuk Mulai 📸
      </button>
    </div>
  );
}
