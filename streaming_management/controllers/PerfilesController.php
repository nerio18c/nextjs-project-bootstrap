<?php
class PerfilesController {
    private $perfilModel;
    
    public function __construct() {
        $this->perfilModel = new Perfil();
    }
    
    // Mostrar todos los perfiles organizados por plataforma
    public function index() {
        try {
            $platform = $_GET['platform'] ?? null;
            
            if ($platform) {
                // Mostrar perfiles de una plataforma específica
                $perfiles = $this->perfilModel->getByPlatform($platform);
                $title = "Perfiles de " . ucfirst($platform);
            } else {
                // Mostrar todas las plataformas disponibles
                $platforms = $this->perfilModel->getPlatforms();
                $title = "Perfiles";
            }
            
            include 'views/partials/header.php';
            include 'views/perfiles/index.php';
            include 'views/partials/footer.php';
            
        } catch (Exception $e) {
            $error = "Error al cargar los perfiles: " . $e->getMessage();
            include 'views/partials/header.php';
            echo "<div class='container mt-4'><div class='alert alert-danger'>$error</div></div>";
            include 'views/partials/footer.php';
        }
    }
    
    // Mostrar formulario para crear nuevo perfil
    public function create() {
        $platform = $_GET['platform'] ?? '';
        
        include 'views/partials/header.php';
        include 'views/perfiles/create.php';
        include 'views/partials/footer.php';
    }
    
    // Procesar creación de nuevo perfil
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=perfiles');
            exit;
        }
        
        try {
            // Validar datos
            $requiredFields = ['platform', 'email', 'password', 'client_name', 'client_phone', 'profile_name', 'profile_pin', 'contract_date', 'expiry_date'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo $field es requerido");
                }
            }
            
            // Validar fechas
            if (strtotime($_POST['expiry_date']) <= strtotime($_POST['contract_date'])) {
                throw new Exception("La fecha de vencimiento debe ser posterior a la fecha de contratación");
            }
            
            // Crear perfil
            $data = [
                'platform' => trim($_POST['platform']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'client_name' => trim($_POST['client_name']),
                'client_phone' => trim($_POST['client_phone']),
                'profile_name' => trim($_POST['profile_name']),
                'profile_pin' => trim($_POST['profile_pin']),
                'contract_date' => $_POST['contract_date'],
                'expiry_date' => $_POST['expiry_date']
            ];
            
            $this->perfilModel->create($data);
            
            $_SESSION['success'] = "Perfil creado exitosamente";
            header('Location: ?page=perfiles&platform=' . urlencode($data['platform']));
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ?page=perfiles&action=create&platform=' . urlencode($_POST['platform'] ?? ''));
            exit;
        }
    }
    
    // Mostrar formulario para editar perfil
    public function edit() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = "ID de perfil no especificado";
            header('Location: ?page=perfiles');
            exit;
        }
        
        try {
            $perfil = $this->perfilModel->getById($id);
            
            if (!$perfil) {
                throw new Exception("Perfil no encontrado");
            }
            
            include 'views/partials/header.php';
            include 'views/perfiles/edit.php';
            include 'views/partials/footer.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ?page=perfiles');
            exit;
        }
    }
    
    // Procesar actualización de perfil
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=perfiles');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = "ID de perfil no especificado";
            header('Location: ?page=perfiles');
            exit;
        }
        
        try {
            // Validar datos
            $requiredFields = ['platform', 'email', 'password', 'client_name', 'client_phone', 'profile_name', 'profile_pin', 'contract_date', 'expiry_date'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo $field es requerido");
                }
            }
            
            // Validar fechas
            if (strtotime($_POST['expiry_date']) <= strtotime($_POST['contract_date'])) {
                throw new Exception("La fecha de vencimiento debe ser posterior a la fecha de contratación");
            }
            
            // Actualizar perfil
            $data = [
                'platform' => trim($_POST['platform']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'client_name' => trim($_POST['client_name']),
                'client_phone' => trim($_POST['client_phone']),
                'profile_name' => trim($_POST['profile_name']),
                'profile_pin' => trim($_POST['profile_pin']),
                'contract_date' => $_POST['contract_date'],
                'expiry_date' => $_POST['expiry_date']
            ];
            
            $this->perfilModel->update($id, $data);
            
            $_SESSION['success'] = "Perfil actualizado exitosamente";
            header('Location: ?page=perfiles&platform=' . urlencode($data['platform']));
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ?page=perfiles&action=edit&id=' . $id);
            exit;
        }
    }
    
    // Eliminar perfil
    public function delete() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = "ID de perfil no especificado";
            header('Location: ?page=perfiles');
            exit;
        }
        
        try {
            $perfil = $this->perfilModel->getById($id);
            
            if (!$perfil) {
                throw new Exception("Perfil no encontrado");
            }
            
            $this->perfilModel->delete($id);
            
            $_SESSION['success'] = "Perfil eliminado exitosamente";
            header('Location: ?page=perfiles&platform=' . urlencode($perfil['platform']));
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ?page=perfiles');
            exit;
        }
    }
    
    // Generar mensaje de WhatsApp para activar perfil
    public function activate() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'ID no especificado']);
            exit;
        }
        
        try {
            $perfil = $this->perfilModel->getById($id);
            
            if (!$perfil) {
                throw new Exception("Perfil no encontrado");
            }
            
            // Crear mensaje de WhatsApp
            $message = "🎬 *ACTIVACIÓN DE PERFIL*\n\n";
            $message .= "Hola {$perfil['client_name']}! 👋\n\n";
            $message .= "Tu perfil de *{$perfil['platform']}* está listo:\n\n";
            $message .= "📧 *Email de la cuenta:* {$perfil['email']}\n";
            $message .= "🔐 *Contraseña:* {$perfil['password']}\n";
            $message .= "👤 *Nombre del perfil:* {$perfil['profile_name']}\n";
            $message .= "🔢 *PIN del perfil:* {$perfil['profile_pin']}\n\n";
            $message .= "📅 *Fecha de vencimiento:* " . date('d/m/Y', strtotime($perfil['expiry_date'])) . "\n\n";
            $message .= "¡Disfruta tu contenido! 🍿";
            
            $whatsappUrl = "https://wa.me/{$perfil['client_phone']}?text=" . urlencode($message);
            
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
            $perfil = $this->perfilModel->getById($id);
            
            if (!$perfil) {
                throw new Exception("Perfil no encontrado");
            }
            
            $daysRemaining = $this->perfilModel->calculateDaysRemaining($perfil['expiry_date']);
            
            // Crear mensaje de WhatsApp
            $message = "⚠️ *NOTIFICACIÓN DE VENCIMIENTO*\n\n";
            $message .= "Hola {$perfil['client_name']}! 👋\n\n";
            
            if ($daysRemaining > 0) {
                $message .= "Tu perfil *{$perfil['profile_name']}* de *{$perfil['platform']}* vence en *{$daysRemaining} días*.\n\n";
                $message .= "📅 *Fecha de vencimiento:* " . date('d/m/Y', strtotime($perfil['expiry_date'])) . "\n\n";
                $message .= "¡Renueva pronto para no perder el acceso! 🔄";
            } else {
                $days = abs($daysRemaining);
                $message .= "Tu perfil *{$perfil['profile_name']}* de *{$perfil['platform']}* venció hace *{$days} días*.\n\n";
                $message .= "📅 *Fecha de vencimiento:* " . date('d/m/Y', strtotime($perfil['expiry_date'])) . "\n\n";
                $message .= "¡Contactanos para renovar tu suscripción! 📞";
            }
            
            $whatsappUrl = "https://wa.me/{$perfil['client_phone']}?text=" . urlencode($message);
            
            echo json_encode(['success' => true, 'url' => $whatsappUrl]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
    
    // Buscar perfiles por cliente
    public function search() {
        $query = $_GET['q'] ?? '';
        
        if (empty($query)) {
            echo json_encode(['success' => false, 'error' => 'Consulta vacía']);
            exit;
        }
        
        try {
            $perfiles = $this->perfilModel->getByClient($query);
            echo json_encode(['success' => true, 'perfiles' => $perfiles]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}
?>
