<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="text-white mb-2"><?php echo $title; ?></h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="?page=dashboard" class="text-white">Dashboard</a></li>
                            <li class="breadcrumb-item active text-white">Cuentas Completas</li>
                            <?php if (isset($platform)): ?>
                                <li class="breadcrumb-item active text-white"><?php echo ucfirst($platform); ?></li>
                            <?php endif; ?>
                        </ol>
                    </nav>
                </div>
                <div>
                    <?php if (isset($platform)): ?>
                        <a href="?page=cuentas&action=create&platform=<?php echo urlencode($platform); ?>" class="btn btn-success me-2">
                            ➕ Nueva Cuenta
                        </a>
                        <a href="?page=cuentas" class="btn btn-secondary">
                            ⬅️ Volver a Plataformas
                        </a>
                    <?php else: ?>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newPlatformModal">
                            ➕ Nueva Plataforma
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if (!isset($platform)): ?>
        <!-- Vista de plataformas -->
        <div class="row g-4">
            <?php if (empty($platforms)): ?>
                <div class="col-12">
                    <div class="card text-center">
                        <div class="card-body py-5">
                            <h3 class="text-muted mb-4">🎬 No hay plataformas registradas</h3>
                            <p class="text-muted mb-4">Comienza agregando tu primera plataforma de streaming</p>
                            <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#newPlatformModal">
                                ➕ Agregar Primera Plataforma
                            </button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($platforms as $platformName): ?>
                    <?php 
                    $platformCuentas = $this->cuentaModel->getByPlatform($platformName);
                    $totalCuentas = count($platformCuentas);
                    $activeCuentas = 0;
                    $expiredCuentas = 0;
                    $expiringCuentas = 0;
                    
                    foreach ($platformCuentas as $cuenta) {
                        $days = $this->cuentaModel->calculateDaysRemaining($cuenta['expiry_date']);
                        if ($days < 0) {
                            $expiredCuentas++;
                        } elseif ($days <= 7) {
                            $expiringCuentas++;
                            $activeCuentas++;
                        } else {
                            $activeCuentas++;
                        }
                    }
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <a href="?page=cuentas&platform=<?php echo urlencode($platformName); ?>" class="platform-card d-block">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h4 class="mb-0">
                                    <?php 
                                    $icons = [
                                        'netflix' => '🎬',
                                        'disney' => '🏰',
                                        'amazon' => '📦',
                                        'hbo' => '🎭',
                                        'paramount' => '⭐',
                                        'apple' => '🍎',
                                        'crunchyroll' => '🍜',
                                        'spotify' => '🎵',
                                        'youtube' => '📺'
                                    ];
                                    $icon = $icons[strtolower($platformName)] ?? '🎯';
                                    echo $icon . ' ' . ucfirst($platformName);
                                    ?>
                                </h4>
                                <span class="badge bg-light text-dark"><?php echo $totalCuentas; ?></span>
                            </div>
                            
                            <div class="row g-2 mb-3">
                                <div class="col-4 text-center">
                                    <div class="fw-bold"><?php echo $activeCuentas; ?></div>
                                    <small class="opacity-75">Activas</small>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="fw-bold text-warning"><?php echo $expiringCuentas; ?></div>
                                    <small class="opacity-75">Por Vencer</small>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="fw-bold text-danger"><?php echo $expiredCuentas; ?></div>
                                    <small class="opacity-75">Vencidas</small>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="opacity-75">Click para gestionar</small>
                                <div>
                                    <button class="btn btn-sm btn-light me-1" onclick="event.preventDefault(); event.stopPropagation(); editPlatform('<?php echo $platformName; ?>')">
                                        ✏️
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="event.preventDefault(); event.stopPropagation(); deletePlatform('<?php echo $platformName; ?>')">
                                        🗑️
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <!-- Vista de cuentas de una plataforma específica -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Cuentas de <?php echo ucfirst($platform); ?></h5>
                        <div>
                            <input type="text" id="searchInput" class="form-control d-inline-block w-auto me-2" placeholder="Buscar...">
                            <span class="badge bg-primary"><?php echo count($cuentas); ?> cuentas</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (empty($cuentas)): ?>
                            <div class="text-center py-5">
                                <h4 class="text-muted mb-3">No hay cuentas registradas para <?php echo ucfirst($platform); ?></h4>
                                <a href="?page=cuentas&action=create&platform=<?php echo urlencode($platform); ?>" class="btn btn-primary">
                                    ➕ Agregar Primera Cuenta
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Plataforma</th>
                                            <th>Email</th>
                                            <th>Contraseña</th>
                                            <th>Cliente</th>
                                            <th>Teléfono</th>
                                            <th>F. Contratación</th>
                                            <th>F. Vencimiento</th>
                                            <th>Días Restantes</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cuentas as $cuenta): ?>
                                            <?php $daysRemaining = $this->cuentaModel->calculateDaysRemaining($cuenta['expiry_date']); ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo ucfirst($cuenta['platform']); ?></strong>
                                                </td>
                                                <td>
                                                    <span class="text-truncate d-inline-block" style="max-width: 150px;" title="<?php echo $cuenta['email']; ?>">
                                                        <?php echo $cuenta['email']; ?>
                                                    </span>
                                                    <button class="btn btn-sm btn-outline-secondary ms-1" onclick="copyToClipboard('<?php echo $cuenta['email']; ?>', 'Email copiado')" title="Copiar email">
                                                        📋
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="password" class="form-control" id="pass_<?php echo $cuenta['id']; ?>" value="<?php echo $cuenta['password']; ?>" readonly>
                                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword(this)" data-target="pass_<?php echo $cuenta['id']; ?>" title="Mostrar/Ocultar">
                                                            👁️‍🗨️
                                                        </button>
                                                        <button class="btn btn-outline-secondary" onclick="copyToClipboard('<?php echo $cuenta['password']; ?>', 'Contraseña copiada')" title="Copiar contraseña">
                                                            📋
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    <strong><?php echo $cuenta['client_name']; ?></strong>
                                                </td>
                                                <td>
                                                    <a href="https://wa.me/<?php echo $cuenta['client_phone']; ?>" target="_blank" class="text-decoration-none">
                                                        <?php echo $cuenta['client_phone']; ?> 📱
                                                    </a>
                                                </td>
                                                <td><?php echo date('d/m/Y', strtotime($cuenta['contract_date'])); ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($cuenta['expiry_date'])); ?></td>
                                                <td>
                                                    <span class="days-remaining"><?php echo $daysRemaining; ?></span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-success btn-sm" onclick="activateAccount(<?php echo $cuenta['id']; ?>, 'cuentas')" title="Activar cuenta">
                                                            ✅ ACTIVAR
                                                        </button>
                                                        <button class="btn btn-warning btn-sm" onclick="notifyExpiry(<?php echo $cuenta['id']; ?>, 'cuentas')" title="Notificar vencimiento">
                                                            ⚠️ NOTIFICAR
                                                        </button>
                                                        <a href="?page=cuentas&action=edit&id=<?php echo $cuenta['id']; ?>" class="btn btn-info btn-sm" title="Editar">
                                                            ✏️ EDITAR
                                                        </a>
                                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete('?page=cuentas&action=delete&id=<?php echo $cuenta['id']; ?>', '¿Eliminar la cuenta de <?php echo $cuenta['client_name']; ?>?')" title="Eliminar">
                                                            🗑️ ELIMINAR
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal para nueva plataforma -->
<div class="modal fade" id="newPlatformModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">➕ Nueva Plataforma</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="?page=cuentas&action=create" method="GET">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="platformSelect" class="form-label">Seleccionar Plataforma</label>
                        <select class="form-select" id="platformSelect" name="platform" required>
                            <option value="">Selecciona una plataforma...</option>
                            <option value="netflix">🎬 Netflix</option>
                            <option value="disney">🏰 Disney+</option>
                            <option value="amazon">📦 Amazon Prime</option>
                            <option value="hbo">🎭 HBO Max</option>
                            <option value="paramount">⭐ Paramount+</option>
                            <option value="apple">🍎 Apple TV+</option>
                            <option value="crunchyroll">🍜 Crunchyroll</option>
                            <option value="spotify">🎵 Spotify</option>
                            <option value="youtube">📺 YouTube Premium</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="customPlatform" class="form-label">O escribe una plataforma personalizada</label>
                        <input type="text" class="form-control" id="customPlatform" placeholder="Nombre de la plataforma...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Continuar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Manejar selección de plataforma personalizada
document.getElementById('customPlatform').addEventListener('input', function() {
    const customValue = this.value.trim();
    const select = document.getElementById('platformSelect');
    
    if (customValue) {
        select.value = '';
        select.name = '';
        this.name = 'platform';
    } else {
        select.name = 'platform';
        this.name = '';
    }
});

document.getElementById('platformSelect').addEventListener('change', function() {
    if (this.value) {
        document.getElementById('customPlatform').value = '';
        document.getElementById('customPlatform').name = '';
        this.name = 'platform';
    }
});

// Funciones para gestionar plataformas
function editPlatform(platform) {
    Swal.fire({
        title: 'Editar Plataforma',
        input: 'text',
        inputValue: platform,
        inputLabel: 'Nombre de la plataforma',
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed && result.value.trim()) {
            // Aquí podrías implementar la lógica para renombrar la plataforma
            Toast.fire({
                icon: 'info',
                title: 'Función de edición en desarrollo'
            });
        }
    });
}

function deletePlatform(platform) {
    Swal.fire({
        title: '¿Eliminar plataforma?',
        text: `Se eliminarán todas las cuentas de ${platform}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí podrías implementar la lógica para eliminar la plataforma
            Toast.fire({
                icon: 'info',
                title: 'Función de eliminación en desarrollo'
            });
        }
    });
}
</script>
