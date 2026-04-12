@extends('layouts.app')

@section('title', 'Cookies - Jediga')

@section('content')
<div class="container py-5" style="margin-top: 100px; min-height: 50vh;">
    <!-- Contenedor con estilo para el texto legal -->
    <div class="row bg-dark text-white rounded p-5 shadow">
        <div class="col-12">
            <h1 class="fw-bold text-warning mb-4">Cookies</h1>
            
            <!-- ¿QUÉ SON LAS COOKIES? -->
            <div class="mb-4">
                <h4 class="fw-bold">¿QUÉ SON LAS COOKIES?</h4>
                <p class="opacity-75">
                    Las cookies son pequeños archivos de texto que se almacenan en el dispositivo del usuario cuando visita un sitio web. Sirven para recordar 
                    información sobre la navegación y mejorar la experiencia del usuario.
                </p>
            </div>

            <!-- ¿QUÉ TIPOS DE COOKIES UTILIZA ESTE SITIO WEB? -->
            <div class="mb-4">
                <h4 class="fw-bold">¿QUÉ TIPOS DE COOKIES UTILIZA ESTE SITIO WEB?</h4>
                <p class="opacity-75">
                    Este sitio web utiliza las siguientes categorías de cookies:
                </p>

                <ul>
                    <li class="opacity-75"><strong>Cookies técnicas (necesarias):</strong> Son esenciales para el funcionamiento del sitio web y no se pueden desactivar.</li>
                    <li class="opacity-75"><strong>Cookies de análisis:</strong> Permiten recopilar información sobre el uso del sitio web y mejorar la experiencia del usuario.</li>
                    <li class="opacity-75"><strong>Cookies de terceros:</strong> Son aquellas enviadas al dispositivo del usuario desde un dominio que no es gestionado por el responsable del sitio web.</li>

                </ul>

            </div>

            <!-- COOKIES UTILIZADAS EN ESTE SITIO WEB -->
            <div class="mb-4">
                <h4 class="fw-bold">COOKIES UTILIZADAS EN ESTE SITIO WEB</h4>
                <p class="opacity-75">
                    A continuación, se detallan las cookies utilizadas:
                </p>

                <ul>
                    <li class="opacity-75"><strong>Cookies propias:</strong> Cookies técnicas necesarias para el correcto funcionamiento del sitio web</li>
                    <li class="opacity-75"><strong>Cookies de análisis:</strong> Permiten recopilar información sobre el uso del sitio web y mejorar la experiencia del usuario.</li>
                    <li class="opacity-75"><strong>Cookies de terceros:</strong> Son aquellas enviadas al dispositivo del usuario desde un dominio que no es gestionado por el responsable del sitio web.</li>

                </ul>

            </div>

            <!-- Base legal para cookies -->
            <div class="mb-4">
                <h4 class="fw-bold">BASE LEGAL PARA EL TRATAMIENTO DE DATOS</h4>
                <p class="opacity-75">
                    El tratamiento de datos personales se realiza de conformidad con el Reglamento General de Protección de Datos (RGPD) y la Ley Orgánica 3/2018, de 5 de diciembre, de Protección de Datos 
                    Personales y garantía de los derechos digitales.
                </p>
            </div>

            <!-- CÓMO DESACTIVAR O ELIMINAR COOKIES DESDE EL NAVEGADOR -->
            <div class="mb-4">
                <h4 class="fw-bold">CÓMO DESACTIVAR O ELIMINAR COOKIES DESDE EL NAVEGADOR</h4>
                <p class="opacity-75">
                    El usuario puede permitir, bloquear o eliminar las cookies instaladas en su dispositivo mediante la configuración de su navegador. A continuación, se indican enlaces a la configuración de los navegadores más comunes:
                </p>

                <ul>
                    <li class="opacity-75"><strong>Google Chrome</strong></li>
                    <li class="opacity-75"><strong>Mozilla Firefox</strong> </li>
                    <li class="opacity-75"><strong>Microsoft Edge</strong></li>
                    <li class="opacity-75"><strong>Safari</strong></li>
                </ul>

            </div>

            <!-- TRANSFERENCIAS INTERNACIONALES DE DATOS -->
            <div class="mb-4">
                <h4 class="fw-bold">TRANSFERENCIAS INTERNACIONALES DE DATOS</h4>
                <p class="opacity-75">
                    Al utilizar cookies de Google LLC, pueden producirse transferencias internacionales de datos fuera del Espacio Económico Europeo.
                    Estas transferencias se realizan conforme a las Cláusulas Contractuales Tipo aprobadas por la Comisión Europea y otras garantías adecuadas.
                </p>
            </div>

            <!-- ACTUALIZACIONES DE LA POLÍTICA DE COOKIES -->
            <div class="mb-4">
                <h4 class="fw-bold">ACTUALIZACIONES DE LA POLÍTICA DE COOKIES</h4>
                <p class="opacity-75">
                    <strong>Jediga</strong> se reserva el derecho a modificar la presente Política de Cookies para adaptarla a cambios legislativos o técnicos. Cualquier modificación será publicada en esta misma página.
                </p>
            </div>           
        </div>
    </div>
</div>
@endsection