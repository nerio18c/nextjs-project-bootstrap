<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="text-white mb-2">➕ Nueva Cuenta Completa</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="?page=dashboard" class="text-white">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="?page=cuentas" class="text-white">Cuentas Completas</a></li>
                            <li class="breadcrumb-item active text-white">Nueva Cuenta</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="?page=cuentas<?php echo $platform ? '&platform=' . urlencode($platform) : ''; ?>" class="btn btn-secondary">
                        ⬅️ Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">🎬 Información de la Cuenta</h5>
                </div>
                <div class="card-body">
                    <form action="?page=cuentas&action=store" method="POST" id="createForm">
                        <div class="row g-3">
                            <!-- Plataforma -->
                            <div class="col-md-6">
                                <label for="platform" class="form-label">
                                    <strong>Plataforma *</strong>
                                </label>
                                <select class="form-select" id="platform" name="platform" required>
                                    <option value="">Selecciona una plataforma...</option>
                                    <option value="netflix" <?php echo ($platform === 'netflix') ? 'selected' : ''; ?>>🎬 Netflix</option>
                                    <option value="disney" <?php echo ($platform === 'disney') ? 'selected' : ''; ?>>🏰 Disney+</option>
                                    <option value="amazon" <?php echo ($platform === 'amazon') ? 'selected' : ''; ?>>📦 Amazon Prime</option>
                                    <option value="hbo" <?php echo ($platform === 'hbo') ? 'selected' : ''; ?>>🎭 HBO Max</option>
                                    <option value="paramount" <?php echo ($platform === 'paramount') ? 'selected' : ''; ?>>⭐ Paramount+</option>
                                    <option value="apple" <?php echo ($platform === 'apple') ? 'selected' : ''; ?>>🍎 Apple TV+</option>
                                    <option value="crunchyroll" <?php echo ($platform === 'crunchyroll') ? 'selected' : ''; ?>>🍜 Crunchyroll</option>
                                    <option value="spotify" <?php echo ($platform === 'spotify') ? 'selected' : ''; ?>>🎵 Spotify</option>
                                    <option value="youtube" <?php echo ($platform === 'youtube') ? 'selected' : ''; ?>>📺 YouTube Premium</option>
                                </select>
                                <div class="form-text">Selecciona la plataforma de streaming</div>
                            </div>

                            <!-- Plataforma personalizada -->
                            <div class="col-md-6">
                                <label for="customPlatform" class="form-label">
                                    <strong>Plataforma Personalizada</strong>
                                </label>
                                <input type="text" class="form-control" id="customPlatform" placeholder="Escribe el nombre de otra plataforma...">
                                <div class="form-text">O escribe una plataforma no listada</div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    <strong>Email de la Cuenta *</strong>
                                </label>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="ejemplo@correo.com">
                                <div class="form-text">Email usado para acceder a la plataforma</div>
                            </div>

                            <!-- Contraseña -->
                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    <strong>Contraseña *</strong>
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required placeholder="Contraseña de la cuenta">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword(this)" data-target="password">
                                        👁️‍🗨️
                                    </button>
                                    <button class="btn btn-outline-secondary" type="button" onclick="generatePassword()" title="Generar contraseña">
                                        🎲
                                    </button>
                                </div>
                                <div class="form-text">Contraseña para acceder a la cuenta</div>
                            </div>

                            <!-- Nombre del cliente -->
                            <div class="col-md-6">
                                <label for="client_name" class="form-label">
                                    <strong>Nombre del Cliente *</strong>
                                </label>
                                <input type="text" class="form-control" id="client_name" name="client_name" required placeholder="Nombre completo del cliente">
                                <div class="form-text">Nombre de la persona que contrató el servicio</div>
                            </div>

                            <!-- Teléfono del cliente -->
                            <div class="col-md-6">
                                <label for="client_phone" class="form-label">
                                    <strong>Teléfono del Cliente *</strong>
                                </label>
                                <input type="tel" class="form-control" id="client_phone" name="client_phone" required placeholder="1234567890" pattern="[0-9]{10,15}">
                                <div class="form-text">Número de WhatsApp (solo números, sin espacios ni símbolos)</div>
                            </div>

                            <!-- Fecha de contratación -->
                            <div class="col-md-6">
                                <label for="contract_date" class="form-label">
                                    <strong>Fecha de Contratación *</strong>
                                </label>
                                <input type="date" class="form-control" id="contract_date" name="contract_date" required value="<?php echo date('Y-m-d'); ?>">
                                <div class="form-text">Fecha en que se contrató el servicio</div>
                            </div>

                            <!-- Fecha de vencimiento -->
                            <div class="col-md-6">
                                <label for="expiry_date" class="form-label">
                                    <strong>Fecha de Vencimiento *</strong>
                                </label>
                                <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
                                <div class="form-text">Fecha en que vence la suscripción</div>
                            </div>

                            <!-- Duración calculada -->
                            <div class="col-12">
                                <div class="alert alert-info" id="durationAlert" style="display: none;">
                                    <strong>Duración del servicio:</strong> <span id="durationText"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="?page=cuentas<?php echo $platform ? '&platform=' . urlencode($platform) : ''; ?>" class="btn btn-secondary">
                                        ❌ Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        ✅ Crear Cuenta
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de ayuda -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">💡 Consejos</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">📧 Email</h6>
                        <small class="text-muted">
                            Usa el email exacto de la cuenta de streaming. Este será compartido con el cliente.
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-success">🔐 Contraseña</h6>
                        <small class="text-muted">
                            Puedes generar una contraseña segura usando el botón 🎲 o escribir una personalizada.
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-info">📱 WhatsApp</h6>
                        <small class="text-muted">
                            El número debe incluir código de país sin el símbolo +. Ejemplo: 521234567890
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-warning">📅 Fechas</h6>
                        <small class="text-muted">
                            El sistema calculará automáticamente los días restantes y enviará alertas de vencimiento.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Plantillas rápidas -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">⚡ Plantillas Rápidas</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="setDuration(30)">
                            📅 1 Mes (30 días)
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="setDuration(90)">
                            📅 3 Meses (90 días)
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="setDuration(180)">
                            📅 6 Meses (180 días)
                        </button>
                        <button class="btn btn-outline-warning btn-sm" onclick="setDuration(365)">
                            📅 1 Año (365 días)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Manejar plataforma personalizada
document.getElementById('customPlatform').addEventListener('input', function() {
    const customValue = this.value.trim();
    const select = document.getElementById('platform');
    
    if (customValue) {
        select.value = '';
        select.name = '';
        this.name = 'platform';
    } else {
        select.name = 'platform';
        this.name = '';
    }
});

document.getElementById('platform').addEventListener('change', function() {
    if (this.value) {
        document.getElementById('customPlatform').value = '';
        document.getElementById('customPlatform').name = '';
        this.name = 'platform';
    }
});

// Calcular duración automáticamente
function calculateDuration() {
    const contractDate = document.getElementById('contract_date').value;
    const expiryDate = document.getElementById('expiry_date').value;
    
    if (contractDate && expiryDate) {
        const start = new Date(contractDate);
        const end = new Date(expiryDate);
        const diffTime = end - start;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        const alert = document.getElementById('durationAlert');
        const text = document.getElementById('durationText');
        
        if (diffDays > 0) {
            text.textContent = `${diffDays} días`;
            alert.className = 'alert alert-success';
            alert.style.display = 'block';
        } else if (diffDays === 0) {
            text.textContent = 'Vence hoy';
            alert.className = 'alert alert-warning';
            alert.style.display = 'block';
        } else {
            text.textContent = `Fecha inválida (${Math.abs(diffDays)} días en el pasado)`;
            alert.className = 'alert alert-danger';
            alert.style.display = 'block';
        }
    } else {
        document.getElementById('durationAlert').style.display = 'none';
    }
}

// Event listeners para fechas
document.getElementById('contract_date').addEventListener('change', calculateDuration);
document.getElementById('expiry_date').addEventListener('change', calculateDuration);

// Función para establecer duración rápida
function setDuration(days) {
    const contractDate = document.getElementById('contract_date').value || new Date().toISOString().split('T')[0];
    document.getElementById('contract_date').value = contractDate;
    
    const start = new Date(contractDate);
    start.setDate(start.getDate() + days);
    
    document.getElementById('expiry_date').value = start.toISOString().split('T')[0];
    calculateDuration();
}

// Generar contraseña aleatoria
function generatePassword() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
    let password = '';
    for (let i = 0; i < 12; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('password').value = password;
    
    Toast.fire({
        icon: 'success',
        title: 'Contraseña generada'
    });
}

// Validación del formulario
document.getElementById('createForm').addEventListener('submit', function(e) {
    const contractDate = new Date(document.getElementById('contract_date').value);
    const expiryDate = new Date(document.getElementById('expiry_date').value);
    
    if (expiryDate <= contractDate) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error en las fechas',
            text: 'La fecha de vencimiento debe ser posterior a la fecha de contratación'
        });
        return false;
    }
    
    // Validar que se haya seleccionado una plataforma
    const platform = document.getElementById('platform').value;
    const customPlatform = document.getElementById('customPlatform').value;
    
    if (!platform && !customPlatform.trim()) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Plataforma requerida',
            text: 'Debes seleccionar una plataforma o escribir una personalizada'
        });
        return false;
    }
    
    // Mostrar confirmación
    e.preventDefault();
    Swal.fire({
        title: '¿Crear cuenta?',
        text: 'Se creará la nueva cuenta con los datos ingresados',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, crear',
        cancelButtonText: 'Revisar'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});

// Formatear número de teléfono
document.getElementById('client_phone').addEventListener('input', function() {
    // Remover todo excepto números
    this.value = this.value.replace(/\D/g, '');
});

// Calcular duración inicial si hay fechas
document.addEventListener('DOMContentLoaded', function() {
    calculateDuration();
});
</script>
