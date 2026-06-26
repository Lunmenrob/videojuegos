<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-content">
            <div class="footer-logo">
                <a href="index.php" class="logo-link">
                    <img src="https://playstation-stores.com/wp-content/uploads/2020/11/Playstation-Wholesale-Store-Logo-White-300x230.png" alt="PlayStation Logo" class="playstation-logo-footer">
                </a>
            </div>
            <div class="footer-links">
                <a href="index.php" class="footer-link">Inicio</a>
                <a href="#" class="footer-link">Soporte</a>
                <a href="#" class="footer-link">Términos</a>
                <a href="#" class="footer-link">Privacidad</a>
            </div>
            <div class="footer-social">
                <a href="https://www.facebook.com/PlayStationES" target="_blank" class="social-link">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com/playstationes" target="_blank" class="social-link">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://www.instagram.com/playstationes/" target="_blank" class="social-link">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://www.youtube.com/user/PlayStationES" target="_blank" class="social-link">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="footer-copyright"> 2026 Gestor de Videojuegos PS4/PS5. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

<style>
    .site-footer {
        background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        border-top: 2px solid #0070d1;
        padding: 2rem 0;
        margin-top: 0;
    }

    .footer-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 2rem;
    }

    .footer-logo {
        display: flex;
        align-items: center;
    }

    .logo-link {
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .playstation-logo-footer {
        height: 40px;
        width: auto;
        transition: transform 0.3s ease;
    }

    .logo-link:hover .playstation-logo-footer {
        transform: scale(1.05);
    }

    .footer-links {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .footer-link {
        color: #ffffff;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .footer-link:hover {
        color: #0070d1;
    }

    .footer-social {
        display: flex;
        gap: 1rem;
    }

    .social-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        color: #ffffff;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .social-link:hover {
        background: #0070d1;
        transform: translateY(-2px);
    }

    .footer-bottom {
        text-align: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .footer-copyright {
        color: #ffffff;
        font-size: 0.85rem;
        margin: 0;
    }

    @media (max-width: 768px) {
        .footer-content {
            flex-direction: column;
            text-align: center;
        }

        .footer-links {
            flex-direction: column;
            gap: 1rem;
        }

        .footer-social {
            justify-content: center;
        }

        .playstation-logo-footer {
            height: 35px;
        }
    }
</style>