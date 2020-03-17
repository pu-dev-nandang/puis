class Class_ticketing_dashboard {
		
	constructor(ArrSelectOptionDepartment){
		this.DeptArr=ArrSelectOptionDepartment;
	}


	LoadDefault = () => {
		this.LoadTable1();
		this.LoadTable2();
	}

	LoadTable1 = () => {
		let selectorTable = $('#table1').find('table');
	}

	LoadTable2 = () => {
		let selectorTable = $('#table2').find('table');
	}	
}