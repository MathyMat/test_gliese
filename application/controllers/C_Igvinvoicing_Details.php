<?php
require_once __DIR__ . '/../core/Controller.php';

class C_Igvinvoicing_Details extends Controller {
    private $model;

    public function __construct() {
        parent::__construct();
        $this->model = $this->load_model('Igvinvoicing_Details');
        
        // Configuración inicial
        $this->view->set_js('index');
        $this->view->set_menu([
            'modules' => $this->segment->get('modules') ?? [],
            'view' => 'Igvinvoicing'
        ]);
    }

    public function index() {
        $this->functions->validate_session($this->segment->get('isActive'));
        $this->view->set_view('index');
    }

    public function get_initial_data() {
        header('Content-Type: application/json');
        try {
            $data = [
                'clients' => $this->model->get_clients(),
                'voucher_types' => $this->model->get_voucher_type(),
                'payment_types' => $this->model->get_payment_type(),
                'payment_methods' => $this->model->get_payment_method(),
                'igv' => $this->model->get_igv(),
                'coin' => $this->model->get_coin(),
                'current_date' => date('Y-m-d')
            ];
            echo json_encode(['status' => 'OK', 'data' => $data]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    public function save_invoice() {
        header('Content-Type: application/json');
        
        try {
            // Verificar método HTTP
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido', 405);
            }
    
            // Obtener datos de entrada
            $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    
            // Validar campos requeridos
            $required_fields = [
                'business_name_cli' => 'Cliente',
                'fecha_emision' => 'Fecha emisión',
                'vt_description' => 'Tipo comprobante',
                'fp_description' => 'Forma de pago',
                'pt_description' => 'Tipo de pago'
            ];
            
            foreach ($required_fields as $field => $name) {
                if (empty($input[$field])) {
                    throw new Exception("Campo requerido: {$name}", 400);
                }
            }
    
            // Obtener información del cliente
            $client = $this->model->get_client_by_id($input['business_name_cli']);
            if (!$client) {
                throw new Exception("Cliente no encontrado", 404);
            }
    
            // Generar series y correlativos
            $series_info = $this->model->get_series($input['vt_description']);
            if (!$series_info) {
                throw new Exception('No se pudo obtener la serie para este tipo de comprobante', 400);
            }
    
            // Preparar datos de cabecera
            $header_data = [
                'user_id' => $input['id_user'] ?? 1, // Valor por defecto si no viene
                'client_id' => $input['business_name_cli'],
                'voucher_type_code' => $input['vt_description'],
                'series' => $series_info['series'],
                'correlative' => $series_info['correlative'],
                'date_time' => $input['fecha_emision'],
                'due_date' => $input['fecha_vencimiento'] ?? $input['fecha_emision'],
                'currency' => 'PEN', // Valor fijo para soles
                'payment_type_code' => $input['pt_description'],
                'payment_method' => $input['fp_description'],
                'tax' => $input['igv'] ?? 18.00,
                'taxable_operations' => $input['op_gravadas'] ?? 0,
                'total_igv' => $input['igv_total'] ?? 0,
                'total_sale' => $input['total_venta'] ?? 0,
                'legend' => 'SON: ' . $this->number_to_words($input['total_venta'] ?? 0) . ' SOLES',
                'status' => 'PENDIENTE',
                'time' => date('H:i:s'),
                'assigned_igv' => $input['igv_asig'] ?? 1,
                'type' => ($input['vt_description'] == 1) ? 'FACTURA' : 'BOLETA',
                'document_number_cli' => $client['document_number'] ?? '',
                'address_cli' => $client['address'] ?? ''
            ];
    
            // Preparar detalles
            $details = [];
            if (!empty($input['product_code']) && is_array($input['product_code'])) {
                foreach ($input['product_code'] as $key => $value) {
                    $details[] = [
                        'product_code' => $input['product_code'][$key] ?? 'SERV' . str_pad($key + 1, 4, '0', STR_PAD_LEFT),
                        'product_description' => $input['product_description'][$key] ?? 'Servicio ' . ($key + 1),
                        'unit_of_measure' => $input['unit_of_measure'][$key] ?? 'NIU',
                        'quantity' => $input['quantity'][$key] ?? 1,
                        'sale_price' => $input['sale_price'][$key] ?? 0,
                        'affectation' => 'GRAVADA',
                        'tax_percentage' => $input['igv_asig'] ?? 18
                    ];
                }
            }
    
            // Crear factura
            $result = $this->model->create_invoice($header_data, $details);
            
            if ($result['status'] === 'OK') {
                // Actualizar correlativo
                $this->model->update_correlative($input['vt_description'], $series_info['correlative']);
            }
    
            echo json_encode($result);
            
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode([
                'status' => 'ERROR',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    public function get_client_info($id) {
        header('Content-Type: application/json');
        try {
            $client = $this->model->get_client_by_id($id);
            echo json_encode($client);
        } catch (Exception $e) {
            echo json_encode(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    private function number_to_words($number) {
        $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
        return $formatter->format($number);
    }
}