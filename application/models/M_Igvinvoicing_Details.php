<?php
class M_Igvinvoicing_Details extends Model {

    public function create_invoice($header_data, $details) {
        try {
            $this->pdo->beginTransaction();

            // 1. Insertar cabecera (sin client_id)
            $invoice_id = $this->insert_header($header_data);

            // 2. Insertar detalles
            $this->insert_details($invoice_id, $details, $header_data['date_time']);

            $this->pdo->commit();

            return [
                'status' => 'OK',
                'message' => 'Factura creada correctamente',
                'data' => [
                    'invoice_id' => $invoice_id,
                    'series' => $header_data['series'],
                    'correlative' => $header_data['correlative']
                ]
            ];

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return [
                'status' => 'ERROR',
                'message' => 'Error al crear factura: ' . $e->getMessage()
            ];
        }
    }

    private function insert_header($data) {
        $sql = "INSERT INTO igvinvoice (
            user_id, voucher_type_code, series, correlative, 
            date_time, due_date, currency, payment_type_code, payment_method, 
            tax, taxable_operations, total_igv, total_sale, legend, 
            status, time, assigned_igv, type, document_number_cli, address_cli
        ) VALUES (
            :user_id, :voucher_type_code, :series, :correlative, 
            :date_time, :due_date, :currency, :payment_type_code, :payment_method, 
            :tax, :taxable_operations, :total_igv, :total_sale, :legend, 
            :status, :time, :assigned_igv, :type, :document_number_cli, :address_cli
        )";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    private function insert_details($invoice_id, $details, $sale_date) {
        $sql = "INSERT INTO igvinvoice_detail (
            invoice_id, product_code, product_description, unit_of_measure,
            quantity, sale_price, affectation, tax_percentage, sold_date
        ) VALUES (
            :invoice_id, :product_code, :product_description, :unit_of_measure,
            :quantity, :sale_price, :affectation, :tax_percentage, :sold_date
        )";
        
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($details as $detail) {
            $detail['invoice_id'] = $invoice_id;
            $detail['sold_date'] = $sale_date;
            $stmt->execute($detail);
        }
    }

    public function get_series($voucher_type) {
        $sql = "SELECT 
            CASE 
                WHEN :voucher_type = 1 THEN 'F001' 
                WHEN :voucher_type = 2 THEN 'B001' 
                ELSE 'T001' 
            END as series,
            LPAD(IFNULL(
                (SELECT MAX(CAST(correlative AS UNSIGNED)) + 1 
                FROM igvinvoice 
                WHERE voucher_type_code = :voucher_type
            ), 1), 8, '0') as correlative";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['voucher_type' => $voucher_type]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update_correlative($voucher_type, $correlative) {
        return true;
    }
}