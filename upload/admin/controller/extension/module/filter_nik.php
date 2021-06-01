<?php
class ControllerExtensionModuleFilterNik extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/filter_nik');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->request->post['module_filter_nik_filter_group_settings'] = serialize($this->request->post['module_filter_nik_filter_group_settings']);
			$this->model_setting_setting->editSetting('module_filter_nik', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/filter_nik', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/filter_nik', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        $this->load->model('catalog/filter');

        $results = $this->model_catalog_filter->getFilterGroups();

        foreach ($results as $result) {
            $data['filters'][] = array(
                'filter_group_id' => $result['filter_group_id'],
                'name'            => $result['name'],
                'sort_order'      => $result['sort_order'],
            );
        }

        if (isset($this->request->post['module_filter_nik_filter_group_settings'])) {
            $data['module_filter_nik_filter_group_settings'] = $this->request->post['module_filter_nik_filter_group_settings'];
        } else {
            $data['module_filter_nik_filter_group_settings'] = unserialize($this->config->get('module_filter_nik_filter_group_settings'));
        }

		if (isset($this->request->post['module_filter_status'])) {
			$data['module_filter_nik_status'] = $this->request->post['module_filter_nik_status'];
		} else {
			$data['module_filter_nik_status'] = $this->config->get('module_filter_nik_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/filter_nik', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/filter_nik')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}