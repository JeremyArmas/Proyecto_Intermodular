@extends('layouts.app')

@section('title', 'Terminos y condiciones de uso - Jediga')

@section('content')
<div class="container py-5" style="margin-top: 100px; min-height: 50vh;">
    <!-- Contenedor con estilo para el texto legal -->
    <div class="row bg-dark text-white rounded p-5 shadow">
        <div class="col-12">
            <h1 class="fw-bold text-warning mb-4">Terminos y condiciones de uso</h1>
            
            <!-- Información general -->
            <div class="mb-4">
                <h4 class="fw-bold">Información general</h4>
                <p class="opacity-75">
                    El presente documento regula las condiciones de uso del sitio web Jediga, del que es titular:
                </p>
                <ul class="opacity-75">
                    <li><strong>Titular:</strong> Jediga</li>
                    <li><strong>Nombre de la empresa:</strong> Jediga</li>
                    <li><strong>NIF/CIF:</strong> 513658799T</li>
                    <li><strong>Domicilio:</strong> Las Palmas de G.C, España </li>
                    <li><strong>Correo electrónico:</strong> jedigasupport@gmail.com</li>
                    <li><strong>Teléfono:</strong> 666 666 666</li>
                </ul>
            </div>

            <!-- Objeto del sitio web -->
            <div class="mb-4">
                <h4 class="fw-bold">Objeto del sitio web</h4>
                <p class="opacity-75">
                    El sitio web tiene como finalidad proporcionar información sobre los servicios, productos y 
                    actividades de <strong>Jediga</strong>, así como permitir el contacto con la empresa a través de formularios u otros medios habilitados.
                </p>
            </div>

            <!-- Condiciones de acceso y uso -->
            <div class="mb-4">
                <h4 class="fw-bold">Condiciones de acceso y uso</h4>
                <p class="opacity-75">
                    El acceso al sitio web es gratuito, salvo en lo relativo al coste de la conexión a internet del usuario.
                    El usuario se compromete a:
                    <ul>
                        <li>Utilizar el sitio web de forma lícita y conforme a la normativa vigente</li>
                        <li>No realizar actividades que puedan dañar, sobrecargar o deteriorar el sitio web</li>
                        <li>No introducir virus, malware u otros sistemas dañinos</li>
                    </ul>
                </p>
            </div>

            <!-- Uso de formularios -->
            <div class="mb-4">
                <h4 class="fw-bold">Uso de formularios</h4>
                <p class="opacity-75">
                    El usuario garantiza que los datos facilitados a través de los formularios son veraces, completos y actualizados.
                    El uso de los formularios no implica ninguna relación contractual automática, salvo que se indique expresamente lo contrario.
                </p>
            </div>

            <!-- PROPIEDAD INTELECTUAL E INDUSTRIAL -->
            <div class="mb-4">
                <h4 class="fw-bold">Propiedad intelectual e industrial</h4>
                <p class="opacity-75">
                    Todos los contenidos del sitio web (textos, imágenes, vídeos, logotipos, diseño, estructura, código fuente, etc.) son titularidad 
                    de <strong>Jediga</strong> o de terceros con licencia, y están protegidos por la normativa de propiedad intelectual e industrial.
                    Queda prohibida la reproducción, distribución o comunicación pública sin autorización expresa del titular.
                </p>
            </div>

            <!-- Responsabilidad -->
            <div class="mb-4">
                <h4 class="fw-bold">Responsabilidad</h4>
                <p class="opacity-75">
                    <strong>Jediga</strong> no se hace responsable de los daños y perjuicios que puedan derivarse del uso del sitio web, incluyendo daños
                     directos, indirectos, incidentales, punitivos o consecuentes. Tampoco se hace responsable de la exactitud, veracidad o integridad de
                      la información contenida en el sitio web, ni de los errores u omisiones en el mismo.
                </p>
            </div>

            <!-- ENLACES A TERCEROS -->
            <div class="mb-4">
                <h4 class="fw-bold">ENLACES A TERCEROS</h4>
                <p class="opacity-75">
                    El sitio web puede incluir enlaces a sitios web de terceros. <strong>Jediga</strong> no se responsabiliza del contenido, servicios o 
                    políticas de privacidad de dichos sitios externos.
                </p>
            </div>

            <!--USO DE COOKIES -->
            <div class="mb-4">
                <h4 class="fw-bold">USO DE COOKIES</h4>
                <p class="opacity-75">
                    El sitio web utiliza cookies para mejorar la experiencia del usuario. Al navegar por el sitio, el usuario acepta el uso de cookies. 
                    Para más información, consulte nuestra Política de Cookies.
                </p>
            </div>

            <!-- Modificaciones -->
            <div class="mb-4">
                <h4 class="fw-bold">Modificaciones</h4>
                <p class="opacity-75">
                    <strong>Jediga</strong> se reserva el derecho de modificar estos Términos y Condiciones en cualquier momento. Las modificaciones entrarán
                     en vigor inmediatamente después de su publicación en el sitio web.
                </p>
            </div>

            <!-- DURACIÓN Y TERMINACIÓN -->
            <div class="mb-4">
                <h4 class="fw-bold">DURACIÓN Y TERMINACIÓN</h4>
                <p class="opacity-75">
                    Estos Términos y Condiciones tienen una duración indefinida y permanecerán en vigor mientras el usuario utilice el sitio web. 
                    <strong>Jediga</strong> se reserva el derecho de suspender o interrumpir el acceso al sitio web en cualquier momento y sin previo aviso,
                     en caso de incumplimiento de estos términos.
                </p>
            </div>

            <!-- LEGISLACIÓN APLICABLE Y JURISDICCIÓN-->
            <div class="mb-4">
                <h4 class="fw-bold">LEGISLACIÓN APLICABLE Y JURISDICCIÓN</h4>
                <p class="opacity-75">
                    Estos Términos y Condiciones se regirán e interpretarán de acuerdo con las leyes de España. Cualquier controversia que surja en relación
                    con estos términos será sometida a la jurisdicción exclusiva de los tribunales de Las Palmas de G.C.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection