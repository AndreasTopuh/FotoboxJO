import { useNavigate } from 'react-router-dom';

export default function Landing() {
  const navigate = useNavigate();
  return (
    <div className="h-screen flex flex-col items-center justify-center bg-black text-white">
      <h1 className="text-4xl mb-6 font-bold">PhotoBooth Online</h1>
      <button
        className="bg-blue-500 px-6 py-3 rounded-full text-lg hover:bg-blue-600"
        onClick={() => navigate('/payment')}>
        Mulai Sekarang
      </button>
    </div>
  );
}
