<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contacto · Grupo Morelos</title>
  {{-- Se incluyen fuentes y estilos css específicos para la página de contacto. --}}
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite('resources/css/contacto.css')
</head>
<body>

  {{-- Bloque de encabezado (header), contiene logo y navegación principal para usuarios externos. --}}
  <header class="site-header">
    <div class="header-container">
      <div class="logo">
        <img src="{{ asset('images/logoprincipal.png') }}" alt="Grupo Morelos"/>
        <span>GRUPO MORELOS</span>
      </div>
      <nav class="main-nav">
        <ul class="nav-links">
          <li><a href="{{ route('inicio') }}">Sobre nosotros</a></li>
          <li><a href="{{ route('oferta') }}">Oferta</a></li>
          <li><a href="{{ route('docente.login') }}">Docentes</a></li>
          <li><a href="{{ route('alumno.login') }}">Alumnos</a></li>
          <li><a href="{{ route('aspirante.select') }}">Aspirantes</a></li>
        </ul>
      </nav>
    </div>
  </header>

  {{-- Bloque hero, sección visual grande con una imagen de fondo (`contacto.png`). --}}
  <section class="contacto-hero" style="background-image: url('{{ asset('images/contacto.png') }}');">
  </section>

  {{-- Sección de contenido principal (contacto y mapa) --}}
  <section class="contacto">
    <div class="container contacto-grid">
      {{-- Sección de contenido principal (contacto y mapa) --}}
      <aside class="contact-panel">
        <h2>Hablemos</h2>
        <p>Resolvemos tus dudas sobre diplomados, certificaciones y procesos de inscripción.</p>

        {{-- Sección de redes sociales --}}
        <ul class="socials">
          <li>
            <a href="https://www.facebook.com/GrupoMorelosAC" target="_blank" rel="noopener">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 9h3V6h-3a3 3 0 0 0-3 3v3H8v3h3v6h3v-6h3l1-3h-4V9a1 1 0 0 1 1-1Z"/></svg>
              Facebook
            </a>
          </li>
          
          <li>
            <a href="https://www.instagram.com/grupomorelosac?igsh=MXVsZ25lYjlsY3dibA==" target="_blank" rel="noopener">
              <svg viewBox="0 0 24 24" aria-hidden="true">
                <rect x="3" y="3" width="18" height="18" rx="4" ry="4"
                      fill="none" stroke="currentColor" stroke-width="2"/>
                <circle cx="12" cy="12" r="4"
                        fill="none" stroke="currentColor" stroke-width="2"/>
                <circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/>
              </svg>
              Instagram
            </a>
          </li>

          <li>
            <a href="https://wa.me/527771031078" target="_blank" rel="noopener">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 12.1A8 8 0 0 1 8.7 20L4 21l1.1-4.5A8 8 0 1 1 20 12.1Zm-8-6.1a6.2 6.2 0 0 0-5.3 9.4l.3.5-.6 2.5 2.6-.7.5.3a6.2 6.2 0 1 0 2.5-11.9Zm3.6 7.9c-.2-.1-1.4-.7-1.6-.8s-.4-.1-.5.1c-.1.2-.6.8-.7 1-.1.1-.2.2-.5.1s-1-.4-1.9-1.2c-.7-.6-1.2-1.4-1.3-1.6-.1-.2 0-.3.1-.4l.3-.4c.1-.1.1-.2.2-.3s.1-.2 0-.3c0-.1-.5-1.2-.7-1.6-.2-.4-.3-.3-.5-.3h-.4c-.1 0-.3 0-.4.2-.1.2-.6.6-.6 1.5s.6 1.7.6 1.8c.1.1 1.2 2 3 2.8.4.2.7.3 1 .4.4.1.8.1 1.1.1.3 0 1 0 1.4-.3.3-.2 1-.8 1.1-1.2.2-.4.2-.8.1-.9-.1 0-.2 0-.3-.1Z"/></svg>
              WhatsApp
            </a>
          </li>
        </ul>

        <div class="dire">
          <h3>Dirección</h3>
          <p>Leñeros S/N, Los Volcanes, 62350 Cuernavaca, Mor. Facultad de Medicina UAEM.</p>
      </aside>

      {{-- Bloque de mapa y foto de instalaciones. --}}      
      <div class="map-photo-grid">
        <div class="map-wrapper">
          <div class="map-wrapper">
        <iframe
            class="map-embed"
            src="https://www.google.com/maps?q={{ urlencode('Leñeros S/N, Los Volcanes, 62350 Cuernavaca, Mor. Facultad de medicina de la UAEM') }}&output=embed"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            aria-label="Mapa">
        </iframe>
        </div>

        </div>

        <div class="photo-wrapper">
          <img src="{{ asset('images/ubicacion.png') }}" alt="Instalaciones Grupo Morelos">
        </div>
      </div>
    </div>
  </section>

  <script src="https://cdn.userway.org/widget.js" data-account="kvnkkEfZx0"></script>
  @stack('scripts')

  <footer class="site-footer">
    <div class="container">
      <p>&copy; 2025 Grupo Morelos. Todos los derechos reservados.</p>
    </div>
  </footer>

</body>
</html>
