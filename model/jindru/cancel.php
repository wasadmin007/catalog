<?php
class ModelJindruCancel extends Model {
	public function addCancel($data) {	
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "cancel` SET order_id = '" . (int)$data['order_id'] . "', customer_id = '" . (int)$this->customer->getId() . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', product = '" . $this->db->escape($data['product']) . "', model = '" . $this->db->escape($data['model']) . "', quantity = '" . (int)$data['quantity'] . "', opened = '" . (int)$data['opened'] . "', cancel_reason_id = '" . (int)$data['cancel_reason_id'] . "', cancel_status_id = '" . (int)$this->config->get('config_return_status_id') . "', comment = '" . $this->db->escape($data['comment']) . "', date_ordered = '" . $this->db->escape($data['date_ordered']) . "', date_added = NOW(), date_modified = NOW()");
	   
	}
	
	public function getCancel($cancel_id) {
		$query = $this->db->query("SELECT r.cancel_id, r.order_id, r.firstname, r.lastname, r.email, r.telephone, r.product, r.model, r.quantity, r.opened, (SELECT rr.name FROM " . DB_PREFIX . "cancel_reason rr WHERE rr.cancel_reason_id = r.cancel_reason_id AND rr.language_id = '" . (int)$this->config->get('config_language_id') . "') AS reason, (SELECT ra.name FROM " . DB_PREFIX . "cancel_action ra WHERE ra.cancel_action_id = r.cancel_action_id AND ra.language_id = '" . (int)$this->config->get('config_language_id') . "') AS action, (SELECT rs.name FROM " . DB_PREFIX . "cancel_status rs WHERE rs.cancel_status_id = r.cancel_status_id AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, r.comment, r.date_ordered, r.date_added, r.date_modified FROM `" . DB_PREFIX . "cancel` r WHERE cancel_id = '" . (int)$cancel_id . "' AND customer_id = '" . $this->customer->getId() . "'");
		
		return $query->row;
	}
	
	public function getCancels($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}	
				
		$query = $this->db->query("SELECT r.cancel_id, r.order_id, r.firstname, r.lastname, rs.name as status, r.date_added FROM `" . DB_PREFIX . "cancel` r LEFT JOIN " . DB_PREFIX . "cancel_status rs ON (r.cancel_status_id = rs.cancel_status_id) WHERE r.customer_id = '" . $this->customer->getId() . "' AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.cancel_id DESC LIMIT " . (int)$start . "," . (int)$limit);
		
		return $query->rows;
	}
			
	public function getTotalCancels() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "cancel`WHERE customer_id = '" . $this->customer->getId() . "'");
		
		return $query->row['total'];
	}
	
	public function getCancelHistories($cancel_id) {
		$query = $this->db->query("SELECT rh.date_added, rs.name AS status, rh.comment, rh.notify FROM " . DB_PREFIX . "cancel_history rh LEFT JOIN " . DB_PREFIX . "cancel_status rs ON rh.cancel_status_id = rs.cancel_status_id WHERE rh.cancel_id = '" . (int)$cancel_id . "' AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY rh.date_added ASC");

		return $query->rows;
	}			
}
?>

   