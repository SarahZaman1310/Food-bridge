import { Link } from "react-router-dom";
import "./Login.css";

function Login() {
  return (
    <div className="login-page">
      <h1>Login Page</h1>

      <Link to="/" className="back-button">
        ← Back to Home
      </Link>
    </div>
  );
}

export default Login;