<!-- Footer -->
    <footer class="mt-5 py-4 text-center">
        <div class="container">
            <p class="text-white mb-0">
                &copy; <?php echo date('Y'); ?> StreamManager - Sistema de Gestión de Cuentas Streaming
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="assets/js/main.js"></script>
    
    <script>
        // Configuración global de SweetAlert2
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Función para confirmar eliminación
        function confirmDelete(url, message = '¿Estás seguro de que deseas eliminar este elemento?') {
            Swal.fire({
                title: '¿Estás seguro?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: 'rgba(255, 255, 255, 0.95)',
                backdrop: 'rgba(0, 0, 0, 0.4)'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }

        // Función para activar cuenta (WhatsApp)
        function activateAccount(id, type = 'cuentas') {
            fetch(`?page=${type}&action=activate&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.open(data.url, '_blank');
                        Toast.fire({
                            icon: 'success',
                            title: 'Mensaje de activación generado'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Error: ' + data.error
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

        // Función para notificar vencimiento (WhatsApp)
        function notifyExpiry(id, type = 'cuentas') {
            fetch(`?page=${type}&action=notify&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.open(data.url, '_blank');
                        Toast.fire({
                            icon: 'success',
                            title: 'Notificación de vencimiento generada'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Error: ' + data.error
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

        // Función para calcular días restantes y aplicar colores
        function updateDaysRemaining() {
            document.querySelectorAll('.days-remaining').forEach(element => {
                const days = parseInt(element.textContent);
                
                if (days < 0) {
                    element.className = 'badge bg-danger days-remaining';
                    element.textContent = Math.abs(days) + ' días vencido';
                } else if (days <= 3) {
                    element.className = 'badge bg-danger days-remaining';
                } else if (days <= 7) {
                    element.className = 'badge bg-warning days-remaining';
                } else if (days <= 15) {
                    element.className = 'badge bg-info days-remaining';
                } else {
                    element.className = 'badge bg-success days-remaining';
                }
            });
        }

        // Función para filtrar tablas
        function filterTable(inputId, tableId) {
            const input = document.getElementById(inputId);
            const table = document.getElementById(tableId);
            
            if (!input || !table) return;
            
            input.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                
                for (let i = 0; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td');
                    let found = false;
                    
                    for (let j = 0; j < cells.length; j++) {
                        if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                    
                    rows[i].style.display = found ? '' : 'none';
                }
            });
        }

        // Función para copiar al portapapeles
        function copyToClipboard(text, message = 'Copiado al portapapeles') {
            navigator.clipboard.writeText(text).then(() => {
                Toast.fire({
                    icon: 'success',
                    title: message
                });
            }).catch(() => {
                // Fallback para navegadores que no soportan clipboard API
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                Toast.fire({
                    icon: 'success',
                    title: message
                });
            });
        }

        // Función para mostrar/ocultar contraseñas
        function togglePassword(buttonElement) {
            const targetId = buttonElement.getAttribute('data-target');
            const targetElement = document.getElementById(targetId);
            
            if (targetElement.type === 'password') {
                targetElement.type = 'text';
                buttonElement.innerHTML = '👁️';
                buttonElement.title = 'Ocultar contraseña';
            } else {
                targetElement.type = 'password';
                buttonElement.innerHTML = '👁️‍🗨️';
                buttonElement.title = 'Mostrar contraseña';
            }
        }

        // Inicializar funciones cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            // Actualizar días restantes
            updateDaysRemaining();
            
            // Configurar filtros de tabla si existen
            filterTable('searchInput', 'dataTable');
            
            // Configurar tooltips de Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Animaciones de entrada para las tarjetas
            const cards = document.querySelectorAll('.card, .platform-card, .stats-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Función para actualizar estadísticas en tiempo real
        function updateStats() {
            fetch('?page=dashboard&action=getStats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualizar estadísticas en el dashboard
                        console.log('Estadísticas actualizadas:', data);
                    }
                })
                .catch(error => {
                    console.error('Error al actualizar estadísticas:', error);
                });
        }

        // Actualizar estadísticas cada 5 minutos
        setInterval(updateStats, 300000);
    </script>
</body>
</html>
