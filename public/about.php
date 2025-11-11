<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Về iCheck - Trợ Lý Kiểm Duyệt Nội Dung</title>
    <!--css-->
    <link rel="stylesheet" href="./assets/style.css">
    <!-- google font link-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Roboto:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            overflow-x: hidden;
        }

        /* Header */
        header {
            background: linear-gradient(135deg, #0f2557 0%, #1a4d8f 100%);
            color: white;
            padding: 80px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,144C960,149,1056,139,1152,128C1248,117,1344,107,1392,101.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }

        header h1 {
            font-size: 3em;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            animation: fadeInDown 1s ease-out;
        }

        header p {
            font-size: 1.3em;
            position: relative;
            z-index: 1;
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        /* Navigation */
        nav {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            padding: 15px;
        }

        nav li {
            margin: 0 20px;
        }

        nav a {
            text-decoration: none;
            color: #0f2557;
            font-weight: 600;
            transition: color 0.3s;
            padding: 5px 10px;
        }

        nav a:hover {
            color: #2196F3;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Section Styles */
        section {
            padding: 60px 20px;
            animation: fadeIn 1s ease-out;
        }

        section:nth-child(even) {
            background: #f8f9fa;
        }

        section h2 {
            color: #0f2557;
            font-size: 2.5em;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }

        section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #2196F3, #0f2557);
            border-radius: 2px;
        }

        /* About Section */
        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
            margin-top: 40px;
        }

        .about-text {
            font-size: 1.1em;
            line-height: 1.8;
        }

        .about-text h3 {
            color: #2196F3;
            margin: 20px 0 10px;
            font-size: 1.5em;
        }

        .about-image {
            text-align: center;
        }

        .about-image img {
            max-width: 100%;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: float 3s ease-in-out infinite;
        }

        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            text-align: center;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #2196F3, #0f2557);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2em;
            color: white;
        }

        .feature-card h3 {
            color: #0f2557;
            margin-bottom: 15px;
            font-size: 1.4em;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        /* Benefits */
        .benefits-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }

        .benefit-item {
            background: white;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #2196F3;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .benefit-item:hover {
            transform: translateX(10px);
        }

        .benefit-item h4 {
            color: #0f2557;
            margin-bottom: 10px;
            font-size: 1.2em;
        }

        /* Stats Section */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .stat-card {
            text-align: center;
            padding: 40px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 3em;
            color: #2196F3;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #666;
            font-size: 1.1em;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            header h1 {
                font-size: 2em;
            }

            header p {
                font-size: 1em;
            }

            .about-content {
                grid-template-columns: 1fr;
            }

            section h2 {
                font-size: 2em;
            }

            nav ul {
                flex-direction: column;
                align-items: center;
            }

            nav li {
                margin: 10px 0;
            }
        }
    </style>
</head>

<body>
    <header>
        <h1>iCheck</h1>
        <p>Trợ Lý Kiểm Duyệt Nội Dung Thông Minh</p>
    </header>

    <nav>
        <ul>
            <li><a href="#about">Về Chúng Tôi</a></li>
            <li><a href="#features">Tính Năng</a></li>
            <li><a href="#benefits">Lợi Ích</a></li>
            <li><a href="#stats">Thống Kê</a></li>
            <li><a href="#contact">Liên Hệ</a></li>
        </ul>
    </nav>

    <section id="about">
        <div class="container">
            <h2>Về iCheck</h2>
            <div class="about-content">
                <div class="about-text">
                    <p>
                        iCheck là trợ lý kiểm duyệt sử dụng AI và luật tiếng Việt giúp nhà trường, doanh nghiệp và cộng đồng <strong>phát hiện sớm nội dung tiêu cực</strong>: tục tĩu, miệt thị, kích động, lừa đảo, dương link độc hại... trên fanpage và bình luận.
                    </p>

                    <h3>🎯 Sứ Mệnh</h3>
                    <p>
                        Chúng tôi muốn bạn an tâm truyền thông, còn việc "soi rủi ro" cứ để iCheck lo. Hệ thống tự động thu thập bài viết & bình luận, chấm điểm rủi ro theo thời gian thực, cảnh báo tức thì và lưu vết xử lý.
                    </p>

                    <h3>⚡ Hiệu Quả</h3>
                    <p>
                        Mọi thao tác đều được lưu vết, dữ liệu thuốc về bạn, và có thể tùy chỉnh danh sách từ nhạy cảm cho phù hợp bối cảnh giáo dục.
                    </p>
                </div>
                <div class="about-image">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 300'%3E%3Crect fill='%230f2557' width='400' height='300'/%3E%3Ccircle cx='200' cy='150' r='80' fill='%232196F3' opacity='0.3'/%3E%3Cpath d='M200,100 L220,130 L260,130 L230,155 L245,190 L200,165 L155,190 L170,155 L140,130 L180,130 Z' fill='white'/%3E%3Ctext x='200' y='250' font-family='Arial' font-size='24' fill='white' text-anchor='middle'%3EiCheck%3C/text%3E%3C/svg%3E" alt="iCheck Logo">
                </div>
            </div>
        </div>
    </section>

    <section id="features">
        <div class="container">
            <h2>Tính Năng Nổi Bật</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🤖</div>
                    <h3>AI Thông Minh</h3>
                    <p>Sử dụng trí tuệ nhân tạo và luật tiếng Việt để phát hiện nội dung tiêu cực với độ chính xác cao</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Thời Gian Thực</h3>
                    <p>Tự động thu thập và chấm điểm rủi ro theo thời gian thực, cảnh báo tức thì khi phát hiện vấn đề</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🛡️</div>
                    <h3>Bảo Vệ Toàn Diện</h3>
                    <p>Phát hiện tục tĩu, miệt thị, kích động, lừa đảo, đường link độc hại trên fanpage và bình luận</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Lưu Vết Xử Lý</h3>
                    <p>Mọi thao tác được lưu vết, dữ liệu thuộc về bạn với 1 lần bấm</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔧</div>
                    <h3>Tùy Chỉnh Linh Hoạt</h3>
                    <p>Có thể tùy chỉnh danh sách từ nhạy cảm cho phù hợp bối cảnh giáo dục</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔗</div>
                    <h3>API & Webhook</h3>
                    <p>Tích hợp dễ dàng với hệ thống của bạn thông qua API và Webhook</p>
                </div>
            </div>
        </div>
    </section>

    <section id="benefits">
        <div class="container">
            <h2>Lợi Ích Khi Sử Dụng iCheck</h2>
            <div class="benefits-list">
                <div class="benefit-item">
                    <h4>🎯 Tiết Kiệm Thời Gian</h4>
                    <p>Tự động hóa quy trình kiểm duyệt, tiết kiệm hàng giờ làm việc mỗi ngày</p>
                </div>
                <div class="benefit-item">
                    <h4>🛡️ An Tâm Truyền Thông</h4>
                    <p>Bảo vệ thương hiệu và cộng đồng khỏi nội dung tiêu cực 24/7</p>
                </div>
                <div class="benefit-item">
                    <h4>📈 Nâng Cao Uy Tín</h4>
                    <p>Duy trì môi trường tích cực, nâng cao uy tín và hình ảnh thương hiệu</p>
                </div>
                <div class="benefit-item">
                    <h4>💰 Tiết Kiệm Chi Phí</h4>
                    <p>Giảm chi phí nhân sự và quản lý so với kiểm duyệt thủ công</p>
                </div>
                <div class="benefit-item">
                    <h4>🎓 Phù Hợp Giáo Dục</h4>
                    <p>Đặc biệt tối ưu cho môi trường nhà trường và giáo dục</p>
                </div>
                <div class="benefit-item">
                    <h4>🌐 Hỗ Trợ Đa Nền Tảng</h4>
                    <p>Hoạt động trên Facebook, các nền tảng mạng xã hội khác</p>
                </div>
            </div>
        </div>
    </section>

    <section id="stats">
        <div class="container">
            <h2>Số Liệu Ấn Tượng</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Hoạt Động Liên Tục</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">365</div>
                    <div class="stat-label">Ngày Trong Năm</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">99%</div>
                    <div class="stat-label">Độ Chính Xác</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">
                        < 1s</div>
                            <div class="stat-label">Thời Gian Phản Hồi</div>
                    </div>
                </div>
            </div>
    </section>

    <!--FOOTER-->
    <footer class="footer" id="contact">
        <div class="footer-top section">
            <div class="container">
                <div class="footer-brand">
                    <a href="#" class="logo">iCheck</a>
                    <p class="footer-text">
                        iCheck là trợ lý kiểm duyệt nội dung cho fanpage và cộng đồng. Hệ thống tự động thu thập
                        bài viết & bình luận, chấm điểm rủi ro theo thời gian thực, cảnh báo tức thì và lưu vết xử lý.
                        Giúp bạn an tâm truyền thông – việc “soi rủi ro” cứ để iCheck lo.
                    </p>
                    <div class="schedule">
                        <div class="schedule-icon">
                            <ion-icon name="time-outline"></ion-icon>
                        </div>
                        <span class="span">
                            24 X 7:<br>
                            365 Days
                        </span>
                    </div>
                </div>
                <ul class="footer-list">
                    <li>
                        <p class="footer-list-title">Other Links</p>
                    </li>
                    <li>
                        <a href="#" class="footer-link">
                            <ion-icon name="add-outline"></ion-icon>
                            <span class="span">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="#analyze" class="footer-link">
                            <ion-icon name="add-outline"></ion-icon>
                            <span class="span">Analyze</span>
                        </a>
                    </li>
                    <li>
                        <a href="#contact" class="footer-link">
                            <ion-icon name="add-outline"></ion-icon>
                            <span class="span">Contact</span>
                        </a>
                    </li>
                    <li>
                        <a href="http://localhost/negative-info-guard/php/admin/login.php" class="footer-link">
                            <ion-icon name="add-outline"></ion-icon>
                            <span class="span">Login</span>
                        </a>
                    </li>
                </ul>
                <ul class="footer-list">
                    <li>
                        <p class="footer-list-title">Our Services</p>
                    </li>
                    <li>
                        <a href="#" class="footer-link">
                            <ion-icon name="add-outline"></ion-icon>
                            <span class="span">Thu thập post & comment tự động</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="footer-link">
                            <ion-icon name="add-outline"></ion-icon>
                            <span class="span">Chấm điểm rủi ro theo thời gian thực</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="footer-link">
                            <ion-icon name="add-outline"></ion-icon>
                            <span class="span">Bộ lọc tục tiếng Việt có thể tùy chỉnh</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="footer-link">
                            <ion-icon name="add-outline"></ion-icon>
                            <span class="span">API & Webhook để tích hợp hệ thống</span>
                        </a>
                    </li>
                </ul>
                <ul class="footer-list">
                    <li>
                        <p class="footer-list-title">Contact Us</p>
                    </li>
                    <li class="footer-item">
                        <div class="item-icon">
                            <ion-icon name="location-outline"></ion-icon>
                        </div>
                        <a href="https://goo.gl/maps/BYA5MxQUg5B8ZFLcA">
                            <address class="item-text">
                                TP.HCM, Viet Nam
                            </address>
                        </a>
                    </li>
                    <li class="footer-item">
                        <div class="item-icon">
                            <ion-icon name="call-outline"></ion-icon>
                        </div>
                        <a href="tel:+0123456789" class="footer-link">+0123456789</a>
                    </li>
                    <li class="footer-item">
                        <div class="item-icon">
                            <ion-icon name="mail-outline"></ion-icon>
                        </div>
                        <a href="mailto:help@example.com" class="footer-link">iCheck@gmail.com</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <p class="copyright">
                    &copy; 2025 All Rights Reserved by iCheck
                </p>
                <ul class="social-list">
                    <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-facebook"></ion-icon>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-instagram"></ion-icon>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-twitter"></ion-icon>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>
    <!--BACK TO TOP-->
    <a href="#top" class="back-top-btn" aria-label="back to top" data-back-top-btn>
        <ion-icon name="caret-up" aria-hidden="true"></ion-icon>
    </a>

    <!--custom js link-->
    <script src="./assets/js/script.js" defer></script>
    <!--ionicon link-->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="/assets/app.js"></script>

    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Animate on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card, .benefit-item, .stat-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });

        // Counter animation for stats
        function animateCounter(element) {
            const target = element.textContent;
            const isNumber = /^\d+$/.test(target);

            if (isNumber) {
                const duration = 2000;
                const start = 0;
                const end = parseInt(target);
                const increment = end / (duration / 16);
                let current = start;

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= end) {
                        element.textContent = end;
                        clearInterval(timer);
                    } else {
                        element.textContent = Math.floor(current);
                    }
                }, 16);
            }
        }

        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const number = entry.target.querySelector('.stat-number');
                    animateCounter(number);
                    statsObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.5
        });

        document.querySelectorAll('.stat-card').forEach(card => {
            statsObserver.observe(card);
        });
    </script>
</body>

</html>