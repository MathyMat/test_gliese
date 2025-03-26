<?php 
// --
class M_Igvinvoicing extends Model {
  // --
  public function __construct()
  {
      parent::__construct();
  }

  // --
  public function get_sale($campus_id)
  {
      try {
          $sql = 'SELECT 
              s.id AS id_sale,
              c.name AS client,
              vt.description AS voucher_type,
              pt.description AS payment_type,
              s.date_time,
              s.series,
              s.correlative,
              s.currency,
              s.tax,
              s.total_igv,
              s.total_sale,
              c.address,
              s.due_date,
              s.taxable_operations,
              s.status
          FROM sale s
          INNER JOIN customer c ON c.id = s.customer_id
          INNER JOIN voucher_type vt ON vt.id = s.invoice_type_id
          INNER JOIN payment_type pt ON pt.id = s.payment_type_id
          WHERE s.campus_id = :campus_id
          ORDER BY s.id DESC';

          $stmt = $this->pdo->prepare($sql);
          $stmt->bindParam(':campus_id', $campus_id, PDO::PARAM_INT);
          $stmt->execute();

          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

          if ($result) {
              $response = array('status' => 'OK', 'result' => $result);
          } else {
              $response = array('status' => 'ERROR', 'result' => array());
          }
      } catch (PDOException $e) {
          $response = array('status' => 'EXCEPTION', 'result' => $e);
      }

      return $response;
  }

  // --
  public function get_sale_by_id($bind)
  {
      try {
          $sql = 'SELECT 
              s.id AS id_sale,
              c.name AS client,
              vt.description AS voucher_type,
              pt.description AS payment_type,
              s.date_time,
              s.series,
              s.correlative,
              s.currency,
              s.tax,
              s.total_igv,
              s.total_sale,
              c.address,
              s.due_date,
              s.taxable_operations,
              s.status
          FROM sale s
          INNER JOIN customer c ON c.id = s.customer_id
          INNER JOIN voucher_type vt ON vt.id = s.invoice_type_id
          INNER JOIN payment_type pt ON pt.id = s.payment_type_id
          WHERE s.id = :id_sale';

          $stmt = $this->pdo->prepare($sql);
          $stmt->bindParam(':id_sale', $bind['id_sale'], PDO::PARAM_INT);
          $stmt->execute();

          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          if ($result) {
              $response = array('status' => 'OK', 'result' => $result);
          } else {
              $response = array('status' => 'ERROR', 'result' => array());
          }
      } catch (PDOException $e) {
          $response = array('status' => 'EXCEPTION', 'result' => $e);
      }

      return $response;
  }

  // --
  public function get_sale_report($id_sale)
  {
      try {
          $sql = 'SELECT 
              s.id AS id_sale,
              c.name AS client,
              vt.description AS voucher_type,
              pt.description AS payment_type,
              s.date_time,
              s.series,
              s.correlative,
              s.currency,
              s.tax,
              s.total_igv,
              s.total_sale,
              c.address,
              s.due_date,
              s.taxable_operations,
              s.status
          FROM sale s
          INNER JOIN customer c ON c.id = s.customer_id
          INNER JOIN voucher_type vt ON vt.id = s.invoice_type_id
          INNER JOIN payment_type pt ON pt.id = s.payment_type_id
          WHERE s.id = :id_sale';

          $stmt = $this->pdo->prepare($sql);
          $stmt->bindParam(':id_sale', $id_sale, PDO::PARAM_INT);
          $stmt->execute();

          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          if ($result) {
              $response = array('status' => 'OK', 'result' => $result);
          } else {
              $response = array('status' => 'ERROR', 'result' => array());
          }
      } catch (PDOException $e) {
          $response = array('status' => 'EXCEPTION', 'result' => $e);
      }

      return $response;
  }

  // --
  public function get_company()
  {
      try {
          $sql = 'SELECT * FROM company';
          $stmt = $this->pdo->prepare($sql);
          $stmt->execute();
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($result) {
              $response = array('status' => 'OK', 'result' => $result);
          } else {
              $response = array('status' => 'ERROR', 'result' => array());
          }
      } catch (PDOException $e) {
          $response = array('status' => 'EXCEPTION', 'result' => $e);
      }
      return $response;
  }
}