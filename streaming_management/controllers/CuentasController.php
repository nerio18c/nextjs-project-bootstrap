<?php
class CuentasController {
    private $cuentaModel;
    
    public function __construct() {
        $this->cuentaModel = new Cuenta();
    }
    
    // Mostrar todas las cuentas organizadas por plataforma
    public function index() {
        try {
            $platform = $_GET['platform'] ?? null;
            
            if ($platform) {
                // Mostrar cuentas de una plataforma específica
                $cuentas = $this->cuentaModel->getByPlatform($platform);
                $title = "Cuentas de " . ucfirst($platform);
            } else {
                // Mostrar todas las plataformas disponibles
                $platforms = $this->cuentaModel->getPlatforms();
                $title = "Cuentas Completas";
            }
            
            include 'views/partials/header.php';
            include 'views/cuentas/index.php';
            include 'views/partials/footer.php';
            
        } catch (Exception $e) {
            $error = "Error al cargar las cuentas: " . $e->getMessage();
            include 'views/partials/header.php';
            echo "<div class='container mt-4'><div class='alert alert-danger'>$error</div></div>";
            include 'views/partials/footer.php';
        }
    }
    
    // Mostrar formulario para crear nueva cuenta
    public function create() {
        $platform = $_GET['platform'] ?? '';
        
        include 'views/partials/header.php';
        include 'views/cuentas/create.php';
        include 'views/partials/footer.php';
    }
    
    // Procesar creación de nueva cuenta
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=cuentas');
            exit;
        }
        
        try {
            // Validar datos
            $requiredFields = ['platform', 'email', 'password', 'client_name', 'client_phone', 'contract_date', 'expiry_date'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo $field es requerido");
                }
            }
            
            // Validar fechas
            if (strtotime($_POST['expiry_date']) <= strtotime($_POST['contract_date'])) {
                throw new Exception("La fecha de vencimiento debe ser posterior a la fecha de contratación");
            }
            
            // Crear cuenta
            $data = [
                'platform' => trim($_POST['platform']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'client_name' => trim($_POST['client_name']),
                'client_phone' => trim($_POST['client_phone']),
                'contract_date' => $_POST['contract_date'],
                'expiry_date' => $_POST['expiry_date']
            ];
            
            $this->cuentaModel->create($data);
            
            $_SESSION['success'] = "Cuenta creada exitosamente";
            header('Location: ?page=cuentas&platform=' . urlencode($data['platform']));
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ?page=cuentas&action=create&platform=' . urlencode($_POST['platform'] ?? ''));
            exit;
        }
    }
    
    // Mostrar formulario para editar cuenta
    public function edit() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = "ID de cuenta no especificado";
            header('Location: ?page=cuentas');
            exit;
        }
        
        try {
            $cuenta = $this->cuentaModel->getById($id);
            
            if (!$cuenta) {
                throw new Exception("Cuenta no encontrada");
            }
            
            include 'views/partials/header.php';
            include 'views/cuentas/edit.php';
            include 'views/partials/footer.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ?page=cuentas');
            exit;
        }
    }
    
    // Procesar actualización de cuenta
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=cuentas');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = "ID de cuenta no especificado";
            header('Location: ?page=cuentas');
            exit;
        }
        
        try {
            // Validar datos
            $requiredFields = ['platform', 'email', 'password', 'client_name', 'client_phone', 'contract_date', 'expiry_date'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo $field es requerido");
                }
            }
            
            // Validar fechas
            if (strtotime($_POST['expiry_date']) <= strtotime($_POST['contract_date'])) {
                throw new Exception("La fecha de vencimiento debe ser posterior a la fecha de contratación");
            }
            
            // Actualizar cuenta
            $data = [
                'platform' => trim($_POST['platform']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'client_name' => trim($_POST['client_name']),
                'client_phone' => trim($_POST['client_phone']),
                'contract_date' => $_POST['contract_date'],
                'expiry_date' => $_POST['expiry_date']
            ];
            
            $this->cuentaModel->update($id, $data);
            
            $_SESSION['success'] = "Cuenta actualizada exitosamente";
            header('Location: ?page=cuentas&platform=' . urlencode($data['platform']));
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ?page=cuentas&action=edit&id=' . $id);
            exit;
        }
    }
    
    // Eliminar cuenta
    public function delete() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = "ID de cuenta no especificado";
            header('Location: ?page=cuentas');
            exit;
        }
        
        try {
            $cuenta = $this->cuentaModel->getById($id);
            
            if (!$cuenta) {
                throw new Exception("Cuenta no encontrada");
            }
            
            $this->cuentaModel->delete($id);
            
            $_SESSION['success'] = "Cuenta eliminada exitosamente";
            header('Location: ?page=cuentas&platform=' . urlencode($cuenta['platform']));
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ?page=cuentas');
            exit;
        }
    }
    
    // Generar mensaje de WhatsApp para activar cuenta
    public function activate() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'ID no especificado']);
            exit;
        }
        
        try {
            $cuenta = $this->cuentaModel->getById($id);
            
            if (!$cuenta) {
                throw new Exception("Cuenta no encontrada");
            }
            
            // Crear mensaje de WhatsApp
            $message = "🎬 *ACTIVACIÓN DE CUENTA*\n\n";
            $message .= "Hola {$cuenta['client_name']}! 👋\n\n";
            $message .= "Tu cuenta de *{$cuenta['platform']}* está lista:\n\n";
            $message .= "📧 *Email:* {$cuenta['email']}\n";
            $message .= "🔐 *Contraseña:* {$cuenta['password']}\n\n";
            $message .= "📅 *Fecha de vencimiento:* " . date('d/m/Y', strtotime($cuenta['expiry_date'])) . "\n\n";
            $message .= "¡Disfruta tu contenido! 🍿";
            
            $whatsappUrl = "https://wa.me/{$cuenta['client_phone']}?text=" . urlencode($message);
            
            echo json_encode(['success' => true, 'url' => $whatsappUrl]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
    
    // Generar mensaje de WhatsApp para notificar vencimiento
    public function notify() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'ID no especificado']);
            exit;
        }
        
        try {
            $cuenta = $this->cuentaModel->getById($id);
            
            if (!$cuenta) {
                throw new Exception("Cuenta no encontrada");
            }
            
            $daysRemaining = $this->cuentaModel->calculateDaysRemaining($cuenta['expiry_date']);
            
            // Crear mensaje de WhatsApp
            $message = "⚠️ *NOTIFICACIÓN DE VENCIMIENTO*\n\n";
            $message .= "Hola {$cuenta['client_name']}! 👋\n\n";
            
            if ($daysRemaining > 0) {
                $message .= "Tu cuenta de *{$cuenta['platform']}* vence en *{$daysRemaining} días*.\n\n";
                $message .= "📅 *Fecha de vencimiento:* " . date('d/m/Y', strtotime($cuenta['expiry_date'])) . "\n\n";
                $message .= "¡Renueva pronto para no perder el acceso! 🔄";
            } else {
                $days = abs($daysRemaining);
                $message .= "Tu cuenta de *{$cuenta['platform']}* venció hace *{$days} días*.\n\n";
                $message .= "📅 *Fecha de vencimiento:* " . date('d/m/Y', strtotime($cuenta['expiry_date'])) . "\n\n";
                $message .= "¡Contactanos para renovar tu suscripción! 📞";
            }
            
            $whatsappUrl = "https://wa.me/{$cuenta['client_phone']}?text=" . urlencode($message);
            
            echo json_encode(['success' => true, 'url' => $whatsappUrl]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}
?>
