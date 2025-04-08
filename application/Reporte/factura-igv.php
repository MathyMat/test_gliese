<!DOCTYPE html>
<html lang="es">
<head>
    <title>Reporte de Factura IGV</title>
    <style type="text/css">
        table {
            color: black;
            border: none;
            width: 100%;
        }

        .header {
            padding-left: 15px;
            padding-right: 15px;
        }

        .text {
            padding-left: 20px;
            padding-right: 20px;
            font-size: 15px;
            text-align: justify-all;
            line-height: 120%;
            margin-top: -2px;
        }

        .text2 {
            padding-left: 50px;
            padding-right: 40px;
            padding-bottom: 10px;
            text-align: justify-all;
            line-height: 170%;
        }

        .factura {
            font-size: 16px;
            width: 28%;
            height: 10px;
            border: 1px solid red;
            text-align: center;
            border-collapse: separate;
            border-spacing: 10;
            border: 1px solid black;
            border-radius: 15px;
            -moz-border-radius: 20px;
            padding: 2px;
        }

        .razon-social {
            color: red;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
            padding-left: 20px;
        }

        .info-empresa {
            font-size: 9px;
            text-align: center;
            margin-top: -10px;
            font-weight: normal;
            text-transform: uppercase;
        }

        .direcion-empresa {
            width: 100%;
            font-size: 10px;
            text-align: left;
            padding-left: 30px;
            margin-top: -25px;
        }

        .rubro {
            color: black;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .linea {
            padding-left: 20px;
            padding-right: 20px;
        }

        .cliente {
            padding-left: 15px;
            padding-right: 15px;
            font-size: 10px;
            margin-top: -10px;
        }

        .cuadro-cliente {
            border-collapse: separate;
            border-spacing: 10;
            border: 1px solid black;
            border-radius: 6px;
            -moz-border-radius: 20px;
            padding: 3px;
            width: 98%;
        }

        .pagos {
            text-align: center;
            display: table-cell;
            border: solid;
            border-width: thin;
            margin-top: -10px;
            width: 98%;
        }

        .contenido {
            padding-left: 25px;
            padding-right: 25px;
            font-size: 9px;
            height: 50px;
            margin-top: -10px;
            width: 98%;
            margin-left: -10px;
        }

        .cabecera {
            background: #1D1B1B;
            color: white;
            line-height: 65px;
            font-size: 12px;
            line-height: 65px;
            border-top-left-radius: 5px;
            border-top-right-radius: 10px;
            margin-bottom: -5px;
            width: 98%;
        }

        .cuadro-contenido {
            margin-left: 0px;
            padding-top: 0px;
            float: left;
        }

        .borde-contenido {
            height: 580px;
            width: 98%;
            margin-left: 0px;
            padding-top: 0px;
        }

        .borde-contenido_1 {
            height: 600px;
            width: 98%;
            padding-left: 3px;
        }

        .cuadro {
            border-collapse: separate;
            margin-top: 0px;
            width: 98%;
            margin-left: 0px;
            padding-top: -581px;
        }

        .articulo {
            border-collapse: separate;
            padding-left: 0px;
            padding-right: 0px;
            width: 98%;
            padding-top: -603px;
        }

        .total {
            padding-left: 35px;
            padding-right: 20px;
            font-size: 9px;
            font-weight: bold;
        }

        .precio {
            width: 40%;
            height: 10px;
            text-align: right;
        }

        .cuadro-precio {
            margin-left: 451.3px;
            margin-top: -1px;
        }

        .foot {
            padding-left: 20px;
            padding-right: 20px;
            font-size: 8pt;
            width: 98%;
        }

        .cuadro-footer {
            width: 98%;
            text-align: center;
        }

        .aviso {
            font-size: 10pt;
            margin-left: 10px;
            margin-right: 10px;
            text-align: justify;
            padding: 20px;
            padding-top: 10px;
            padding-bottom: 10px;
            border: solid 0.3px #000;
        }

        .nota {
            font-size: 10pt;
            margin-left: 10px;
            margin-right: 10px;
            text-align: justify;
            padding: 20px;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .silver {
            background: white;
            padding: 3px 4px 3px;
        }

        .clouds {
            background: #ecf0f1;
            padding: 3px 4px 3px;
        }

        .boder {
            border-collapse: collapse;
            border-color: #087DA2;
        }
    </style>
</head>
<body>
    <?php
    // Verificar datos de la compañía
    if (!isset($companyData) || $companyData['status'] !== 'OK' || empty($companyData['result'])) {
        throw new Exception("Error: No se encontraron datos de la compañía.");
    }
    $company = $companyData['result'];
    $empresa = $company['business_name'] ?? '';
    $nombre_comercial = $company['company_name'] ?? $empresa;
    $rucE = $company['ruc'] ?? '';
    $direccion = $company['address'] ?? '';
    $direccion2 = $company['address2'] ?? '';
    $distrito = $company['district'] ?? '';
    $provincia = $company['province'] ?? '';
    $departamento = $company['department'] ?? '';
    $codigo_postal = $company['postal_code'] ?? '';
    $telefono = $company['phone'] ?? '';
    $correo = $company['email'] ?? '';
    $web = $company['web'] ?? '';
    $logo = $company['logo'] ?? '';
    $pais = $company['country'] ?? 'Perú';
    $fecha_inicio = $company['start_date'] ?? '';
    $rubro = $company['industry'] ?? '';
    $ubigeo = $company['ubigeo'] ?? '';

    // Verificar datos de la factura
    if (!isset($reportData) || $reportData['status'] !== 'OK' || empty($reportData['result'])) {
        throw new Exception("Error: No se encontraron datos de facturación para el ID proporcionado.");
    }
    $factura = $reportData['result'];
    $nombre_user = $factura['name_user'] ?? '';
    $codeVoucher = $regc['product_code'] ?? '';
    $voucher_type_code = $factura['voucher_type_code'] ?? '';
    $tipo_voucher = ($factura['voucher_type_code'] == '01') ? 'FACTURA ELECTRÓNICA' : 'BOLETA ELECTRÓNICA';
    $documento_client = $factura['document_type'] ?? '';
    $tipo_documento_cliente = $factura['document_type_id'] ?? '';
    $cliente = $factura['client_name'] ?? '';
    $igv_asig = $factura['total_igv'] ?? 0;
    $direccioncliente = $factura['client_address'] ?? '';
    $rucC = $factura['document_number'] ?? '';
    $serie = $factura['series'] ?? '';
    $correlativo = $factura['correlative'] ?? '';
    $moneda = $factura['currency_desc'] ?? '';
    $fecha = $factura['issue_date'] ?? '';
    $fecha_ven = $factura['due_date'] ?? '';
    $total_venta = $factura['total_sale'] ?? 0;
    $op_gravadas = $factura['taxable_operations'] ?? 0;
    $op_gratuitas = $factura['free_operations'] ?? 0;
    $op_exoneradas = $factura['exempt_operations'] ?? 0;
    $op_inafectas = $factura['unaffected_operations'] ?? 0;
    $tipo_pago = $factura['payment_type'] ?? '';
    $leyenda = $factura['legend'] ?? '';
    $igv_porcentaje = $factura['igv'] ?? 18;

    // Verificar detalles de la factura
    if (!isset($detailsData) || $detailsData['status'] !== 'OK' || empty($detailsData['result'])) {
        throw new Exception("Error: No se encontraron detalles para la factura.");
    }
    $detalles = $detailsData['result'];
    $item = 0;

    // Función para formatear fechas en español
    function formatDate($dateString) {
        if (empty($dateString)) return '';
        
        $meses = [
            'January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo',
            'April' => 'Abril', 'May' => 'Mayo', 'June' => 'Junio',
            'July' => 'Julio', 'August' => 'Agosto', 'September' => 'Septiembre',
            'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'
        ];
        
        $date = new DateTime($dateString);
        return $date->format('d') . ' de ' . $meses[$date->format('F')] . ' del ' . $date->format('Y');
    }
    ?>
    <form action>
        <input type="hidden" name="rucempresa" value="<?= $rucE ?>">
        <input type="hidden" name="seriecompro" value="<?= $serie ?>">
        <input type="hidden" name="correlativocompro" value="<?= $correlativo ?>">
    </form>

    <div class="header">
        <table style="width: 100%">
            <tr>
                <th style="width: 55%; text-align: center; ">
                    <?php
                    $logoPath = "application\Reporte\logo.png"; // Ajusta esta ruta
                    if (file_exists($logoPath)) {
                        echo '<img style="width: 90%;" src="'.$logoPath.'" alt="Logo">';
                    } else {
                        echo '<h3>'.$empresa.'</h3>';
                        echo '<p>RUC: '.$rucE.'</p>';
                    }
                    ?>
                    <p class="razon-social"><?= $empresa; ?></p>
                </th>
                <th style="width: 40%; text-align: center; padding-top: 5px " class="factura">
                    <p>
                        R.U.C. <?= $rucE; ?><br><br>
                        <b><?= $tipo_voucher; ?></b><br><br>
                        <?= $serie . ' - ' . $correlativo ?><br><br>
                    </p>
                </th>
                <th style="width: 3%; text-align: center; padding-top: 5px "></th>
            </tr>
        </table>
    </div>

    <br>

    <div class="direcion-empresa">
        <table style="width: 100%">
            <tr>
                <td style="width: 55%">
                    Dirección: <?= $direccion; ?> - <?= $distrito; ?> - <?= $provincia; ?><br>
                    Telef.: <?= $telefono; ?> Email: <?= $correo; ?><br>
                    Web: <?= $web; ?>
                </td>
            </tr>
        </table>
    </div>
    <br>

    <div class="cliente">
        <table class="cuadro-cliente">
            <tr>
                <td style="width: 10%"><b>CLIENTE</b></td>
                <td style="width: 88.3%">: <?= $cliente; ?></td>
            </tr>
            <tr>
                <td style="width: 10%"><b><?= $documento_client; ?></b></td>
                <td style="width: 88.3%">: <?= $rucC; ?></td>
            </tr>
            <tr>
                <td style="width: 10%"><b>DIRECCIÓN</b></td>
                <td style="width: 88.3%">: <?= $direccioncliente; ?> </td>
            </tr>
        </table>

        <br>

        <table cellspacing="0" cellpadding="0" border="0.5" class="pagos">
            <tr>
                <td style="width:24.6%"><b>Fecha de Emisión</b><br>
                    <?= formatDate($fecha) ?>
                </td>
                <td style="width:24.6%"><b>Fecha de Vencimiento</b><br>
                    <?= formatDate($fecha_ven) ?>
                </td>
                <td style="width:24.6%"><b>Moneda</b><br><?= $moneda; ?> </td>
                <td style="width:24.6%"><b>Condición de Pago</b><br><?= $tipo_pago; ?></td>
            </tr>
            <tr>
                <td style="width:24.6%"><b>Asesor Comercial</b><br><?= $nombre_user ?></td>
                <td style="width:24.6%"></td>
                <td style="width:24.6%"></td>
                <td style="width:24.6%"></td>
            </tr>
        </table>
    </div>
    <br>

    <!-- Descripción -->
    <div class="contenido">
        <table class="cabecera">
            <tr>
                <th style="width: 9.05%; height: 3.2px; text-align: center; padding-top: 5px ">CODIGO</th>
                <th style="width: 55%; text-align: center; height: 12px; padding-top: 5px ">DESCRIPCIÓN</th>
                <th style="width: 10%; text-align: center; padding-top: 5px ">CAT.</th>
                <th style="width: 13%; text-align: center; padding-top: 5px ">P. UNIT.</th>
                <th style="width: 13%; text-align: center; padding-top: 5px ">IMPORTE</th>
            </tr>
        </table>

        <table class="cuadro-contenido">
            <tr>
                <td class="borde-contenido">
                </td>
            </tr>
        </table>

        <table class="cuadro" border="0.3" cellpadding="0" cellspacing="1" bordercolor="black" style="border-collapse:collapse;">
            <tr>
                <td style="width:9.05%; height: 595px"></td>
                <td style="width:55%; "></td>
                <td style="width:10%; "></td>
                <td style="width:13%; "></td>
                <td style="width:13%; "></td>
            </tr>
        </table>

        <table class="articulo" border="0.3" cellpadding="0" cellspacing="1" bordercolor="black" style="border-collapse:collapse;">
            <?php
            foreach ($detalles as $regd) {
                $item += 1;
                $estilo = ($item % 2 == 0) ? '#DAF9FB' : '#F0F0F0';
                $precioV = $regd['item_unit_price'];
                $importe = $precioV * $regd['quantity'];
            ?>
                <tr style="text-align:left">
                    <td style="background-color: <?= $estilo; ?>; width:9.05%; padding-top: 5px; text-align: center;"><?= $regd['product_code']; ?></td>
                    <td style="background-color: <?= $estilo; ?>; width:55%; height: 1.12px; padding-top: 5px; text-align: justify; padding: 5px"><?= $regd['product_description'] . " " . ($regd['series'] ?? ''); ?></td>
                    <td style="background-color: <?= $estilo; ?>; width:10%; padding-top: 5px; text-align: center;"><?= $regd['quantity']; ?></td>
                    <td style="background-color: <?= $estilo; ?>; width:13%; padding-top: 5px; text-align: right;"><?= number_format($precioV, 2, '.', ','); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td style="background-color: <?= $estilo; ?>; width:13%; padding-top: 5px; text-align: right;"><?= number_format($importe, 2, '.', ','); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
            <?php } ?>
            <br>
        </table>
    </div>
    <br>

    <div class="total">
        <table cellspacing="0" cellpadding="0" border="0.2">
            <tr style="width: 100%; text-align: center">
                <td style="text-align: center; width:12%">OP. GRAVADA</td>
                <td style="text-align: center; width:12%">OP. GRATUITA</td>
                <td style="text-align: center; width:12%">OP. EXONERADA</td>
                <td style="text-align: center; width:12%">OP. INAFECTA</td>
                <td style="text-align: center; width:12%">DESCTO TOTAL</td>
                <td style="text-align: center; width:12%">IGV (<?= $igv_porcentaje ?>%)</td>
                <td style="text-align: center; width:12%">PRECIO TOTAL</td>
            </tr>
            <tr style="width: 100%; text-align: center;">
                <td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; <?= number_format($op_gravadas, 2, '.', ','); ?></td>
                <td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; <?= number_format($op_gratuitas, 2, '.', ','); ?></td>
                <td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; <?= number_format($op_exoneradas, 2, '.', ','); ?></td>
                <td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; <?= number_format($op_inafectas, 2, '.', ','); ?></td>
                <td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; <?= number_format(($regd['discount'] ?? 0), 2, '.', ','); ?></td>
                <td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; <?= number_format($igv_asig, 2, '.', ','); ?></td>
                <td style="width:12%">S/ &nbsp;&nbsp;&nbsp;&nbsp; <?= number_format($total_venta, 2, '.', ','); ?></td>
            </tr>
        </table>
        <br>
        <table style="border: solid 0.2px black; ">
            <tr>
                <td style=" width:84%; height: 10px;">SON: <?= $leyenda ?></td>
            </tr>
        </table>
    </div>

    <page_footer><br><br>
    <div class="foot"><br><br>
        <table cellspacing="0" cellpadding="0" border="0.2">
            <tr class="cuadro-footer">
                <td style="width: 80%; padding-top: 5px">
                    ¡¡¡ GRACIAS POR SU COMPRA VUELVA PRONTO !!! <br>
                    _____________________________________________________________________________________________________________<br><br>
                    Representación impresa de la <?= $tipo_voucher ?><br>
                    Emitida del sistema del contribuyente autorizado con fecha
                    <b>
                        <?= formatDate($fecha_inicio) ?>
                    </b><br>
                    Puede consultar su comprobante electrónico utilizando su clave SOL, en la plataforma de SUNAT. <?= $web; ?>
                </td>
                <td style="width: 5%; text-align: center;">
                    <?php
                    include_once "vendor/phpqrcode/qrlib.php";
                    
                    // Verificar que todos los datos necesarios estén presentes
                    if (!empty($rucE) && !empty($voucher_type_code) && !empty($serie) && 
                        !empty($correlativo) && !empty($total_venta) && !empty($fecha) && 
                        !empty($tipo_documento_cliente) && !empty($rucC)) {
                        
                        // Formatear fecha y montos
                        $fecha_qr = date('Y-m-d', strtotime($fecha));
                        $total_qr = number_format($total_venta, 2, '.', '');
                        $igv_qr = number_format($igv_asig, 2, '.', '');
                        
                        // Crear contenido del QR
                        $qr_content = implode('|', [
                            $rucE,
                            $voucher_type_code,
                            $serie,
                            $correlativo,
                            $igv_qr,
                            $total_qr,
                            $fecha_qr,
                            $tipo_documento_cliente,
                            $rucC
                        ]);
                        
                        // Ruta temporal para guardar el QR
                        $tempDir = sys_get_temp_dir() . '/';
                        $fileName = 'qr_' . md5($qr_content) . '.png';
                        $filePath = $tempDir . $fileName;
                        
                        // Generar el QR y guardarlo temporalmente
                        QRcode::png($qr_content, $filePath, QR_ECLEVEL_H, 5, 2);
                        
                        // Verificar que el archivo se creó
                        if (file_exists($filePath)) {
                            // Mostrar la imagen del QR
                            echo '<img src="data:image/png;base64,' . base64_encode(file_get_contents($filePath)) . '" />';
                            // Eliminar el archivo temporal
                            unlink($filePath);
                        } else {
                            echo '<span style="color:red;">Error al generar QR</span>';
                        }
                    } else {
                        echo '<span style="color:red;">Faltan datos para generar QR</span>';
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
</page_footer>
</body>
</html>