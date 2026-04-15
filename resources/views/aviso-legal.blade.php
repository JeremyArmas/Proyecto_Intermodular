@extends('layouts.app')

@section('title', 'Aviso Legal - Jediga')

@section('content')
<div class="container py-5" style="margin-top: 100px; min-height: 50vh;">
    <!-- Contenedor con estilo para el texto legal -->
    <div class="row bg-dark text-white rounded p-5 shadow">
        <div class="col-12">
            <h1 class="fw-bold text-warning mb-4">Aviso Legal</h1>
            
            <!-- Datos identificativos -->
            <div class="mb-4">
                <h4 class="fw-bold">Datos identificativos</h4>
                <p class="opacity-75">
                    En cumplimiento con el deber de información recogido en la Ley 34/2002, de 11 de julio, de Servicios 
                    de la Sociedad de la Información y del Comercio Electrónico (LSSI-CE), se facilitan a continuación los
                    siguientes datos identificativos del titular del sitio web:
                </p>
                <ul class="opacity-75">
                    <li><strong>Titular:</strong> Jediga</li>
                    <li><strong>Nombre de la empresa:</strong> Jediga</li>
                    <li><strong>NIF/CIF:</strong> 513658799T</li>
                    <li><strong>Domicilio:</strong> Las Palmas de G.C, España </li>
                    <li><strong>Correo electrónico:</strong> jedigasupport@gmail.com</li>
                    <li><strong>Teléfono:</strong> 666 666 666</li>
                    <li><strong>Actividad económica:</strong> Venta de videojuegos</li>
                </ul>
            </div>

            <!-- Condiciones de uso -->
            <div class="mb-4">
                <h4 class="fw-bold">Condiciones de uso</h4>
                <p class="opacity-75">
                    El acceso y uso de este sitio web atribuye la condición de usuario, implicando la aceptación plena 
                    y sin reservas de todas las disposiciones incluidas en el presente Aviso Legal.
                    El usuario se compromete a hacer un uso adecuado del sitio web y a no emplearlo para actividades ilícitas,
                    ilegales o contrarias a la buena fe y al orden público.
                </p>
            </div>

            <!-- Propiedad intelectual -->
            <div class="mb-4">
                <h4 class="fw-bold">Propiedad intelectual</h4>
                <p class="opacity-75">
                    Todos los contenidos del sitio web (textos, imágenes, diseños, logotipos, código fuente, estructura, etc.) 
                    son titularidad de <strong>Jediga</strong> o de terceros con licencia, estando protegidos por la normativa vigente en materia
                     de propiedad intelectual e industrial.
                     Queda expresamente prohibida la reproducción, distribución o modificación total o parcial sin la 
                     autorización expresa del titular.
                </p>
            </div>

            <!-- Responsabilidad -->
            <div class="mb-4">
                <h4 class="fw-bold">Responsabilidad</h4>
                <p class="opacity-75">
                    <strong>Jediga</strong> no se hace responsable de los errores u omisiones en los contenidos ni de los daños derivados 
                    del uso del sitio web, ni por actuaciones realizadas en base a la información que en él se facilita. Asimismo, no se garantiza 
                    la ausencia de virus u otros elementos que puedan causar alteraciones en los sistemas informáticos del usuario.
                </p>
            </div>

            <!-- Protección de datos personales -->
            <div class="mb-4">
                <h4 class="fw-bold">Protección de datos personales</h4>
                <p class="opacity-75">
                    De conformidad con lo dispuesto en el Reglamento (UE) 2016/679 (RGPD) y la Ley Orgánica 3/2018 (LOPDGDD), se informa al usuario 
                    de que los datos personales que facilite a través del sitio web serán tratados conforme a lo establecido en la Política de Privacidad.
                    Los datos recogidos mediante formularios de contacto serán tratados con la finalidad de gestionar consultas, solicitudes de información
                     o comunicaciones comerciales, siendo la base legal el consentimiento del usuario.
                    El usuario podrá ejercer sus derechos de acceso, rectificación, supresión, oposición, limitación y portabilidad enviando una solicitud a
                    <strong>jedigasupport@gmail.com</strong>, adjuntando copia de su documento de identidad.
                </p>
            </div>

            <!--Enlaces a terceros-->
            <div class="mb-4">
                <h4 class="fw-bold">Enlaces a terceros</h4>
                <p class="opacity-75">
                    El sitio web puede contener enlaces a sitios web de terceros. <strong>Jediga</strong> no se hace responsable de los contenidos
                    ni de las políticas de privacidad de dichos sitios web.
                </p>
            </div>

            <!--Ley aplicable y jurisdicción-->
            <div class="mb-4">
                <h4 class="fw-bold">Ley aplicable y jurisdicción</h4>
                <p class="opacity-75">
                La relación entre <strong>Jediga</strong> y el usuario se regirá por la normativa española vigente. Para la resolución de cualquier controversia, ambas partes
                 se someten a los juzgados y tribunales de <strong>Las Palmas de gran canarias , Las Palmas</strong>, salvo que la normativa aplicable disponga lo contrario.
                </p>
            </div>

            <!--Aviso de que es una página no oficial-->
             <div class="mb-4">
                <h4 class="fw-bold">Aviso de que es una página no oficial</h4>
                <p class="opacity-75">
                Esta página web es una página no oficial creada por alumnos de grado superior en desarrollo de aplicaciones web para 
                fines educativos. No se va a adquirir juegos reales ni nada por el estilo.
                </p>
            </div>

            <!--Aviso a las empresas-->
            <div class="mb-4">
                <h4 class="fw-bold">Aviso a las empresas</h4>
                <p class="opacity-75">
                Si eres una empresa y no quieres que tu juego aparezca en nuestra página web, por favor, ponte en contacto con nosotros a través de nuestro correo electrónico <strong>jedigasupport@gmail.com</strong> y lo retiraremos lo antes posible.
                </p>
            </div>

            <!--Aviso a las empresas sobre los pedidos-->
            <div class="mb-4">
                <h4 class="fw-bold">Aviso a las empresas sobre los pedidos</h4>
                <p class="opacity-75">
                    Desde el momento que una empresa haya solicitado un pedido, no se podrá cancelar el pedido. Puede solicitar no recibir el producto sin embargo , el dinero no serña devuelto.
                </p>
            </div>

        </div>
    </div>
</div>
@endsection