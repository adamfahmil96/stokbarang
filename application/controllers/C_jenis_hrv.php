<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_jenis_hrv extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_jenis_hrv');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'c_jenis_hrv/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'c_jenis_hrv/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'c_jenis_hrv/index.html';
            $config['first_url'] = base_url() . 'c_jenis_hrv/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->M_jenis_hrv->total_rows($q);
        $c_jenis_hrv = $this->M_jenis_hrv->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'c_jenis_hrv_data' => $c_jenis_hrv,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('c_jenis_hrv/jenis_barang_list', $data);
    }

    public function read($id)
    {
        $row = $this->M_jenis_hrv->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'id_jenis' => $row->id_jenis,
		'jenis' => $row->jenis,
		'flag_jenis' => $row->flag_jenis,
	    );
            $this->load->view('c_jenis_hrv/jenis_barang_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('c_jenis_hrv'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('c_jenis_hrv/create_action'),
	    'id' => set_value('id'),
	    'id_jenis' => set_value('id_jenis'),
	    'jenis' => set_value('jenis'),
	    'flag_jenis' => set_value('flag_jenis'),
	);
        $this->load->view('c_jenis_hrv/jenis_barang_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'id_jenis' => $this->input->post('id_jenis',TRUE),
		'jenis' => $this->input->post('jenis',TRUE),
		'flag_jenis' => $this->input->post('flag_jenis',TRUE),
	    );

            $this->M_jenis_hrv->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('c_jenis_hrv'));
        }
    }

    public function update($id)
    {
        $row = $this->M_jenis_hrv->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('c_jenis_hrv/update_action'),
		'id' => set_value('id', $row->id),
		'id_jenis' => set_value('id_jenis', $row->id_jenis),
		'jenis' => set_value('jenis', $row->jenis),
		'flag_jenis' => set_value('flag_jenis', $row->flag_jenis),
	    );
            $this->load->view('c_jenis_hrv/jenis_barang_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('c_jenis_hrv'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'id_jenis' => $this->input->post('id_jenis',TRUE),
		'jenis' => $this->input->post('jenis',TRUE),
		'flag_jenis' => $this->input->post('flag_jenis',TRUE),
	    );

            $this->M_jenis_hrv->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('c_jenis_hrv'));
        }
    }

    public function delete($id)
    {
        $row = $this->M_jenis_hrv->get_by_id($id);

        if ($row) {
            $this->M_jenis_hrv->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('c_jenis_hrv'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('c_jenis_hrv'));
        }
    }

    public function _rules()
    {
	$this->form_validation->set_rules('id_jenis', 'id jenis', 'trim|required');
	$this->form_validation->set_rules('jenis', 'jenis', 'trim|required');
	$this->form_validation->set_rules('flag_jenis', 'flag jenis', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "jenis_barang.xls";
        $judul = "jenis_barang";
        $tablehead = 0;
        $tablebody = 1;
        $nourut = 1;
        //penulisan header
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $namaFile . "");
        header("Content-Transfer-Encoding: binary ");

        xlsBOF();

        $kolomhead = 0;
        xlsWriteLabel($tablehead, $kolomhead++, "No");
	xlsWriteLabel($tablehead, $kolomhead++, "Id Jenis");
	xlsWriteLabel($tablehead, $kolomhead++, "Jenis");
	xlsWriteLabel($tablehead, $kolomhead++, "Flag Jenis");

	foreach ($this->M_jenis_hrv->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->id_jenis);
	    xlsWriteLabel($tablebody, $kolombody++, $data->jenis);
	    xlsWriteLabel($tablebody, $kolombody++, $data->flag_jenis);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file C_jenis_hrv.php */
/* Location: ./application/controllers/C_jenis_hrv.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-09-06 05:39:33 */
/* http://harviacode.com */
