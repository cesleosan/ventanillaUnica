<div class="text-center mb-16">
    <h2 class="text-4xl md:text-5xl font-black text-tlalpan-vino tracking-tight mb-2">
        Bienvenido a Tlalpan Alcaldia Virtual
    </h2>
    <p class="text-xl md:text-2xl font-light text-tlalpan-accent">
        La Modernidad al servicio de Usted
    </p>
</div>

<div class="flex flex-wrap justify-center gap-8 mb-16">

    <div class="w-full max-w-xl bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row hover:scale-[1.02] transition-all duration-300 group">
        <div class="md:w-2/5 h-56 md:h-auto relative overflow-hidden">
            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=600" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
            <div class="absolute inset-0 bg-tlalpan-vino/20 mix-blend-multiply"></div>
        </div>
        <div class="md:w-3/5 p-8 flex flex-col justify-between">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 leading-tight">Atención Ciudadana Virtual</h3>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Ahora con el Sistema de Atención Ciudadana Virtual usted podrá realizar diversos servicios por Internet.
                </p>
            </div>
        </div>
    </div>

    <?php if ($data['is_logged_in']): ?>
    <div class="w-full max-w-xl bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row hover:scale-[1.02] transition-all duration-300 group animate-fade-in-up">
        <div class="md:w-2/5 h-56 md:h-auto relative overflow-hidden">
            <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&q=80&w=600" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
            <div class="absolute inset-0 bg-tlalpan-vino/20 mix-blend-multiply"></div>
        </div>
        <div class="md:w-3/5 p-8 flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-2xl font-bold text-gray-900 leading-tight">Ventanilla Única</h3>
                    <svg class="w-6 h-6 text-tlalpan-accent opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Consulte por Internet requisitos, tiempos y costos para realizar sus trámites.
                </p>
            </div>
            <a href="#" class="block w-full text-center bg-white text-tlalpan-vino border-2 border-tlalpan-vino font-bold py-3 rounded-xl hover:bg-tlalpan-vino hover:text-white transition shadow-sm tracking-wide">
                CONSULTAR TRÁMITE
            </a>
        </div>
    </div>
    <?php endif; ?>

</div>

<div class="max-w-4xl mx-auto space-y-6 text-lg leading-relaxed text-gray-600 text-justify">
    <p>
        Desde la comodidad de su casa u oficina y sin tener que presentarse a las oficinas de la Alcaldia de Tlalpan, ahora podrá realizar consultas, ingresar servicios, gestionar trámites y darles seguimiento de manera cómoda y segura.
    </p>
    <p>
        Estos servicios cuentan con un área pública donde cualquier Ciudadano podrá ingresar y realizar diversas consultas de su interés. Por otro lado si usted quisiera registrar algún servicio o consultar el estatus de algún trámite, deberá estar registrado y contar con una clave de usuario y una contraseña. Si aún no cuenta con ella haga <a href="#" class="font-bold text-tlalpan-vino underline hover:text-tlalpan-accent transition">click aquí</a> en el botón "Regístrese" para registrarse en forma gratuita.
    </p>
</div>