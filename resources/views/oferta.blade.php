<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Oferta Educativa · Grupo Morelos</title>
  {{-- Se incluyen fuentes y estilos css específicos para la página de contacto. --}}
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite('resources/css/oferta.css')
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
          <li><a href="{{ route('inicio') }}">Sobre nosotros</a>
          </li><li><a href="{{ route('docente.login') }}">Docentes</a></li>
          <li><a href="{{ route('alumno.login') }}">Alumnos</a></li>
          <li><a href="{{ route('aspirante.select') }}">Aspirantes</a></li>
          <li><a href="{{ route('contacto') }}">Contacto</a></li>
        </ul>
      </nav>
    </div>
  </header>

  {{-- Bloque hero, sección visual grande con una imagen de fondo (`oferta.png`). --}}
  <section class="oferta-hero" style="background-image: url('{{ asset('images/oferta.png') }}');">
  </section>

  {{-- Bloque de diplomados y programas. --}}
  <section id="programas" class="programas">
    <div class="container">
      <h2 class="section-title">DIPLOMADOS Y PROGRAMAS</h2>

      <div class="cards">
        <article class="card">
          <div class="card-body">
            <h3>Diplomado en nivel básico</h3>
            <p>Fundamentos de atención prehospitalaria, primeros auxilios y evaluación primaria. Ideal para iniciar tu ruta profesional.</p>
            <ul class="tags">
              <li>Duración: 12 meses</li>
              <li>Modalidad: Presencual</li>
            </ul>
            <a href="#" class="btn">Solicitar información</a>
          </div>
        </article>

        <article class="card">
          <div class="card-body">
            <h3>Diplomado intermedio avanzado</h3>
            <p>Profundiza en soporte vital, manejo de trauma y protocolos avanzados. Para quienes ya dominan los fundamentos.</p>
            <ul class="tags">
              <li>Duración: 18 meses</li>
              <li>Modalidad: Presencial</li>
              <li>Requisito: Nivel básico</li>
            </ul>
            <a href="#" class="btn">Solicitar información</a>
          </div>
        </article>

        <article class="card">
          <div class="card-body">
            <h3>Licenciatura en gestión integral de riesgo</h3>
            <p>Formación integral en prevención, mitigación y respuesta ante desastres, con enfoque en políticas públicas y operación.</p>
            <ul class="tags">
              <li>Duración: 7 semestres</li>
              <li>Modalidad: Escolarizada</li>
              <li>Título: Licenciatura</li>
            </ul>
            <a href="#" class="btn">Solicitar información</a>
          </div>
        </article>
      </div>
    </div>
  </section>

  <section id="buceo" class="destacado-buceo">
    <div class="container destacado-grid">
      <div class="destacado-copy">
        <h2>CERTIFICACIÓN DE BUCEO</h2>
        <p>Entrenamiento especializado con instructores certificados. Enfocado en seguridad, operación y rescate en medios acuáticos.</p>
        <ul class="bullets">
          <li>Prácticas supervisadas en campo y piscina.</li>
          <li>Protocolos de rescate y primeros auxilios acuáticos.</li>
          <li>Certificación avalada por instructores con experiencia.</li>
        </ul>
      </div>
      <div class="destacado-media">
        <img src="{{ asset('images/buceo.png') }}" alt="Certificación en buceo">
      </div>
    </div>
  </section>

  {{-- Bloque de instructores. --}}
  <section class="instructores">
    <div class="container">
      <h3 class="section-title">INSTRUCTORES DE BUCEO</h3>

      <div class="instructores-grid">
        <article class="instr-card">
          <img class="instr-photo" src="{{ asset('images/instructores.png') }}" alt="Instructor 1">
          <div class="instr-body">
            <h4>Jorge Rios Calderón 1</h4>
            <p class="role">Instructor de buceo - Rescate Acuático</p>
            <ul class="badges">
              <li>+10 años</li>
              <li>Rescate</li>
              <li>Member ID: 64096</li>
            </ul>
          </div>
        </article>

        <article class="instr-card">
          <img class="instr-photo" src="{{ asset('images/instructores.png') }}" alt="Instructor 2">
          <div class="instr-body">
            <h4>Fatima Rios Armenta</h4>
            <p class="role">Buceo Avanzado · Seguridad</p>
            <ul class="badges">
              <li>+7 años</li>
              <li>Operaciones</li>
              <li>Member ID: 64647</li>
            </ul>
          </div>
        </article>

        <article class="instr-card">
          <img class="instr-photo" src="{{ asset('images/instructores.png') }}" alt="Instructor 3">
          <div class="instr-body">
            <h4>Jesus Rios Armenta</h4>
            <p class="role">Técnicas de rescate · Equipos</p>
            <ul class="badges">
              <li>+12 años</li>
              <li>Equipamiento</li>
              <li>Member ID: 65568</li>
            </ul>
          </div>
        </article>
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
