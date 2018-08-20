
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
