<?php
class ProveedoresController {
    private $proveedorModel;
    
    public function __construct() {
        $this->proveedorModel = new Proveedor();
    }
    
    // Mostrar todos los proveedores
    public function index() {
        try {
            $proveedor_id = $_GET['proveedor_id'] ?? null;
            $platform = $_GET['platform'] ?? null;
            
            if ($proveedor_id && $platform) {
                // Mostrar plataformas específicas de un proveedor
                $plataformas = $this->proveedorModel->getPlataformasByProveedor($proveedor_id);
                $plataformas = array_filter($plataformas, function($p) use ($platform) {
                    return $p['platform'] === $platform;
                });
                $proveedor = $this->proveedorModel->getProveedorById($proveedor_id);
                $title = "Plataformas de " . ucfirst($platform) . " - " . $proveedor['supplier_name'];
            } elseif ($proveedor_id) {
                // Mostrar todas las plataformas de un proveedor específico
                $plataformas = $this->proveedorModel->getPlataformasByProveedor($proveedor_id);
                $platforms = array_unique(array_column($plataformas, 'platform'));
                $proveedor = $this->proveedorModel->getProveedorById($proveedor_id);
                $title = "Plataformas de " . $proveedor['supplier_name'];
            } else {
                // Mostrar todos los proveedores
                $proveedores = $this->proveedorModel->getProveedoresWithPlatforms();
                $title = "Proveedores";
            }
            
            include 'views/partials/header.php';
            include 'views/proveedores/index.php';
            include 'views/partials/footer.php';
            
        } catch (Exception $e) {
            $error = "Error al cargar los proveedores: " . $e->getMessage();
            include 'views/partials/header.php';
            echo "<div class='container mt-4'><div class='alert alert-danger'>$error</div></div>";
            include 'views/partials/footer.php';
        }
    }
    
    // Mostrar formulario para crear nuevo proveedor o plataforma
    public function create() {
        $type = $_GET['type'] ?? 'proveedor'; // 'proveedor' o 'plataforma'
        $proveedor_id = $_GET['proveedor_id'] ?? null;
        $platform = $_GET['platform'] ?? '';
        
        if ($type === 'plataforma' && !$proveedor_id) {
            $_SESSION['error'] = "Proveedor no especificado";
            header('Location: ?page=proveedores');
            exit;
        }
        
        if ($type === 'plataforma') {
            $proveedor = $this->proveedorModel->getProveedorById($proveedor_id);
            if (!$proveedor) {
                $_SESSION['error'] = "Proveedor no encontrado";
                header('Location: ?page=proveedores');
                exit;
            }
        }
        
        include 'views/partials/header.php';
        include 'views/proveedores/create.php';
        include 'views/partials/footer.php';
    }
    
    // Procesar creación de proveedor o plataforma
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=proveedores');
            exit;
        }
        
        $type = $_POST['type'] ?? 'proveedor';
        
        try {
            if ($type === 'proveedor') {
                // Crear nuevo proveedor
                if (empty($_POST['supplier_name'])) {
                    throw new Exception("El nombre del proveedor es requerido");
                }
                
                $data = ['supplier_name' => trim($_POST['supplier_name'])];
                $this->proveedorModel->createProveedor($data);
                
                $_SESSION['success'] = "Proveedor creado exitosamente";
                header('Location: ?page=proveedores');
                
            } else {
                // Crear nueva plataforma para proveedor
                $requiredFields = ['proveedor_id', 'platform', 'email', 'password', 'supplier_phone', 'contract_date', 'expiry_date'];
                foreach ($requiredFields as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("El campo $field es requerido");
                    }
                }
                
                // Validar fechas
                if (strtotime($_POST['expiry_date']) <= strtotime($_POST['contract_date'])) {
                    throw new Exception("La fecha de vencimiento debe ser posterior a la fecha de contratación");
                }
                
                $data = [
                    'proveedor_id' => $_POST['proveedor_id'],
                    'platform' => trim($_POST['platform']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'supplier_phone' => trim($_POST['supplier_phone']),
                    'contract_date' => $_POST['contract_date'],
                    'expiry_date' => $_POST['expiry_date']
                ];
                
                $this->proveedorModel->createPlataforma($data);
                
                $_SESSION['success'] = "Plataforma creada exitosamente";
                header('Location: ?page=proveedores&proveedor_id=' . $_POST['proveedor_id'] . '&platform=' . urlencode($data['platform']));
            }
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $redirect = '?page=proveedores&action=create&type=' . $type;
            if ($type === 'plataforma') {
                $redirect .= '&proveedor_id=' . ($_POST['proveedor_id'] ?? '');
                $redirect .= '&platform=' . urlencode($_POST['platform'] ?? '');
            }
            header('Location: ' . $redirect);
            exit;
        }
    }
    
    // Mostrar formulario para editar proveedor o plataforma
    public function edit() {
        $type = $_GET['type'] ?? 'proveedor';
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = "ID no especificado";
            header('Location: ?page=proveedores');
            exit;
        }
        
        try {
            if ($type === 'proveedor') {
                $item = $this->proveedorModel->getProveedorById($id);
                if (!$item) {
                    throw new Exception("Proveedor no encontrado");
                }
            } else {
                $item = $this->proveedorModel->getPlataformaById($id);
                if (!$item) {
                    throw new Exception("Plataforma no encontrada");
                }
            }
            
            include 'views/partials/header.php';
            include 'views/proveedores/edit.php';
            include 'views/partials/footer.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ?page=proveedores');
            exit;
        }
    }
    
    // Procesar actualización de proveedor o plataforma
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=proveedores');
            exit;
        }
        
        $type = $_POST['type'] ?? 'proveedor';
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = "ID no especificado";
            header('Location: ?page=proveedores');
            exit;
        }
        
        try {
            if ($type === 'proveedor') {
                // Actualizar proveedor
                if (empty($_POST['supplier_name'])) {
                    throw new Exception("El nombre del proveedor es requerido");
                }
                
                $data = ['supplier_name' => trim($_POST['supplier_name'])];
                $this->proveedorModel->updateProveedor($id, $data);
                
                $_SESSION['success'] = "Proveedor actualizado exitosamente";
                header('Location: ?page=proveedores');
                
            } else {
                // Actualizar plataforma
                $requiredFields = ['platform', 'email', 'password', 'supplier_phone', 'contract_date', 'expiry_date'];
                foreach ($requiredFields as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("El campo $field es requerido");
                    }
                }
                
                // Validar fechas
                if (strtotime($_POST['expiry_date']) <= strtotime($_POST['contract_date'])) {
                    throw new Exception("La fecha de vencimiento debe ser posterior a la fecha de contratación");
                }
                
                $data = [
                    'platform' => trim($_POST['platform']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'supplier_phone' => trim($_POST['supplier_phone']),
                    'contract_date' => $_POST['contract_date'],
                    'expiry_date' => $_POST['expiry_date']
                ];
                
                $this->proveedorModel->updatePlataforma($id, $data);
                
                $_SESSION['success'] = "Plataforma actualizada exitosamente";
                header('Location: ?page=proveedores&proveedor_id=' . $_POST['proveedor_id'] . '&platform=' . urlencode($data['platform']));
            }
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ?page=proveedores&action=edit&type=' . $type . '&id=' . $id);
            exit;
        }
    }
    
    // Eliminar proveedor o plataforma
    public function delete() {
        $type = $_GET['type'] ?? 'proveedor';
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = "ID no especificado";
            header('Location: ?page=proveedores');
            exit;
        }
        
        try {
            if ($type === 'proveedor') {
                $proveedor = $this->proveedorModel->getProveedorById($id);
                if (!$proveedor) {
                    throw new Exception("Proveedor no encontrado");
                }
                
                $this->proveedorModel->deleteProveedor($id);
                $_SESSION['success'] = "Proveedor eliminado exitosamente";
                header('Location: ?page=proveedores');
                
            } else {
                $plataforma = $this->proveedorModel->getPlataformaById($id);
                if (!$plataforma) {
                    throw new Exception("Plataforma no encontrada");
                }
                
                $this->proveedorModel->deletePlataforma($id);
                $_SESSION['success'] = "Plataforma eliminada exitosamente";
                header('Location: ?page=proveedores&proveedor_id=' . $plataforma['proveedor_id']);
            }
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ?page=proveedores');
            exit;
        }
    }
    
    // Obtener plataformas de un proveedor (AJAX)
    public function getPlatforms() {
        $proveedor_id = $_GET['proveedor_id'] ?? null;
        
        if (!$proveedor_id) {
            echo json_encode(['success' => false, 'error' => 'Proveedor no especificado']);
            exit;
        }
        
        try {
            $plataformas = $this->proveedorModel->getPlataformasByProveedor($proveedor_id);
            $platforms = array_unique(array_column($plataformas, 'platform'));
            
            echo json_encode(['success' => true, 'platforms' => $platforms]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
    
    // Obtener estadísticas de un proveedor
    public function getProveedorStats() {
        $proveedor_id = $_GET['proveedor_id'] ?? null;
        
        if (!$proveedor_id) {
            echo json_encode(['success' => false, 'error' => 'Proveedor no especificado']);
            exit;
        }
        
        try {
            $plataformas = $this->proveedorModel->getPlataformasByProveedor($proveedor_id);
            
            $stats = [
                'total' => count($plataformas),
                'active' => 0,
                'expired' => 0,
                'expiring' => 0
            ];
            
            $today = new DateTime();
            $sevenDaysFromNow = new DateTime('+7 days');
            
            foreach ($plataformas as $plataforma) {
                $expiryDate = new DateTime($plataforma['expiry_date']);
                
                if ($expiryDate >= $today) {
                    $stats['active']++;
                    if ($expiryDate <= $sevenDaysFromNow) {
                        $stats['expiring']++;
                    }
                } else {
                    $stats['expired']++;
                }
            }
            
            echo json_encode(['success' => true, 'stats' => $stats]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}
?>
