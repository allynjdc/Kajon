$(".date").html(function(){
	var date = $(this).text().split("-");

	if (date.length == 3) {
		switch(date[0]) {
			case "01":
				date[0] = "January";
				break;
			case "02":
				date[0] = "February";
				break;
			case "03":
				date[0] = "March";
				break;
			case "04":
				date[0] = "April";
				break;
			case "05":
				date[0] = "May";
				break;
			case "06":
				date[0] = "June";
				break;
			case "07":
				date[0] = "July";
				break;
			case "08":
				date[0] = "August";
				break;
			case "09":
				date[0] = "September";
				break;
			case "10":
				date[0] = "October";
				break;
			case "11":
				date[0] = "November";
				break;
		    default:
		        date[0] = "December";
		}
	
		date[1] = " " + date[1] + ", ";

		date.join();
	}


	return date;
});