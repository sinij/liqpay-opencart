<?php

class ControllerExtensionPaymentLiqPayCheckout extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/payment/liqpay_checkout');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('liqpay_checkout', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['text_pay'] = $this->language->get('text_pay');
        $data['text_card'] = $this->language->get('text_card');

        $data['entry_public_key'] = $this->language->get('entry_public_key');
        $data['entry_private_key'] = $this->language->get('entry_private_key');
        $data['entry_api'] = $this->language->get('entry_api');
        $data['entry_type'] = $this->language->get('entry_type');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['help_total'] = $this->language->get('help_total');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['public_key'])) {
            $data['error_public_key'] = $this->error['public_key'];
        } else {
            $data['error_public_key'] = '';
        }

        if (isset($this->error['private_key'])) {
            $data['error_private_key'] = $this->error['private_key'];
        } else {
            $data['error_private_key'] = '';
        }

        if (isset($this->error['api'])) {
            $data['error_api'] = $this->error['api'];
        } else {
            $data['error_api'] = '';
        }

        if (isset($this->error['action'])) {
            $data['error_action'] = $this->error['action'];
        } else {
            $data['error_action'] = '';
        }

        if (isset($this->error['type'])) {
            $data['error_type'] = $this->error['type'];
        } else {
            $data['error_type'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/liqpay_checkout', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link('extension/payment/liqpay_checkout', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['liqpay_checkout_public_key'])) {
            $data['liqpay_checkout_public_key'] = $this->request->post['liqpay_checkout_public_key'];
        } else {
            $data['liqpay_checkout_public_key'] = $this->config->get('liqpay_checkout_public_key');
        }

        if (isset($this->request->post['liqpay_checkout_private_key'])) {
            $data['liqpay_checkout_private_key'] = $this->request->post['liqpay_checkout_private_key'];
        } else {
            $data['liqpay_checkout_private_key'] = $this->config->get('liqpay_checkout_private_key');
        }

        if (isset($this->request->post['liqpay_checkout_api'])) {
            $data['liqpay_checkout_api'] = $this->request->post['liqpay_checkout_api'];
        } else {
            $data['liqpay_checkout_api'] = $this->config->get('liqpay_checkout_api');
        }

        // if (isset($this->request->post['liqpay_checkout_action'])) {
        // 	$data['liqpay_checkout_action'] = $this->request->post['liqpay_checkout_action'];
        // } else {
        // 	$data['liqpay_checkout_action'] = $this->config->get('liqpay_checkout_action');
        // }

        if (isset($this->request->post['liqpay_checkout_type'])) {
            $data['liqpay_checkout_type'] = $this->request->post['liqpay_checkout_type'];
        } else {
            $data['liqpay_checkout_type'] = $this->config->get('liqpay_checkout_type');
        }

        if (isset($this->request->post['liqpay_checkout_total'])) {
            $data['liqpay_checkout_total'] = $this->request->post['liqpay_checkout_total'];
        } else {
            $data['liqpay_checkout_total'] = $this->config->get('liqpay_checkout_total');
        }

        if (isset($this->request->post['liqpay_checkout_order_status_id'])) {
            $data['liqpay_checkout_order_status_id'] = $this->request->post['liqpay_checkout_order_status_id'];
        } else {
            $data['liqpay_checkout_order_status_id'] = $this->config->get('liqpay_checkout_order_status_id');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['liqpay_checkout_geo_zone_id'])) {
            $data['liqpay_checkout_geo_zone_id'] = $this->request->post['liqpay_checkout_geo_zone_id'];
        } else {
            $data['liqpay_checkout_geo_zone_id'] = $this->config->get('liqpay_checkout_geo_zone_id');
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['liqpay_checkout_status'])) {
            $data['liqpay_checkout_status'] = $this->request->post['liqpay_checkout_status'];
        } else {
            $data['liqpay_checkout_status'] = $this->config->get('liqpay_checkout_status');
        }

        if (isset($this->request->post['liqpay_checkout_sort_order'])) {
            $data['liqpay_checkout_sort_order'] = $this->request->post['liqpay_checkout_sort_order'];
        } else {
            $data['liqpay_checkout_sort_order'] = $this->config->get('liqpay_checkout_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/liqpay_checkout', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/payment/liqpay_checkout')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['liqpay_checkout_public_key']) {
            $this->error['public_key'] = $this->language->get('error_public_key');
        }

        if (!$this->request->post['liqpay_checkout_private_key']) {
            $this->error['private_key'] = $this->language->get('error_private_key');
        }

        if (!$this->request->post['liqpay_checkout_api']) {
            $this->error['api'] = $this->language->get('error_api');
        }

        // if (!$this->request->post['liqpay_checkout_action']) {
        // 	$this->error['action'] = $this->language->get('error_action');
        // }

        return !$this->error;
    }
}