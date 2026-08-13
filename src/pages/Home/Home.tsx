import { Link } from "react-router-dom";
import "./Home.css";

function Home() {
  return (
    <div className="home-page">
      <h1>FoodBridge</h1>

      <Link to="/login" className="login-button">
        Login
      </Link>
    </div>
  );
}

export default Home;