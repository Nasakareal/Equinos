<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Equinos y Caninos') }}</title>
  <link rel="icon" href="{{ asset('icon.ico') }}" type="image/x-icon">
  <meta name="description" content="Agrupamiento de Equinos y Caninos — Secretaría de Seguridad Pública de Michoacán. Patrullaje montado, binomios caninos, búsqueda y rescate, detección y programa de equinoterapia.">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    :root{
      --bg0:#070B16;
      --bg1:#0A1228;
      --card: rgba(255,255,255,.06);
      --card2: rgba(255,255,255,.08);
      --border: rgba(255,255,255,.14);
      --txt: rgba(255,255,255,.92);
      --muted: rgba(255,255,255,.72);
      --muted2: rgba(255,255,255,.56);
      --accent:#00E5FF;
      --accent2:#7C4DFF;
      --ok:#00D084;
      --warn:#FFB020;
      --danger:#FF4D6D;
      --shadow: 0 20px 60px rgba(0,0,0,.45);
      --shadow2: 0 10px 30px rgba(0,0,0,.35);
      --radius: 18px;
    }

    html,body{height:100%; background: radial-gradient(1200px 700px at 20% 10%, rgba(124,77,255,.18), transparent 55%),
                                     radial-gradient(900px 600px at 90% 15%, rgba(0,229,255,.14), transparent 50%),
                                     radial-gradient(900px 600px at 60% 95%, rgba(0,208,132,.10), transparent 55%),
                                     linear-gradient(180deg, var(--bg0), var(--bg1));
              color: var(--txt);}

    *{scroll-behavior:smooth}

    .glass{
      background: linear-gradient(180deg, var(--card), rgba(255,255,255,.03));
      border: 1px solid var(--border);
      box-shadow: var(--shadow2);
      border-radius: var(--radius);
      backdrop-filter: blur(10px);
    }

    .nav-blur{
      position: sticky; top:0; z-index: 1000;
      backdrop-filter: blur(14px);
      background: rgba(7,11,22,.55);
      border-bottom: 1px solid rgba(255,255,255,.10);
    }

    .brand-badge{
      width: 44px; height: 44px;
      border-radius: 14px;
      display: grid; place-items: center;
      background: linear-gradient(135deg, rgba(0,229,255,.22), rgba(124,77,255,.22));
      border: 1px solid rgba(255,255,255,.14);
      box-shadow: 0 10px 25px rgba(0,0,0,.25);
    }

    .hero{
      position: relative;
      overflow: hidden;
      border-radius: calc(var(--radius) + 6px);
      box-shadow: var(--shadow);
      border: 1px solid rgba(255,255,255,.12);
      background:
        radial-gradient(700px 400px at 20% 10%, rgba(0,229,255,.14), transparent 55%),
        radial-gradient(800px 450px at 90% 15%, rgba(124,77,255,.16), transparent 60%),
        linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.02));
    }

    .hero::before{
      content:"";
      position:absolute; inset:-2px;
      background:
        radial-gradient(500px 300px at 30% 35%, rgba(0,229,255,.10), transparent 60%),
        radial-gradient(600px 380px at 75% 25%, rgba(124,77,255,.12), transparent 65%),
        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1200' height='600'%3E%3Cdefs%3E%3CradialGradient id='g' cx='50%25' cy='50%25' r='70%25'%3E%3Cstop offset='0%25' stop-color='white' stop-opacity='0.08'/%3E%3Cstop offset='100%25' stop-color='white' stop-opacity='0'/%3E%3C/radialGradient%3E%3C/defs%3E%3Ccircle cx='220' cy='130' r='240' fill='url(%23g)'/%3E%3Ccircle cx='980' cy='140' r='280' fill='url(%23g)'/%3E%3Ccircle cx='680' cy='520' r='330' fill='url(%23g)'/%3E%3C/svg%3E");
      opacity: .85;
      filter: saturate(1.1);
      pointer-events:none;
    }

    .hero-content{ position: relative; }

    .kicker{
      display:inline-flex; gap:.6rem; align-items:center;
      padding: .45rem .75rem;
      border-radius: 999px;
      background: rgba(255,255,255,.06);
      border: 1px solid rgba(255,255,255,.10);
      color: var(--muted);
      font-size: .92rem;
    }
    .kicker i{ color: var(--accent); }

    .headline{
      font-weight: 800;
      letter-spacing: -.02em;
      line-height: 1.05;
      font-size: clamp(2.2rem, 4vw, 3.6rem);
      margin: 0.9rem 0 0.6rem 0;
      text-shadow: 0 20px 40px rgba(0,0,0,.28);
    }

    .subhead{
      color: var(--muted);
      font-size: clamp(1.05rem, 1.3vw, 1.2rem);
      max-width: 62ch;
    }

    .btn-glow{
      border: 1px solid rgba(255,255,255,.14) !important;
      background: linear-gradient(135deg, rgba(0,229,255,.16), rgba(124,77,255,.16)) !important;
      color: var(--txt) !important;
      box-shadow: 0 16px 35px rgba(0,0,0,.30);
    }
    .btn-glow:hover{ transform: translateY(-1px); }
    .btn-outline-soft{
      border: 1px solid rgba(255,255,255,.18) !important;
      color: var(--txt) !important;
      background: rgba(255,255,255,.04) !important;
    }
    .btn-outline-soft:hover{ background: rgba(255,255,255,.06) !important; }

    .stat{
      padding: 1rem;
      border-radius: 16px;
      border: 1px solid rgba(255,255,255,.12);
      background: rgba(255,255,255,.04);
      height: 100%;
    }
    .stat .n{ font-weight: 800; font-size: 1.8rem; }
    .stat .l{ color: var(--muted2); font-size: .95rem; }

    .section-title{
      font-weight: 800;
      letter-spacing: -.01em;
    }
    .section-lead{ color: var(--muted); max-width: 70ch; }

    .service-card{
      height: 100%;
      padding: 1.15rem;
      border-radius: var(--radius);
      border: 1px solid rgba(255,255,255,.12);
      background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.03));
      box-shadow: 0 14px 35px rgba(0,0,0,.25);
      transition: transform .15s ease, border-color .15s ease;
    }
    .service-card:hover{
      transform: translateY(-3px);
      border-color: rgba(0,229,255,.28);
    }
    .icon-chip{
      width: 48px; height: 48px;
      border-radius: 16px;
      display: grid; place-items: center;
      border: 1px solid rgba(255,255,255,.14);
      background: rgba(255,255,255,.06);
      box-shadow: 0 12px 25px rgba(0,0,0,.25);
    }
    .service-card h5{ font-weight: 800; margin-top: .9rem; }
    .service-card p{ color: var(--muted); margin: 0; }

    .pill{
      display:inline-flex; align-items:center; gap:.45rem;
      padding: .35rem .65rem;
      border-radius: 999px;
      border: 1px solid rgba(255,255,255,.14);
      background: rgba(255,255,255,.04);
      color: var(--muted);
      font-size: .9rem;
    }

    .divider{
      height:1px;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,.14), transparent);
    }

    .faq .accordion-item{
      background: rgba(255,255,255,.04);
      border: 1px solid rgba(255,255,255,.12);
      border-radius: 16px !important;
      overflow: hidden;
      box-shadow: 0 12px 30px rgba(0,0,0,.25);
    }
    .faq .accordion-button{
      background: rgba(255,255,255,.03);
      color: var(--txt);
      font-weight: 700;
    }
    .faq .accordion-button:not(.collapsed){
      background: rgba(255,255,255,.05);
      color: var(--txt);
      box-shadow: none;
    }
    .faq .accordion-body{ color: var(--muted); }

    .footer{
      color: var(--muted2);
      font-size: .95rem;
    }
    .footer a{ color: rgba(255,255,255,.78); text-decoration: none; }
    .footer a:hover{ text-decoration: underline; }

    .tiny-note{
      color: rgba(255,255,255,.45);
      font-size: .85rem;
    }
  </style>
</head>

<body>
  <!-- NAV -->
  <div class="nav-blur">
    <div class="container py-3">
      <div class="d-flex align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="brand-badge">
            <i class="fa-solid fa-shield-heart"></i>
          </div>
          <div class="lh-sm">
            <div class="fw-bold">Agrupamiento de Equinos y Caninos</div>
            <div class="tiny-note">Secretaría de Seguridad Pública • Michoacán</div>
          </div>
        </div>

        <div class="d-none d-md-flex align-items-center gap-3">
          <a class="pill" href="#servicios"><i class="fa-solid fa-grid-2"></i> Servicios</a>
          <a class="pill" href="#equinoterapia"><i class="fa-solid fa-horse"></i> Equinoterapia</a>
          <a class="pill" href="#k9"><i class="fa-solid fa-dog"></i> Binomios K9</a>
          <a class="pill" href="#faq"><i class="fa-solid fa-circle-question"></i> Preguntas</a>
        </div>

        <div class="d-flex align-items-center gap-2">
          @if (Route::has('login'))
            @auth
              <a href="{{ url('/home') }}" class="btn btn-sm btn-glow px-3">
                <i class="fa-solid fa-gauge-high me-2"></i> Ir al panel
              </a>
            @else
              <a href="{{ route('login') }}" class="btn btn-sm btn-outline-soft px-3">
                <i class="fa-solid fa-right-to-bracket me-2"></i> Iniciar sesión
              </a>
            @endauth
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- HERO -->
  <div class="container my-4 my-md-5">
    <div class="hero p-4 p-md-5">
      <div class="hero-content">
        <div class="row align-items-center g-4">
          <div class="col-lg-7">
            <span class="kicker">
              <i class="fa-solid fa-location-dot"></i>
              Seguridad, proximidad social y apoyo comunitario en Michoacán
            </span>

            <h1 class="headline">
              Equinos y Caninos <span style="color: var(--accent);">al servicio</span> de tu seguridad
            </h1>

            <p class="subhead mb-4">
              Patrullaje montado en zonas de difícil acceso, binomios K9 para prevención y operatividad,
              y un programa de equinoterapia que impulsa el desarrollo y rehabilitación infantil.
            </p>

            <div class="d-flex flex-wrap gap-2">
              <a href="#servicios" class="btn btn-glow btn-lg px-4">
                <i class="fa-solid fa-compass me-2"></i> Ver servicios
              </a>
              <a href="#contacto" class="btn btn-outline-soft btn-lg px-4">
                <i class="fa-solid fa-phone me-2"></i> Solicitar información
              </a>
            </div>

            <div class="mt-4 d-flex flex-wrap gap-2">
              <span class="pill"><i class="fa-solid fa-horse-head" style="color: var(--warn)"></i> Vigilancia montada</span>
              <span class="pill"><i class="fa-solid fa-dog" style="color: var(--ok)"></i> Guardia, protección y rescate</span>
              <span class="pill"><i class="fa-solid fa-magnifying-glass" style="color: var(--accent)"></i> Detección especializada</span>
            </div>
          </div>

          <div class="col-lg-5">
            <div class="glass p-3 p-md-4">
              <div class="row g-3">
                <div class="col-12">
                  <div class="stat">
                    <div class="d-flex align-items-center justify-content-between">
                      <div>
                        <div class="n" data-count="2100">0</div>
                        <div class="l">Sesiones de equinoterapia reportadas (enero–agosto 2023)</div>
                      </div>
                      <i class="fa-solid fa-horse fa-2xl" style="color: var(--warn)"></i>
                    </div>
                  </div>
                </div>

                <div class="col-6">
                  <div class="stat">
                    <div class="d-flex align-items-center justify-content-between">
                      <div>
                        <div class="n" data-count="21">0</div>
                        <div class="l">Binomios caninos en operatividad (referencias públicas)</div>
                      </div>
                      <i class="fa-solid fa-dog fa-xl" style="color: var(--ok)"></i>
                    </div>
                  </div>
                </div>

                <div class="col-6">
                  <div class="stat">
                    <div class="d-flex align-items-center justify-content-between">
                      <div>
                        <div class="n" data-count="3">0</div>
                        <div class="l">Ejes: seguridad, rescate y apoyo comunitario</div>
                      </div>
                      <i class="fa-solid fa-shield-halved fa-xl" style="color: var(--accent)"></i>
                    </div>
                  </div>
                </div>

                <div class="col-12">
                  <div class="tiny-note mt-1">
                    * Cifras y funciones descritas con base en comunicados y notas públicas de SSP/medios locales.
                  </div>
                </div>
              </div>
            </div>

            <div class="divider my-4"></div>

            <div class="glass p-3 p-md-4">
              <div class="d-flex gap-3 align-items-start">
                <div class="icon-chip">
                  <i class="fa-solid fa-star"></i>
                </div>
                <div>
                  <div class="fw-bold">Enfoque de proximidad</div>
                  <div class="text-white-50">
                    Operatividad preventiva y presencia estratégica en espacios públicos, eventos y zonas con acceso complejo.
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- SERVICIOS -->
  <div id="servicios" class="container my-5">
    <div class="text-center mb-4">
      <h2 class="section-title mb-2">Servicios y capacidades</h2>
      <p class="section-lead mx-auto">
        Un agrupamiento especializado con componentes montados y K9, además de un programa social de equinoterapia.
      </p>
    </div>

    <div class="row g-3 g-md-4">
      <div class="col-md-6 col-lg-4">
        <div class="service-card">
          <div class="icon-chip"><i class="fa-solid fa-horse-head"></i></div>
          <h5>Vigilancia montada</h5>
          <p>
            Recorridos a caballo para prevención y disuasión, especialmente en zonas donde el acceso vehicular es difícil.
          </p>
          <div class="mt-3 d-flex flex-wrap gap-2">
            <span class="pill"><i class="fa-solid fa-route"></i> Recorridos</span>
            <span class="pill"><i class="fa-solid fa-mountain-city"></i> Zonas complejas</span>
          </div>
        </div>
      </div>

      <div id="k9" class="col-md-6 col-lg-4">
        <div class="service-card">
          <div class="icon-chip"><i class="fa-solid fa-dog"></i></div>
          <h5>Binomios K9</h5>
          <p>
            Guardia y protección, búsqueda y rescate, y apoyo operativo en dispositivos de seguridad.
          </p>
          <div class="mt-3 d-flex flex-wrap gap-2">
            <span class="pill"><i class="fa-solid fa-person-shelter"></i> Protección</span>
            <span class="pill"><i class="fa-solid fa-life-ring"></i> Rescate</span>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="service-card">
          <div class="icon-chip"><i class="fa-solid fa-magnifying-glass"></i></div>
          <h5>Detección especializada</h5>
          <p>
            Detección de narcóticos, armas, explosivos y otros apoyos especializados según operatividad y coordinación interinstitucional.
          </p>
          <div class="mt-3 d-flex flex-wrap gap-2">
            <span class="pill"><i class="fa-solid fa-bomb"></i> Explosivos</span>
            <span class="pill"><i class="fa-solid fa-gun"></i> Armas</span>
            <span class="pill"><i class="fa-solid fa-capsules"></i> Narcóticos</span>
          </div>
        </div>
      </div>

      <div id="equinoterapia" class="col-md-6 col-lg-4">
        <div class="service-card">
          <div class="icon-chip"><i class="fa-solid fa-hand-holding-heart"></i></div>
          <h5>Programa de Equinoterapia</h5>
          <p>
            Terapia complementaria a través del movimiento del caballo para apoyar coordinación psicomotriz, equilibrio y desarrollo.
          </p>
          <div class="mt-3 d-flex flex-wrap gap-2">
            <span class="pill"><i class="fa-solid fa-child"></i> Infancias</span>
            <span class="pill"><i class="fa-solid fa-heart-pulse"></i> Rehabilitación</span>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="service-card">
          <div class="icon-chip"><i class="fa-solid fa-people-group"></i></div>
          <h5>Proximidad y cultura preventiva</h5>
          <p>
            Presencia en espacios públicos y actividades que fortalecen la confianza ciudadana y la prevención.
          </p>
          <div class="mt-3 d-flex flex-wrap gap-2">
            <span class="pill"><i class="fa-solid fa-comments"></i> Proximidad</span>
            <span class="pill"><i class="fa-solid fa-shield"></i> Prevención</span>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="service-card">
          <div class="icon-chip"><i class="fa-solid fa-sitemap"></i></div>
          <h5>Coordinación operativa</h5>
          <p>
            Apoyos en operativos y dispositivos en coordinación con otras áreas/corporaciones, según necesidades de seguridad.
          </p>
          <div class="mt-3 d-flex flex-wrap gap-2">
            <span class="pill"><i class="fa-solid fa-network-wired"></i> Coordinación</span>
            <span class="pill"><i class="fa-solid fa-calendar-check"></i> Dispositivos</span>
          </div>
        </div>
      </div>
    </div>

    <div class="divider my-5"></div>

    <!-- BLOQUE “IMPACTO” -->
    <div class="row g-4 align-items-stretch">
      <div class="col-lg-6">
        <div class="glass p-4 h-100">
          <h3 class="section-title mb-2">Impacto comunitario</h3>
          <p class="section-lead mb-3">
            El componente social (equinoterapia) y el componente operativo (equinos/K9) comparten una meta: seguridad con enfoque humano.
          </p>
          <ul class="mb-0 text-white-50">
            <li class="mb-2">Equinoterapia como alternativa complementaria para apoyo físico, psíquico y social.</li>
            <li class="mb-2">K9 para guardia/protección, búsqueda/rescate y detección.</li>
            <li class="mb-2">Equinos para vigilancia en zonas de difícil acceso vehicular.</li>
          </ul>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="glass p-4 h-100">
          <h3 class="section-title mb-2">Transparencia del alcance</h3>
          <p class="section-lead mb-3">
            Este sitio es un inicio (“welcome”) para presentar servicios. La agenda, requisitos y disponibilidad se confirman por canales oficiales.
          </p>
          <div class="d-flex flex-wrap gap-2">
            <span class="pill"><i class="fa-solid fa-circle-info"></i> Información</span>
            <span class="pill"><i class="fa-solid fa-clipboard-check"></i> Requisitos</span>
            <span class="pill"><i class="fa-solid fa-clock"></i> Horarios</span>
            <span class="pill"><i class="fa-solid fa-location-crosshairs"></i> Cobertura</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- FAQ -->
  <div id="faq" class="container my-5 faq">
    <div class="text-center mb-4">
      <h2 class="section-title mb-2">Preguntas frecuentes</h2>
      <p class="section-lead mx-auto">Respuestas rápidas para orientar a la ciudadanía.</p>
    </div>

    <div class="accordion accordion-flush" id="faqAcc">
      <div class="accordion-item mb-3">
        <h2 class="accordion-header" id="h1">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c1">
            ¿Qué servicios ofrece el componente canino (K9)?
          </button>
        </h2>
        <div id="c1" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
          <div class="accordion-body">
            De forma general, se publican funciones como guardia y protección, búsqueda y rescate, y detección/localización (narcóticos, armas, explosivos, entre otros apoyos especializados), según operatividad y coordinación.
          </div>
        </div>
      </div>

      <div class="accordion-item mb-3">
        <h2 class="accordion-header" id="h2">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c2">
            ¿Para qué sirve el patrullaje montado?
          </button>
        </h2>
        <div id="c2" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
          <div class="accordion-body">
            Permite cubrir zonas con difícil acceso vehicular y fortalecer la presencia preventiva en espacios públicos, brindando vigilancia y disuasión.
          </div>
        </div>
      </div>

      <div class="accordion-item mb-3">
        <h2 class="accordion-header" id="h3">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c3">
            ¿La equinoterapia tiene costo?
          </button>
        </h2>
        <div id="c3" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
          <div class="accordion-body">
            En comunicados y notas públicas se menciona como servicio gratuito en el marco de apoyo comunitario; la disponibilidad, requisitos y agenda se confirman con la autoridad correspondiente.
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CONTACTO -->
  <div id="contacto" class="container my-5">
    <div class="glass p-4 p-md-5">
      <div class="row g-4 align-items-center">
        <div class="col-lg-8">
          <h2 class="section-title mb-2">Solicitar información</h2>
          <p class="section-lead mb-0">
            Integra aquí (cuando quieras) los canales oficiales: teléfono, correo, ubicación y requisitos.
            Por ahora, este bloque queda listo para conectarlo con tu módulo real.
          </p>
        </div>
        <div class="col-lg-4">
          <div class="d-grid gap-2">
            <a class="btn btn-glow btn-lg" href="javascript:void(0)">
              <i class="fa-solid fa-envelope me-2"></i> Contacto (próximamente)
            </a>
            <a class="btn btn-outline-soft btn-lg" href="#servicios">
              <i class="fa-solid fa-arrow-up-right-from-square me-2"></i> Volver a servicios
            </a>
          </div>
        </div>
      </div>

      <div class="divider my-4"></div>

      <div class="footer d-flex flex-column flex-md-row justify-content-between gap-2">
        <div>
          © {{ date('Y') }} • Agrupamiento de Equinos y Caninos • SSP Michoacán
        </div>
        <div class="tiny-note">
          Sitio informativo inicial (welcome) • Desarrollado en Laravel
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // contador suave para los números del hero
    (function(){
      const els = document.querySelectorAll('[data-count]');
      const easeOut = t => 1 - Math.pow(1 - t, 3);

      const animate = (el) => {
        const end = parseInt(el.getAttribute('data-count'), 10) || 0;
        const dur = 900 + Math.min(end, 2100) * 0.15;
        const start = performance.now();
        const from = 0;

        const step = (now) => {
          const t = Math.min(1, (now - start) / dur);
          const val = Math.round(from + (end - from) * easeOut(t));
          el.textContent = val.toLocaleString('es-MX');
          if (t < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
      };

      const io = new IntersectionObserver((entries) => {
        entries.forEach(e => {
          if (e.isIntersecting){
            animate(e.target);
            io.unobserve(e.target);
          }
        });
      }, { threshold: 0.6 });

      els.forEach(el => io.observe(el));
    })();
  </script>
</body>
</html>
