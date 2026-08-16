<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Đăng nhập — Hệ thống điều hành quản lý trường học</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

<link rel="stylesheet" href="/css/theme.css">
<link rel="stylesheet" href="/css/nav.css">
<link rel="stylesheet" href="/css/login.css">
</head>
<body>

<!-- ============ TOP NAV - trạng thái CHƯA đăng nhập ============ -->
<header class="topnav is-guest">
  <div class="topnav-brand">
    <span class="topnav-logo">
      <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
        <rect width="32" height="32" rx="7" fill="#B8892F"/>
        <path d="M8 10.5C8 9.67157 8.67157 9 9.5 9H15V23H9.5C8.67157 23 8 22.3284 8 21.5V10.5Z" fill="#12253E"/>
        <path d="M24 10.5C24 9.67157 23.3284 9 22.5 9H17V23H22.5C23.3284 23 24 22.3284 24 21.5V10.5Z" fill="#12253E"/>
        <line x1="16" y1="9.5" x2="16" y2="22.5" stroke="#B8892F" stroke-width="1"/>
      </svg>
    </span>
    <span class="topnav-appname">Hệ thống điều hành quản lý trường học</span>
  </div>
</header>

<!-- ============ NỘI DUNG TRANG LOGIN ============ -->
<div class="login-screen">
  <!-- Panel trái: giấy kẻ ngang + con dấu -->
  <div class="panel-left">
    <span class="live-badge"><span class="pulse"></span>Hệ thống đang hoạt động</span>
    <div class="brand-mark">
      <span class="brand-eyebrow">Hệ thống quản lý trường học</span>
      <h1 class="brand-title">Lịch báo giảng &amp; điểm danh</h1>
      <p class="brand-sub">Giáo viên cập nhật, hiệu trưởng phê duyệt — mọi tiết dạy được ghi nhận đúng thời gian, đúng người phụ trách.</p>
    </div>

    <div class="panel-quicklinks">
      <span class="panel-quicklinks-label">Truy cập theo cấp quản lý</span>
      <div class="quicklink-grid">
        <a href="#" class="quicklink-btn"><span class="dot"></span>Sở GD&ĐT</a>
        <a href="#" class="quicklink-btn"><span class="dot"></span>Mầm non</a>
        <a href="#" class="quicklink-btn"><span class="dot"></span>Tiểu học</a>
        <a href="#" class="quicklink-btn"><span class="dot"></span>THCS</a>
        <a href="#" class="quicklink-btn"><span class="dot"></span>THPT</a>
        <a href="#" class="quicklink-btn"><span class="dot"></span>GDNN - TX</a>
        <a href="#" class="quicklink-btn"><span class="dot"></span>Dạy thêm - học thêm</a>
      </div>
    </div>

    <div class="seal-wrap">
      <div class="seal">
        <svg viewBox="0 0 158 158">
          <defs>
            <path id="seal-arc" d="M 79,79 m -58,0 a 58,58 0 1,1 116,0" fill="none"/>
          </defs>
          <text>
            <textPath href="#seal-arc" startOffset="2%">ĐÃ&#8226;DUYỆT&#8226;ĐÚNG&#8226;HẠN&#8226;</textPath>
          </text>
        </svg>
        <div class="seal-center">SỔ<br>BÁO GIẢNG</div>
      </div>
    </div>

    <div class="panel-footer">
      <div><span class="n">03</span>cấp quản lý</div>
      <div><span class="n">100%</span>tiết dạy truy vết</div>
      <div><span class="n">24/7</span>gửi &amp; duyệt trực tuyến</div>
    </div>
  </div>

  <!-- Panel phải: form -->
  <div class="panel-right">
    <div class="form-card">
      <div class="form-eyebrow">Đăng nhập</div>
      <h2 class="form-title">Chào mừng trở lại</h2>
      <p class="form-desc">Nhập tài khoản được cấp bởi nhà trường để tiếp tục.</p>

      <div class="error-banner" id="errorBanner">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span>Tài khoản hoặc mật khẩu không đúng.</span>
      </div>

      <form id="loginForm">
        <div class="field">
          <label for="username">Tên đăng nhập</label>
          <div class="input-wrap">
            <input type="text" id="username" name="username" placeholder="vd: gv.nguyenvana" autocomplete="username" required>
          </div>
        </div>

        <div class="field">
          <label for="password">Mật khẩu</label>
          <div class="input-wrap">
            <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
            <button type="button" class="toggle-pass" id="togglePass" aria-label="Hiện mật khẩu">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
        </div>

        <div class="row-between">
          <label class="remember">
            <input type="checkbox" name="remember">
            Ghi nhớ đăng nhập
          </label>
          <a href="#">Quên mật khẩu?</a>
        </div>

        <button type="submit" class="btn btn-primary btn-submit">
          Đăng nhập
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </button>
      </form>

      <p class="form-footer-note">Liên hệ quản trị nhà trường nếu bạn gặp sự cố đăng nhập.</p>
    </div>
  </div>

</div>

<script>
  const togglePass = document.getElementById('togglePass');
  const passwordInput = document.getElementById('password');
  togglePass.addEventListener('click', () => {
    const isPass = passwordInput.type === 'password';
    passwordInput.type = isPass ? 'text' : 'password';
  });

  document.getElementById('loginForm').addEventListener('submit', function(e){
    e.preventDefault();
  });
</script>

</body>
</html>