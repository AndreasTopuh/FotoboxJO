// Landing.jsx
import { useNavigate } from 'react-router-dom';

export default function Landing() {
  const navigate = useNavigate();
  return (
    <div className="h-screen flex flex-col justify-center items-center bg-black text-white">
      <h1 className="text-4xl font-bold mb-6">Welcome to PhotoBooth Online!</h1>
      <button
        onClick={() => navigate('/package')}
        className="bg-pink-500 px-6 py-3 rounded-full text-lg hover:bg-pink-600">
        Mulai Sekarang
      </button>
    </div>
  );
}
