
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Nandang
 * Date: 12/20/2017
 * Time: 1:41 PM
 * edited by adhi setelah implement abstract class, datetime : 12/04/2018
 */


class C_rekap extends Globalclass {

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('default', TRUE);

    }

    public function inportDBLibrary($db){

        $this->server22 = $this->load->database('server22', TRUE);
        $std = $this->db->query('SELECT s.NPM AS member_id, s.Name AS member_name, 
                                    s.DateOfBirth AS birth_date, s.Address AS member_address,
                                    ast.EmailPU AS member_mail_address, s.Email AS member_email,Gender AS gender
                                    FROM ta_2018.students s 
                                    LEFT JOIN db_academic.auth_students ast
                                    ON (s.NPM = ast.NPM)')->result_array();

        for($i=0;$i<count($std);$i++){
            $d = $std[$i];

//            $d['member_type_id'] = 2;
//            $d['gender'] = ($d['gender']=='P') ? 0 : 1;
//            $d['inst_name'] = 'Podomoro University';
//            $d['member_since_date'] = '2018-01-12';
//            $d['register_date'] = '2018-01-12';
//            $d['expire_date'] = '2019-01-12';
//            $d['input_date'] = '2018-08-20';
//            $d['last_update'] = '2018-08-20';

            $this->server22->set('expire_date', '2019-01-12');
            $this->server22->where('member_id', $d['member_id']);
            $this->server22->update('library.member');

//            $dataMem = $this->server22->select('member_id')->get_where('library.member', array('member_id'=>$d['member_id']))->result_array();


//            if(count($dataMem)<=0){
//                $this->server22->insert('library.member',$d);
//            }

//            $std[$i] = $d;

        }

        print_r($std);


        exit;




        return print_r(json_encode($data));
    }

    public function rekap_($db){
        $db_ = 'ta_'.$db;
        $dataStd = $this->db->query('SELECT * FROM '.$db_.'.students ORDER BY NPM ASC')->result_array();

        $res = [];
        for($c=0;$c<count($dataStd);$c++){
            $NPM = $dataStd[$c]['NPM'];
            $dataS = $this->db->query('SELECT * FROM '.$db_.'.study_planning 
                                                            WHERE SemesterID = 13 AND NPM = "'.$NPM.'" ')
                ->result_array();
            $Credit = 0;
            for($s=0;$s<count($dataS);$s++){
                $Credit = $Credit + $dataS[$s]['Credit'];
            }

            $re = array(
                'NPM' => $NPM,
                'Name' => ucwords(strtolower($dataStd[$c]['Name'])),
                'Credit' => $Credit
            );
            array_push($res,$re);
        }

        echo "<table><tr><th>NPM</th><th>Name</th><th>Credit</th></tr>";

        for($r=0;$r<count($res);$r++){
            echo "<tr><td>".$res[$r]['NPM']."</td><td>".$res[$r]['Name']."</td><td>".$res[$r]['Credit']."</td></tr>";
        }

        echo "</table>";

//            return print_r($res);
    }


}
