<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupo Morelos</title>
    {{-- Se incluyen fuentes y estilos css específicos para la página de contacto. --}}
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/inicio.css')
</head>
<body>

{{-- Bloque de encabezado (header), contiene logo y navegación principal para usuarios externos. --}}
<header class="site-header">
  <div class="header-container">
    <div class="logo">
      <img src="{{ asset('images/logoprincipal.png') }}" alt="Grupo Morelos"/>
      <span>GRUPO MORELOS</span>
    </div>

    {{-- Bloque de secciones de la página. --}}      
    <div class="header-right">
        <nav class="main-nav">
        <ul class="nav-links">
            <li><a href="{{ route('inicio') }}">Inicio</a></li>
            <li><a href="{{ route('oferta') }}">Oferta</a></li>
            <li><a href="{{ route('docente.login') }}">Docentes</a></li>
            <li><a href="{{ route('alumno.login') }}">Alumnos</a></li>
            <li><a href="{{ route('aspirante.select') }}">Aspirantes</a></li>
            <li><a href="{{ route('contacto') }}">Contacto</a></li>
        </ul>
        </nav>
    </div>

      {{-- Bloque para ingresar como administrador. --}}      
      <a href="{{ route('admin.login') }}" class="user-icon" aria-label="Cuenta">
        <img src="{{ asset('images/user.png') }}" alt="Cuenta">
      </a>
    </div>
  </div>
</header>

    <section class="hero" style="background-image: url('{{ asset('images/banner.png') }}');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
        </div>
    </section>

    {{-- Bloque sobre nosotros. --}}      
    <section class="sobre-nosotros" id="sobre-nosotros">
        <div class="container contenido">
            <h2>SOBRE NOSOTROS</h2>
            <p>
                Diplomado en la Formación de Paramédicos, orientado al desarrollo de competencias teóricas y prácticas en atención prehospitalaria, primeros auxilios y manejo de emergencias médicas.
            </p>
            <div class="mvv-grid">
                <div class="item mision">
                    <h3>+10</h3>
                    <p>Años de experiencia</p>
                </div>
                <div class="item mision">
                    <h3>+70</h3>
                    <p>Generaciones</p>
                </div>
                <div class="item mision">
                    <h3>+5</h3>
                    <p>Cedes</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Bloque sobre nuestra historia. --}}
    <section class="historia">
        <div class="container">
            <div class="historia-grid">
                <div class="historia-texto">
                    <h2>NUESTRA HISTORIA</h2>
                    <p>En Grupo Morelos, hemos dedicado más de 15 años a la formación de profesionales en emergencias, construyendo un legado de excelencia y servicio.</p>
                </div>
                <div class="galeria">
                    <img src="{{ asset('images/historia.png') }}" alt="Nuestra Historia">
                </div>
            </div>
        </div>
    </section>

    {{-- Bloque sobre misión, visión y valores. --}}
    <section class="mision-vision-valores">
        <div class="container">
            <h2 class="section-title">MISIÓN, VISIÓN Y VALORES</h2>

            <div class="mvv-grid">
            <div class="item mision">
                <h3>MISIÓN</h3>
                <p>Formamos profesionales en atención prehospitalaria, protección civil y bomberos, con excelencia académica y ética.</p>
            </div>
            <div class="item vision">
                <h3>VISIÓN</h3>
                <p>Ser una institución líder en la formación de profesionales de la salud y emergencias, reconocida a nivel nacional.</p>
            </div>
            <div class="item valores">
                <h3>VALORES</h3>
                <p>Compromiso, responsabilidad, ética y excelencia en el aprendizaje continuo.</p>
            </div>
            </div>
        </div>
    </section>

    {{-- Bloque sobre aliados y certificaciones. --}}
    <section class="logos-strip" aria-label="Logos de aliados y certificaciones">
        <div class="container">
            <h2 class="logos-title">Aliados y certificaciones</h2>

            <ul class="logos-grid">
            <li>
                <a href="#" aria-label="Logo 1">
                <img src="{{ asset('images/logoprincipal.png') }}" alt="Logo 1">
                </a>
            </li>
            <li>
                <a href="#" aria-label="Logo 2">
                <img src="{{ asset('images/logosecundario.png') }}" alt="Logo 2">
                </a>
            </li>
            <li>
                <a href="#" aria-label="Logo 3">
                <img src="{{ asset('images/logo3.png') }}" alt="Logo 3">
                </a>
            </li>
            <li>
                <a href="#" aria-label="Logo 4">
                <img src="{{ asset('images/logo4.png') }}" alt="Logo 4">
                </a>
            </li>
            <li>
                <a href="#" aria-label="Logo 5">
                <img src="{{ asset('images/logo5.png') }}" alt="Logo 5">
                </a>
            </li>
            <li>
                <a href="#" aria-label="Logo 6">
                <img src="{{ asset('images/logo6.jpg') }}" alt="Logo 6">
                </a>
            </li>
            <li>
                <a href="#" aria-label="Logo 7">
                <img src="{{ asset('images/logo7.png') }}" alt="Logo 7">
                </a>
            </li>
            <li>
                <a href="#" aria-label="Logo 8">
                <img src="{{ asset('images/logo8.jpg') }}" alt="Logo 8">
                </a>
            </li>
            </ul>
        </div>
    </section>
    
    <footer class="site-footer">
        <div class="container">
            <p>&copy; 2025 Grupo Morelos. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
