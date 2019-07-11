<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_api3 extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->load->model('m_api');
        $this->load->model('m_rest');
        $this->load->model('akademik/m_tahun_akademik');
        $this->load->model('master/m_master');
        $this->load->library('JWT');
        $this->load->library('google');
    }

    private function getInputToken()
    {
        $token = $this->input->post('token');
        $key = "s3Cr3T-G4N";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    private function getInputToken2()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    public function is_url_exist($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($code == 200){
            $status = true;
        }else{
            $status = false;
        }
        curl_close($ch);
        return $status;
    }

    public function getListMenuAgregator(){

        $data = $this->db->order_by('ID','ASC')->get('db_it.agregator_menu')->result_array();

        return print_r(json_encode($data));

    }

    public function crudTeamAgregagor(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='insertTeamAggr'){

            $dataForm = (array) $data_arr['dataForm'];
            $this->db->insert('db_it.agregator_user',$dataForm);
            $insert_id = $this->db->insert_id();

            $Member = (array) $data_arr['Member'];
            if(count($Member)>0){
                for($i=0;$i<count($Member);$i++){

                    // Cek apakah NIP sudah ada atau blm
                    $dataCk = $this->db->get_where('db_it.agregator_user_member',array(
                        'NIP' => $Member[$i]
                    ))->result_array();

                    if(count($dataCk)<=0){
                        $arr = array(
                            'AUPID' => $insert_id,
                            'NIP' => $Member[$i]
                        );
                        $this->db->insert('db_it.agregator_user_member',$arr);
                    }


                }
            }

            return print_r(1);


        }
        else if($data_arr['action']=='updateTeamAggr'){

            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];

            $this->db->where('ID', $ID);
            $this->db->update('db_it.agregator_user',$dataForm);
            $this->db->reset_query();


            $this->db->where('AUPID', $ID);
            $this->db->delete('db_it.agregator_user_member');
            $this->db->reset_query();

            $Member = (array) $data_arr['Member'];
            if(count($Member)>0){
                for($i=0;$i<count($Member);$i++){
                    // Cek apakah NIP sudah ada atau blm
                    $dataCk = $this->db->get_where('db_it.agregator_user_member',array(
                        'NIP' => $Member[$i]
                    ))->result_array();

                    if(count($dataCk)<=0){
                        $arr = array(
                            'AUPID' => $ID,
                            'NIP' => $Member[$i]
                        );
                        $this->db->insert('db_it.agregator_user_member',$arr);
                    }

                }
            }

            return print_r(1);

        }
        else if($data_arr['action']=='readTeamAggr'){

            $data = $this->db->get('db_it.agregator_user')->result_array();

            for($i=0;$i<count($data);$i++){

                // Get Menu Name
                $ArrMenu = json_decode($data[$i]['Menu']);

                $listMenu = [];
                for($m=0;$m<count($ArrMenu);$m++){

                    $dtm = $this->db->get_where('db_it.agregator_menu',array(
                        'ID' => $ArrMenu[$m]
                    ))->result_array();

                    if(count($dtm)>0){
                        array_push($listMenu,$dtm[0]);
                    }
                }

                $data[$i]['Member'] = $this->db->query('SELECT aum.*, em.Name FROM db_it.agregator_user_member aum 
                                                            LEFT JOIN db_employees.employees em ON (em.NIP = aum.NIP)
                                                            WHERE aum.AUPID = "'.$data[$i]['ID'].'" ')->result_array();

                $data[$i]['DetailMenu'] = $listMenu;

            }



            return print_r(json_encode($data));

        }

    }



}
