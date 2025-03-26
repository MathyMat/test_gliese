<?php
// --

class C_Igvinvoicing extends Controller
{
    // --
    public function __construct()
    {
        parent::__construct();
    }

    // --
    public function index()
    {
        // --
        $this->functions->validate_session($this->segment->get('isActive'));
        $this->functions->check_permissions($this->segment->get('modules'), 'Igvinvoicing');
        // --
        $this->view->set_js('index');       // -- Load JS
        $this->view->set_menu(array('modules' => $this->segment->get('modules'), 'view' => 'Igvinvoicing')); // -- Active Menu
        $this->view->set_view('index');     // -- Load View
    }

    // --
    public function get_sale()
    {
        $this->functions->validate_session($this->segment->get('isActive'));

        $request = $_SERVER['REQUEST_METHOD'];

        if ($request === 'GET') {
            $input = json_decode(file_get_contents('php://input'), true);
            if (empty($input)) {
                $input = filter_input_array(INPUT_GET);
            }

            $campus_id = $this->segment->get('current_campus_id');

            if (!$campus_id) {
                $json = array(
                    'status' => 'ERROR',
                    'type' => 'warning',
                    'msg' => 'No se ha seleccionado una ubicación.',
                    'data' => array()
                );
            } else {
                $obj = $this->load_model('Sale');
                $response = $obj->get_sale($campus_id);

                switch ($response['status']) {
                    case 'OK':
                        // Verificar documentos con status 1
                        $pending_docs = array_filter($response['result'], function ($item) {
                            return $item['status'] === '1';
                        });

                        $warning_message = '';
                        if (!empty($pending_docs)) {
                            $count_pending = count($pending_docs);
                            $warning_message = array(
                                'show' => true,
                                'count' => $count_pending,
                                'message' => "¡Atención! Tiene {$count_pending} documento(s) pendiente(s) de declarar a SUNAT. " .
                                    "Estos documentos deben ser declarados dentro del plazo establecido para evitar multas.",
                                'action' => 'showPending'
                            );
                        }

                        $json = array(
                            'status' => 'OK',
                            'type' => 'success',
                            'msg' => 'Listado de registros encontrados.',
                            'data' => $response['result'],
                            'warning' => $warning_message
                        );
                        break;

                    case 'ERROR':
                        $json = array(
                            'status' => 'ERROR',
                            'type' => 'warning',
                            'msg' => 'No se encontraron registros en el sistema.',
                            'data' => array(),
                        );
                        break;

                    case 'EXCEPTION':
                        $json = array(
                            'status' => 'ERROR',
                            'type' => 'error',
                            'msg' => $response['result']->getMessage(),
                            'data' => array()
                        );
                        break;
                }
            }
        } else {
            $json = array(
                'status' => 'ERROR',
                'type' => 'error',
                'msg' => 'Método no permitido.',
                'data' => array()
            );
        }

        header('Content-Type: application/json');
        echo json_encode($json);
    }

    // --
    public function get_sale_by_id()
    {
        // --
        $this->functions->validate_session($this->segment->get('isActive'));
        // --
        $request = $_SERVER['REQUEST_METHOD'];
        // --
        if ($request === 'GET') {
            // --
            $input = json_decode(file_get_contents('php://input'), true);
            if (empty($input)) {
                $input = filter_input_array(INPUT_GET);
            }
            // --
            if (!empty($input['id_sale'])) {
                // --
                $obj = $this->load_model('Sale');
                // --
                $bind = array(
                    'id_sale' => intval($input['id_sale'])
                );
                // --
                $response = $obj->get_sale_by_id($bind);
                // --
                switch ($response['status']) {
                        // --
                    case 'OK':
                        // --
                        $json = array(
                            'status' => 'OK',
                            'type' => 'success',
                            'msg' => 'Listado de registros encontrados.',
                            'data' => $response['result']
                        );
                        // --
                        break;

                    case 'ERROR':
                        // --
                        $json = array(
                            'status' => 'ERROR',
                            'type' => 'warning',
                            'msg' => 'No se encontraron registros en el sistema.',
                            'data' => array(),
                        );
                        // --
                        break;

                    case 'EXCEPTION':
                        // --
                        $json = array(
                            'status' => 'ERROR',
                            'type' => 'error',
                            'msg' => $response['result']->getMessage(),
                            'data' => array()
                        );
                        // --
                        break;
                }
            } else {
                // --
                $json = array(
                    'status' => 'ERROR',
                    'type' => 'warning',
                    'msg' => 'No se enviaron los campos necesarios, verificar.',
                    'data' => array()
                );
            }
        } else {
            // --
            $json = array(
                'status' => 'ERROR',
                'type' => 'error',
                'msg' => 'Método no permitido.',
                'data' => array()
            );
        }

        // --
        header('Content-Type: application/json');
        echo json_encode($json);
    }

    // --
    public function get_sale_report()
    {
        try {
            $this->functions->validate_session($this->segment->get('isActive'));

            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                throw new Exception('Método no permitido.');
            }

            $input = json_decode(file_get_contents('php://input'), true) ?? filter_input_array(INPUT_GET);

            if (empty($input['id_sale']) || empty($input['tipo'])) {
                throw new Exception('ID de venta o tipo no proporcionado.');
            }

            $id_sale = intval($input['id_sale']);
            $tipo = intval($input['tipo']);

            $saleModel = $this->load_model('Sale');

            $companyData = $saleModel->get_company();
            $reportData = $saleModel->get_sale_report($id_sale);

            if ($companyData['status'] !== 'OK' || empty($companyData['result'])) {
                throw new Exception('No se pudo obtener la información de la compañía.');
            }

            if ($reportData['status'] !== 'OK' || empty($reportData['result'])) {
                throw new Exception('No se encontró el reporte de venta.');
            }

            $rucE = $companyData['result']['ruc'] ?? '';
            $regc = $reportData['result'];
            $codigo_voucher = $regc['voucher_type'];
            $serie = $regc['series'];
            $correlativo = $regc['correlative'];

            ob_start();
            switch ($tipo) {
                case 1:
                    include 'application/Reporte/Factura.php';
                    break;
                case 2:
                    include 'application/Reporte/Ticket.php';
                    break;
                default:
                    throw new Exception('Tipo de reporte no válido');
            }
            $content = ob_get_clean();

            $html2pdf = new Html2Pdf();
            $html2pdf->writeHTML($content);
            $html2pdf->output("{$rucE}-0{$codigo_voucher}-{$serie}-{$correlativo}.pdf");
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['status' => 'ERROR', 'message' => $e->getMessage()]);
            exit;
        }
    }

    // --
    public function send_email()
    {
        try {
            $this->functions->validate_session($this->segment->get('isActive'));
            $input = filter_input_array(INPUT_GET);

            if (empty($input['id_sale']) || empty($input['email'])) {
                throw new Exception('ID de venta o dirección de correo electrónico no proporcionados');
            }

            $id_sale = intval($input['id_sale']);
            $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Dirección de correo electrónico no válida');
            }

            $saleModel = $this->load_model('Sale');

            $companyData = $saleModel->get_company();
            $reportData = $saleModel->get_sale_report($id_sale);

            if (
                $companyData['status'] !== 'OK' || empty($companyData['result']) ||
                $reportData['status'] !== 'OK' || empty($reportData['result'])
            ) {
                throw new Exception('No se pudieron obtener los datos necesarios');
            }

            $rucE = $companyData['result']['ruc'] ?? '';
            $regc = $reportData['result'];
            $codigo_voucher = $regc['code'] ?? '';
            $serie = $regc['series'] ?? '';
            $correlativo = $regc['correlative'] ?? '';

            $baseFileName = "{$rucE}-{$codigo_voucher}-{$serie}-{$correlativo}";
            $xmlFilePath = __DIR__ . '/../../files/FRM/' . $baseFileName . '.xml';

            if (!file_exists($xmlFilePath)) {
                throw new Exception('No se pudo encontrar el archivo XML');
            }

            $obj = $this->load_model('Company');
            $response = $obj->get_config();
            $host = $response['result']['host'];
            $username = $response['result']['email'];
            $password = $response['result']['password'];

            ob_start();
            include 'application/Reporte/Factura.php';
            $content = ob_get_clean();

            $html2pdf = new Html2Pdf();
            $html2pdf->writeHTML($content);
            $pdfContent = $html2pdf->output('', 'S');
            $xmlContent = file_get_contents($xmlFilePath);

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = $host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $username;
            $mail->Password   = $password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->setFrom($username, 'Facturacion');
            $mail->Subject = 'Factura adjunta';
            $mail->Body    = 'Adjunto encontrará su factura en formato PDF y XML.';
            $mail->addStringAttachment($pdfContent, $baseFileName . '.pdf', 'base64', 'application/pdf');
            $mail->addStringAttachment($xmlContent, $baseFileName . '.xml', 'base64', 'application/xml');
            $mail->send();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'OK', 'message' => 'Correo enviado con éxito']);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }
}