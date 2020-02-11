<link rel="stylesheet" type="text/css" href="<?=base_url('assets/OrgChart/dist/css/jquery.orgchart.min.css')?>">
<script type="text/javascript" src="<?=base_url('assets/OrgChart/dist/js/jquery.orgchart.min.js')?>"></script>
<script type="text/javascript">
	'use strict';

(function($){

  $(function() {

    var datascource = {
      'name': 'Lao Lao',
      'title': 'general manager',
      'children': [
        { 'name': 'Bo Miao', 'title': 'department manager' },
        { 'name': 'Su Miao', 'title': 'department manager',
          'children': [
            { 'name': 'Tie Hua', 'title': 'senior engineer' },
            { 'name': 'Hei Hei', 'title': 'senior engineer',
              'children': [
                { 'name': 'Pang Pang', 'title': 'engineer' },
                { 'name': 'Xiang Xiang', 'title': 'UE engineer' }
              ]
            }
          ]
        },
        { 'name': 'Hong Miao', 'title': 'department manager' },
        { 'name': 'Chun Miao', 'title': 'department manager' }
      ]
    };

    var oc = $('#chart-container').orgchart({
      'data' : datascource,
      'nodeContent': 'title',
      'pan': true,
      'zoom': true
    });

  });

})(jQuery);
</script>

<style type="text/css">
	#chart-container {
	  font-family: Arial;
	  height: 420px;
	  border: 2px dashed #aaa;
	  border-radius: 5px;
	  overflow: auto;
	  text-align: center;
	}

	#github-link {
	  position: fixed;
	  right: 10px;
	  font-size: 3em;
	}
</style>


<div id="chart-container"></div>
