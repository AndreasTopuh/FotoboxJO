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
      {/* Container Tengah */}
      <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
        {/* Ilustrasi dan Teks */}
        <div className="relative mb-10">
          <div className="text-5xl font-extrabold drop-shadow-lg">FotoboxJO</div>
        </div>

        {/* Tombol */}
        <button
          className="bg-pink-400 hover:bg-pink-500 transition px-8 py-3 rounded-full text-lg font-semibold shadow-md text-white"
          onClick={() => navigate('/payment')}
        >
          Ketuk Untuk Mulai
        </button>
      </div>
    </div>
  );
}
