import { Link, useNavigate } from "react-router-dom";
import "./Auth.css";

function Login() {
  const navigate = useNavigate();

  const handleLogin = (e: React.FormEvent) => {
    e.preventDefault();

    // Dummy login for now
    // Later this will connect to Laravel authentication
    navigate("/donor");
  };

  return (
    <div className="auth-page">
      <div className="auth-card">
        <div className="auth-brand">
          <div className="auth-logo">🌿</div>
          <div>
            <h1>FoodBridge</h1>
            <span>Food · People · Impact</span>
          </div>
        </div>

        <div className="auth-heading">
          <p>WELCOME BACK</p>
          <h2>Log in to FoodBridge</h2>
          <span>
            Continue your journey of turning surplus food into support.
          </span>
        </div>

        <form onSubmit={handleLogin}>
          <div className="form-group">
            <label>Email address</label>
            <input
              type="email"
              placeholder="Enter your email"
              required
            />
          </div>

          <div className="form-group">
            <label>Password</label>
            <input
              type="password"
              placeholder="Enter your password"
              required
            />
          </div>

          <div className="form-options">
            <label className="remember">
              <input type="checkbox" />
              Remember me
            </label>

            <a href="#">Forgot password?</a>
          </div>

          <button type="submit" className="auth-button">
            Log in
          </button>
        </form>

        <div className="auth-divider">
          <span>OR</span>
        </div>

        <p className="auth-switch">
          Don't have an account?{" "}
          <Link to="/signup">Create an account</Link>
        </p>

        <Link to="/" className="back-home">
          ← Back to FoodBridge
        </Link>
      </div>

      <div className="auth-image">
        <div className="auth-overlay">
          <span>FOOD · PEOPLE · IMPACT</span>
          <h2>Good food deserves a second chance.</h2>
          <p>
            Together, we can connect surplus food with people and communities
            who need it.
          </p>
        </div>
      </div>
    </div>
  );
}

export default Login;