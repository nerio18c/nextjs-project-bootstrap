<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="text-white text-center mb-4">
                🎬 Dashboard - StreamManager
            </h1>
            <p class="text-white text-center opacity-75 mb-5">
                Sistema de gestión integral para cuentas y proveedores de streaming
            </p>
        </div>
    </div>

    <!-- Tarjetas principales del dashboard -->
    <div class="row g-4 mb-5">
        <!-- CONTABILIDAD -->
        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="stats-card">
                        <h3 class="card-title mb-4">📊 CONTABILIDAD</h3>
                        
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="stats-number"><?php echo $data['contabilidad']['total_cuentas']; ?></div>
                                <div class="stats-label">Cuentas</div>
                            </div>
                            <div class="col-6">
                                <div class="stats-number"><?php echo $data['contabilidad']['total_perfiles']; ?></div>
                                <div class="stats-label">Perfiles</div>
                            </div>
                            <div class="col-6">
                                <div class="stats-number"><?php echo $data['contabilidad']['total_proveedores']; ?></div>
                                <div class="stats-label">Proveedores</div>
                            </div>
                            <div class="col-6">
                                <div class="stats-number"><?php echo $data['contabilidad']['total_activos']; ?></div>
                                <div class="stats-label">Activos</div>
                            </div>
                        </div>
                        
                        <hr class="my-4" style="border-color: rgba(255,255,255,0.3);">
                        
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="stats-number text-warning"><?php echo $data['contabilidad']['total_por_vencer']; ?></div>
                                <div class="stats-label">Por Vencer</div>
                            </div>
                            <div class="col-6">
                                <div class="stats-number text-danger"><?php echo $data['contabilidad']['total_vencidos']; ?></div>
                                <div class="stats-label">Vencidos</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CUENTAS COMPLETAS -->
        <div class="col-lg-3 col-md-6">
            <a href="?page=cuentas" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="stats-card">
                            <h3 class="card-title mb-4">🎯 CUENTAS COMPLETAS</h3>
                            
                            <div class="stats-number"><?php echo $data['cuentas_stats']['total']; ?></div>
                            <div class="stats-label mb-4">Total de Cuentas</div>
                            
                            <div class="row g-3">
                                <div class="col-4">
                                    <div class="stats-number text-success"><?php echo $data['cuentas_stats']['active']; ?></div>
                                    <div class="stats-label">Activas</div>
                                </div>
                                <div class="col-4">
                                    <div class="stats-number text-warning"><?php echo $data['cuentas_stats']['expiring']; ?></div>
                                    <div class="stats-label">Por Vencer</div>
                                </div>
                                <div class="col-4">
                                    <div class="stats-number text-danger"><?php echo $data['cuentas_stats']['expired']; ?></div>
                                    <div class="stats-label">Vencidas</div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <small class="opacity-75">Click para gestionar cuentas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- PERFILES -->
        <div class="col-lg-3 col-md-6">
            <a href="?page=perfiles" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="stats-card">
                            <h3 class="card-title mb-4">👤 PERFILES</h3>
                            
                            <div class="stats-number"><?php echo $data['perfiles_stats']['total']; ?></div>
                            <div class="stats-label mb-4">Total de Perfiles</div>
                            
                            <div class="row g-3">
                                <div class="col-4">
                                    <div class="stats-number text-success"><?php echo $data['perfiles_stats']['active']; ?></div>
                                    <div class="stats-label">Activos</div>
                                </div>
                                <div class="col-4">
                                    <div class="stats-number text-warning"><?php echo $data['perfiles_stats']['expiring']; ?></div>
                                    <div class="stats-label">Por Vencer</div>
                                </div>
                                <div class="col-4">
                                    <div class="stats-number text-danger"><?php echo $data['perfiles_stats']['expired']; ?></div>
                                    <div class="stats-label">Vencidos</div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <small class="opacity-75">Click para gestionar perfiles</small>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- PROVEEDORES -->
        <div class="col-lg-3 col-md-6">
            <a href="?page=proveedores" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="stats-card">
                            <h3 class="card-title mb-4">🏢 PROVEEDORES</h3>
                            
                            <div class="stats-number"><?php echo $data['proveedores_stats']['total_proveedores']; ?></div>
                            <div class="stats-label mb-4">Total Proveedores</div>
                            
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="stats-number text-info"><?php echo $data['proveedores_stats']['total_plataformas']; ?></div>
                                    <div class="stats-label">Plataformas</div>
                                </div>
                                <div class="col-6">
                                    <div class="stats-number text-success"><?php echo $data['proveedores_stats']['active']; ?></div>
                                    <div class="stats-label">Activas</div>
                                </div>
                            </div>
                            
                            <div class="row g-3 mt-2">
                                <div class="col-6">
                                    <div class="stats-number text-warning"><?php echo $data['proveedores_stats']['expiring']; ?></div>
                                    <div class="stats-label">Por Vencer</div>
                                </div>
                                <div class="col-6">
                                    <div class="stats-number text-danger"><?php echo $data['proveedores_stats']['expired']; ?></div>
                                    <div class="stats-label">Vencidas</div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <small class="opacity-75">Click para gestionar proveedores</small>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Alertas de vencimientos próximos -->
    <?php if (!empty($data['expiring']['cuentas']) || !empty($data['expiring']['perfiles']) || !empty($data['expiring']['plataformas'])): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">⚠️ Alertas de Vencimiento (Próximos 7 días)</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Cuentas por vencer -->
                        <?php if (!empty($data['expiring']['cuentas'])): ?>
                        <div class="col-md-4">
                            <h6 class="text-primary">🎯 Cuentas Completas</h6>
                            <?php foreach ($data['expiring']['cuentas'] as $cuenta): ?>
                                <?php 
                                $cuentaModel = new Cuenta();
                                $days = $cuentaModel->calculateDaysRemaining($cuenta['expiry_date']); 
                                ?>
                                <div class="alert alert-warning py-2 mb-2">
                                    <small>
                                        <strong><?php echo $cuenta['platform']; ?></strong><br>
                                        <?php echo $cuenta['client_name']; ?><br>
                                        <span class="badge bg-danger"><?php echo $days; ?> días</span>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Perfiles por vencer -->
                        <?php if (!empty($data['expiring']['perfiles'])): ?>
                        <div class="col-md-4">
                            <h6 class="text-success">👤 Perfiles</h6>
                            <?php foreach ($data['expiring']['perfiles'] as $perfil): ?>
                                <?php 
                                $perfilModel = new Perfil();
                                $days = $perfilModel->calculateDaysRemaining($perfil['expiry_date']); 
                                ?>
                                <div class="alert alert-warning py-2 mb-2">
                                    <small>
                                        <strong><?php echo $perfil['platform']; ?></strong><br>
                                        <?php echo $perfil['client_name']; ?> - <?php echo $perfil['profile_name']; ?><br>
                                        <span class="badge bg-danger"><?php echo $days; ?> días</span>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Plataformas de proveedores por vencer -->
                        <?php if (!empty($data['expiring']['plataformas'])): ?>
                        <div class="col-md-4">
                            <h6 class="text-info">🏢 Proveedores</h6>
                            <?php foreach ($data['expiring']['plataformas'] as $plataforma): ?>
                                <?php 
                                $proveedorModel = new Proveedor();
                                $days = $proveedorModel->calculateDaysRemaining($plataforma['expiry_date']); 
                                ?>
                                <div class="alert alert-warning py-2 mb-2">
                                    <small>
                                        <strong><?php echo $plataforma['platform']; ?></strong><br>
                                        <?php echo $plataforma['supplier_name']; ?><br>
                                        <span class="badge bg-danger"><?php echo $days; ?> días</span>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Accesos rápidos -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">🚀 Accesos Rápidos</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="?page=cuentas&action=create" class="btn btn-primary w-100">
                                ➕ Nueva Cuenta Completa
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="?page=perfiles&action=create" class="btn btn-success w-100">
                                👤 Nuevo Perfil
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="?page=proveedores&action=create&type=proveedor" class="btn btn-info w-100">
                                🏢 Nuevo Proveedor
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-warning w-100" onclick="updateStats()">
                                🔄 Actualizar Estadísticas
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Función específica para actualizar estadísticas del dashboard
function updateDashboardStats() {
    fetch('?page=dashboard&action=getStats')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Toast.fire({
                    icon: 'success',
                    title: 'Estadísticas actualizadas correctamente'
                });
                // Recargar la página para mostrar los datos actualizados
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Error al actualizar estadísticas'
                });
            }
        })
        .catch(error => {
            Toast.fire({
                icon: 'error',
                title: 'Error de conexión'
            });
        });
}

// Actualizar estadísticas automáticamente cada 10 minutos
setInterval(updateDashboardStats, 600000);
</script>
