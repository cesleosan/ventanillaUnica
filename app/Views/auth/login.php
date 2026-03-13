<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 p-4 sm:p-6 relative overflow-hidden">
    
    <div class="absolute inset-0 pointer-events-none opacity-[0.03] z-0">
        <svg width="100%" height="100%"><rect width="100%" height="100%" fill="url(#grid-login)" /><defs><pattern id="grid-login" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="#773357" stroke-width="1"/></pattern></defs></svg>
    </div>

    <div class="max-w-md mx-auto w-full relative z-10">
        
        <div class="bg-white rounded-[2rem] sm:rounded-[2.5rem] shadow-[0_25px_60px_-15px_rgba(119,51,87,0.15)] overflow-hidden border border-gray-100">
            
            <div class="bg-tlalpan-vino p-8 sm:p-10 text-center relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                
                <div class="inline-block bg-white p-4 rounded-2xl shadow-lg mb-6">
                    <img src="/logos/Logo AT Vertical guinda 100 PX.png" alt="Alcaldía Tlalpan" class="h-16 sm:h-20 w-auto object-contain">
                </div>
                
                <h2 class="text-white text-xl sm:text-2xl font-black tracking-tight uppercase">Ventanilla Única</h2>
                <div class="flex items-center justify-center gap-2 mt-2">
                    <span class="h-[1px] w-6 sm:w-8 bg-white/30"></span>
                    <p class="text-white/70 text-[9px] sm:text-[10px] font-bold uppercase tracking-[0.2em] sm:tracking-[0.3em]">Acceso Administrativo</p>
                    <span class="h-[1px] w-6 sm:w-8 bg-white/30"></span>
                </div>
            </div>

            <div class="p-6 sm:p-10">
                <?php if (!empty($data['error'])): ?>
                    <div class="mb-6 p-4 bg-red-50 text-red-700 text-xs rounded-xl border-r-4 border-red-500 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold"><?= $data['error'] ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/" class="space-y-5 sm:space-y-6">
                    <input type="hidden" name="action" value="login">

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1 tracking-widest">Usuario del Sistema</label>
                        <input type="text" name="username" placeholder="EJ. SISTEMAS" 
                            class="input-tlalpan w-full px-5 py-3.5 sm:py-4 text-sm font-semibold outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1 tracking-widest">Contraseña</label>
                        <input type="password" name="password" placeholder="••••••••" 
                            class="input-tlalpan w-full px-5 py-3.5 sm:py-4 text-sm outline-none">
                    </div>

                    <div class="bg-[#FCF7F9] p-4 rounded-2xl border border-[#E6D4DD]">
                        <label class="block text-[9px] font-black text-tlalpan-accent uppercase mb-3 text-center tracking-tighter">Verificación de Seguridad</label>
                        <div class="flex flex-col sm:flex-row items-center gap-3">
                            <div class="relative cursor-pointer overflow-hidden rounded-xl bg-white border border-[#E6D4DD] w-full sm:w-[140px] h-12 flex-shrink-0" onclick="window.location.reload();">
                                <?php if (!empty($data['captcha_image'])): ?>
                                    <img src="<?= $data['captcha_image'] ?>" alt="CAPTCHA" class="h-full w-full object-fill block">
                                    <div class="absolute inset-0 bg-tlalpan-vino/5 opacity-0 hover:opacity-100 flex items-center justify-center transition-opacity">
                                        <svg class="w-5 h-5 text-tlalpan-vino" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <input type="text" name="captcha" placeholder="CÓDIGO" 
                                class="w-full sm:flex-1 bg-white border border-[#E6D4DD] rounded-xl px-4 py-3 text-center font-mono font-black text-lg text-tlalpan-vino focus:border-tlalpan-vino outline-none tracking-widest uppercase h-12">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-tlalpan-vino hover:bg-[#5a2540] text-white font-black py-4 rounded-2xl shadow-[0_10px_25px_-5px_rgba(119,51,87,0.4)] transition-all transform active:scale-95 uppercase tracking-[0.2em] text-xs">
                        Iniciar Sesión
                    </button>
                </form>
            </div>
            
            <div class="bg-gray-50 px-8 py-6 text-center border-t border-gray-100">
                <p class="text-[11px] text-gray-400 font-medium tracking-tight">
                    ¿Problemas de acceso? <a href="#" class="text-tlalpan-vino font-black hover:underline">Contactar a Soporte</a>
                </p>
            </div>
        </div>

        <div class="mt-8 flex flex-col items-center gap-2 opacity-40">
            <p class="text-[9px] font-black text-gray-500 uppercase tracking-[0.4em]">Ventanilla Única</p>
            <div class="h-1 w-12 bg-tlalpan-vino rounded-full"></div>
        </div>
    </div>
</div>